<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;

class OrderController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * 注文確認画面
     */
    public function checkout()
    {
        if (!Auth::check()) {
            // カートページのURLをintendedとして保存
            return redirect()->guest(route('login'))
                ->with('error', '注文にはログインが必要です。')
                ->with('intended_url', route('cart.index'));
        }

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'カートが空です。');
        }

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::with('shop.company')
                ->where('id', $productId)
                ->where('delete_flg', 0)
                ->whereIn('status', [Product::STATUS_ON_SALE, Product::STATUS_OUT_OF_STOCK])
                ->first();

            if ($product && $product->shop && $product->shop->status === Shop::STATUS_PUBLIC && !$product->shop->delete_flg) {
                $availableQuantity = min($quantity, $product->stock);
                if ($availableQuantity > 0) {
                    $subtotal = $product->price * $availableQuantity;
                    $items[] = [
                        'product' => $product,
                        'quantity' => $availableQuantity,
                        'subtotal' => $subtotal,
                    ];
                    $total += $subtotal;
                }
            }
        }

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', '注文できる商品がありません。');
        }

        $user = Auth::user();
        
        // メールアドレスが有効かチェック
        $hasValidEmail = !empty($user->email) && filter_var($user->email, FILTER_VALIDATE_EMAIL);

        return view('orders.checkout', compact('items', 'total', 'user', 'hasValidEmail'));
    }

    /**
     * Stripe決済セッション作成
     */
    public function createStripeSession(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Stripe APIキーの確認
        $stripeSecret = config('services.stripe.secret');
        if (!$stripeSecret) {
            return redirect()->route('orders.checkout')
                ->with('error', 'Stripe APIキーが設定されていません。管理者にお問い合わせください。');
        }

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'カートが空です。');
        }

        // カート内の商品をショップごとにグループ化
        $shopItems = [];
        $items = [];
        $lineItems = [];

        foreach ($cart as $productId => $quantity) {
            $product = Product::with('shop')
                ->where('id', $productId)
                ->where('delete_flg', 0)
                ->whereIn('status', [Product::STATUS_ON_SALE, Product::STATUS_OUT_OF_STOCK])
                ->first();

            if (!$product || !$product->shop || $product->shop->status !== Shop::STATUS_PUBLIC || $product->shop->delete_flg) {
                continue;
            }

            $availableQuantity = min($quantity, $product->stock);
            if ($availableQuantity <= 0) {
                continue;
            }

            $shopId = $product->shop_id;
            if (!isset($shopItems[$shopId])) {
                $shopItems[$shopId] = [];
            }

            $subtotal = $product->price * $availableQuantity;
            $shopItems[$shopId][] = [
                'product' => $product,
                'quantity' => $availableQuantity,
                'subtotal' => $subtotal,
            ];
            $items[] = [
                'product' => $product,
                'quantity' => $availableQuantity,
                'subtotal' => $subtotal,
            ];

            // Stripe用のLineItemを作成
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $product->price,
                ],
                'quantity' => $availableQuantity,
            ];
        }

        if (empty($shopItems)) {
            return redirect()->route('cart.index')->with('error', '注文できる商品がありません。');
        }

        try {
            // 一時的に注文データをセッションに保存
            Session::put('pending_orders', $shopItems);

            // ユーザーのメールアドレスを取得
            $user = Auth::user();
            $customerEmail = null;
            $validEmail = false;
            
            // まず、フォームから送信されたメールアドレスを優先的に取得
            $inputEmail = $request->input('customer_email');
            if (!empty($inputEmail) && filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) {
                $customerEmail = $inputEmail;
                $validEmail = true;
            } else {
                // フォームからメールアドレスが送信されていない場合、ユーザーのメールアドレスを使用
                $customerEmail = $user->email ?? null;
                if (!empty($customerEmail) && filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
                    $validEmail = true;
                } else {
                    Log::warning('Invalid email address for Stripe checkout', [
                        'user_id' => Auth::id(),
                        'email' => $customerEmail,
                        'input_email' => $inputEmail,
                    ]);
                }
            }

            // Stripe Customerを作成または取得（銀行振込に必要）
            $stripeCustomerId = null;
            $stripeCustomer = null;
            
            // ユーザーに関連するCompanyからStripe Customer IDを取得（存在する場合）
            // 個人ユーザーの場合は、新規にCustomerを作成
            if ($user->company && $user->company->stripe_customer_id) {
                $stripeCustomerId = $user->company->stripe_customer_id;
                // 既存のCustomerを取得してメールアドレスを更新
                try {
                    $stripeCustomer = Customer::retrieve($stripeCustomerId);
                    // メールアドレスが有効で、Customerのメールアドレスと異なる場合は更新
                    if ($validEmail && $customerEmail && $stripeCustomer->email !== $customerEmail) {
                        Customer::update($stripeCustomerId, ['email' => $customerEmail]);
                        $stripeCustomer->email = $customerEmail;
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to retrieve Stripe Customer', [
                        'customer_id' => $stripeCustomerId,
                        'error' => $e->getMessage(),
                    ]);
                    // Customerが見つからない場合は新規作成
                    $stripeCustomerId = null;
                }
            }
            
            // Customerが存在しない場合は新規作成
            if (!$stripeCustomerId) {
                // 新しいStripe Customerを作成
                $customerParams = [
                    'metadata' => [
                        'user_id' => Auth::id(),
                    ],
                ];
                
                // メールアドレスが有効な場合は設定
                if ($validEmail && $customerEmail) {
                    $customerParams['email'] = $customerEmail;
                }
                
                $stripeCustomer = Customer::create($customerParams);
                $stripeCustomerId = $stripeCustomer->id;
                
                // Companyがあれば保存
                if ($user->company) {
                    $user->company->stripe_customer_id = $stripeCustomerId;
                    $user->company->save();
                }
            }

            // Webhookで注文を作成するため、shopItemsをシリアライズ可能な形式に変換
            $shopItemsForMetadata = [];
            foreach ($shopItems as $shopId => $shopItemList) {
                $shopItemsForMetadata[$shopId] = [];
                foreach ($shopItemList as $item) {
                    $shopItemsForMetadata[$shopId][] = [
                        'product_id' => $item['product']->id,
                        'product_name' => $item['product']->name,
                        'product_price' => $item['product']->price,
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['subtotal'],
                        'unit_price' => $item['product']->price,
                    ];
                }
            }
            
            // Stripe Checkoutセッションを作成
            // 日本の決済方法をサポート: クレジットカード、コンビニ決済、銀行振込
            // 決済方法のオプション設定（konbiniは常に有効）
            $paymentMethodOptions = [
                'konbini' => [
                    'expires_after_days' => 5, // 5日以内に支払い
                ],
            ];
            
            // payment_method_typesの初期設定（両方の決済方法を含める）
            // konbiniはcustomerパラメータが不要だが、customer_balanceはcustomerパラメータが必須
            $paymentMethodTypes = ['card', 'konbini'];
            
            // customer_balance（銀行振込）を使用する場合、customerパラメータが必須
            // customerが設定されている場合のみ、customer_balanceを追加
            if ($stripeCustomerId) {
                $paymentMethodTypes[] = 'customer_balance';
                $paymentMethodOptions['customer_balance'] = [
                    'funding_type' => 'bank_transfer',
                    'bank_transfer' => [
                        'type' => 'jp_bank_transfer', // 日本の銀行振込
                    ],
                ];
            }
            
            $checkoutSessionParams = [
                'payment_method_types' => $paymentMethodTypes,
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('orders.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('orders.checkout') . '?canceled=1',
                'metadata' => [
                    'user_id' => Auth::id(),
                    'shop_items' => json_encode($shopItemsForMetadata), // セッションデータをmetadataに保存（Webhookで注文を作成するため）
                ],
                'payment_method_options' => $paymentMethodOptions,
            ];
            
            // customer_balance（銀行振込）を使用する場合のみ、customerパラメータを設定
            // customerパラメータを設定しても、konbiniが利用可能であることを期待
            // もしkonbiniが除外される場合は、Stripeのアカウント設定を確認する必要がある
            if ($stripeCustomerId && in_array('customer_balance', $paymentMethodTypes)) {
                $checkoutSessionParams['customer'] = $stripeCustomerId;
            }
            
            // メールアドレスが有効な場合のみcustomer_emailを設定
            // 無効な場合は設定しないことで、Stripe Checkoutでユーザーが入力できるようになる
            // 既存のCustomerがある場合は、Customerのメールアドレスを使用
            $emailForCheckout = null;
            if ($validEmail && $customerEmail) {
                $emailForCheckout = $customerEmail;
            } elseif ($stripeCustomer && !empty($stripeCustomer->email)) {
                $emailForCheckout = $stripeCustomer->email;
            }
            
            if ($emailForCheckout) {
                $checkoutSessionParams['customer_email'] = $emailForCheckout;
            }
            
            // デバッグ用ログ
            Log::info('Creating Stripe Checkout Session', [
                'payment_method_types' => $checkoutSessionParams['payment_method_types'],
                'payment_method_options' => $checkoutSessionParams['payment_method_options'],
                'has_customer' => isset($checkoutSessionParams['customer']),
                'customer_id' => $checkoutSessionParams['customer'] ?? null,
                'currency' => $lineItems[0]['price_data']['currency'] ?? 'unknown',
            ]);
            
            $checkoutSession = StripeSession::create($checkoutSessionParams);
            
            // 作成されたセッションの情報をログに記録
            Log::info('Stripe Checkout Session Created', [
                'session_id' => $checkoutSession->id,
                'payment_method_types' => $checkoutSession->payment_method_types ?? [],
                'payment_method_types_requested' => $checkoutSessionParams['payment_method_types'],
                'url' => $checkoutSession->url,
            ]);
            
            // konbiniが除外されている場合、警告をログに記録
            if (!in_array('konbini', $checkoutSession->payment_method_types ?? [])) {
                Log::warning('konbini was excluded from payment_method_types', [
                    'requested' => $checkoutSessionParams['payment_method_types'],
                    'actual' => $checkoutSession->payment_method_types ?? [],
                    'has_customer' => isset($checkoutSessionParams['customer']),
                ]);
            }

            return redirect($checkoutSession->url);
        } catch (ApiErrorException $e) {
            Log::error('Stripe Checkout Session Creation Failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'cart' => $cart,
            ]);
            return redirect()->route('orders.checkout')
                ->with('error', '決済セッションの作成に失敗しました: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Unexpected Error in createStripeSession', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);
            return redirect()->route('orders.checkout')
                ->with('error', 'エラーが発生しました: ' . $e->getMessage());
        }
    }

    /**
     * Stripe決済成功時の処理
     */
    public function stripeSuccess(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()->route('cart.index')->with('error', 'セッションIDが無効です。');
        }

        try {
            $checkoutSession = StripeSession::retrieve($sessionId);

            // 既に注文が存在するかチェック（Webhookで作成された可能性がある）
            $existingOrder = Order::where('stripe_checkout_session_id', $sessionId)->first();
            if ($existingOrder) {
                // 既に注文が存在する場合は、注文履歴にリダイレクト
                return redirect()->route('mypage.orders.index')
                    ->with('status', '注文が確定しました。支払い方法に応じて、支払い情報をご確認ください。');
            }

            // コンビニ決済・銀行振込などの非同期決済の場合は、payment_statusが'unpaid'でも正常
            $paymentMethodTypes = $checkoutSession->payment_method_types ?? [];
            $isAsyncPayment = in_array('konbini', $paymentMethodTypes) || in_array('customer_balance', $paymentMethodTypes);
            
            // 非同期決済でない場合で、payment_statusが'unpaid'の場合はエラー
            if ($checkoutSession->payment_status === 'unpaid' && !$isAsyncPayment) {
                return redirect()->route('orders.checkout')
                    ->with('error', '決済が完了していません。');
            }
            
            // 非同期決済の場合は、注文を作成するが入金待ちステータスにする
            // 即時決済（クレジットカード）でpayment_statusが'paid'の場合は入金確認済み
            // 非同期決済（コンビニ・銀行振込）でpayment_statusが'unpaid'の場合は入金待ち
            $orderStatus = ($checkoutSession->payment_status === 'paid') 
                ? Order::STATUS_PAID 
                : Order::STATUS_NEW; // コンビニ決済・銀行振込などは入金待ち

            $shopItems = Session::get('pending_orders', []);
            if (empty($shopItems)) {
                // セッションデータがない場合でも、注文が存在しない場合はエラー
                // ただし、Webhookで既に注文が作成されている可能性があるため、再度チェック
                $existingOrder = Order::where('stripe_checkout_session_id', $sessionId)->first();
                if ($existingOrder) {
                    return redirect()->route('mypage.orders.index')
                        ->with('status', '注文が確定しました。支払い方法に応じて、支払い情報をご確認ください。');
                }
                return redirect()->route('cart.index')->with('error', '注文データが見つかりません。');
            }

            DB::beginTransaction();
            try {
                $orders = [];

                // ショップごとに注文を作成
                foreach ($shopItems as $shopId => $shopItemList) {
                    $shopTotal = array_sum(array_column($shopItemList, 'subtotal'));

                    $userId = Auth::id();
                    
                    $order = Order::create([
                        'shop_id' => $shopId,
                        'user_id' => $userId,
                        'total_amount' => $shopTotal,
                        'status' => $orderStatus, // 即時決済は入金確認済み、非同期決済は入金待ち
                        'stripe_checkout_session_id' => $sessionId,
                        'stripe_payment_intent_id' => $checkoutSession->payment_intent,
                        'delete_flg' => 0,
                    ]);
                    
                    // デバッグ用ログ
                    Log::info('Order created in stripeSuccess', [
                        'order_id' => $order->id,
                        'user_id' => $userId,
                        'shop_id' => $shopId,
                        'status' => $orderStatus,
                        'session_id' => $sessionId,
                        'total_amount' => $shopTotal,
                    ]);

                    // 注文明細を作成
                    foreach ($shopItemList as $item) {
                        $product = $item['product'];
                        // 在庫を再度確認（ロック）
                        $product = Product::lockForUpdate()->find($product->id);
                        if ($product->stock < $item['quantity']) {
                            throw new \Exception("「{$product->name}」の在庫が不足しています。");
                        }

                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'quantity' => $item['quantity'],
                            'unit_price' => $product->price,
                            'delete_flg' => 0,
                        ]);

                        // 在庫を減らす
                        $product->stock -= $item['quantity'];
                        if ($product->stock <= 0) {
                            $product->status = Product::STATUS_OUT_OF_STOCK;
                        }
                        $product->save();
                    }

                    $orders[] = $order;
                }

                DB::commit();

                // カートと一時データをクリア
                Session::forget('cart');
                Session::forget('pending_orders');

                // 注文履歴にリダイレクト（pending状態でも注文履歴に表示される）
                $statusMessage = ($orderStatus === Order::STATUS_PAID) 
                    ? '決済が完了しました。注文が確定しました。'
                    : '注文が確定しました。支払い方法に応じて、支払い情報をご確認ください。';
                
                return redirect()->route('mypage.orders.index')
                    ->with('status', $statusMessage);
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('cart.index')
                    ->with('error', '注文処理中にエラーが発生しました: ' . $e->getMessage());
            }
        } catch (ApiErrorException $e) {
            return redirect()->route('orders.checkout')
                ->with('error', '決済情報の取得に失敗しました: ' . $e->getMessage());
        }
    }

    /**
     * 注文確定処理（従来の方法 - 決済なし）
     */
    public function confirm(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'カートが空です。');
        }

        // カート内の商品をショップごとにグループ化
        $shopItems = [];
        $items = [];

        foreach ($cart as $productId => $quantity) {
            $product = Product::with('shop')
                ->where('id', $productId)
                ->where('delete_flg', 0)
                ->whereIn('status', [Product::STATUS_ON_SALE, Product::STATUS_OUT_OF_STOCK])
                ->lockForUpdate()
                ->first();

            if (!$product || !$product->shop || $product->shop->status !== Shop::STATUS_PUBLIC || $product->shop->delete_flg) {
                continue;
            }

            $availableQuantity = min($quantity, $product->stock);
            if ($availableQuantity <= 0) {
                continue;
            }

            $shopId = $product->shop_id;
            if (!isset($shopItems[$shopId])) {
                $shopItems[$shopId] = [];
            }

            $subtotal = $product->price * $availableQuantity;
            $shopItems[$shopId][] = [
                'product' => $product,
                'quantity' => $availableQuantity,
                'subtotal' => $subtotal,
            ];
            $items[] = [
                'product' => $product,
                'quantity' => $availableQuantity,
                'subtotal' => $subtotal,
            ];
        }

        if (empty($shopItems)) {
            return redirect()->route('cart.index')->with('error', '注文できる商品がありません。');
        }

        // 在庫チェック（再度確認）
        foreach ($items as $item) {
            $product = $item['product'];
            if ($product->stock < $item['quantity']) {
                return redirect()->route('cart.index')->with('error', "「{$product->name}」の在庫が不足しています。");
            }
        }

        DB::beginTransaction();
        try {
            $orders = [];

            // ショップごとに注文を作成
            foreach ($shopItems as $shopId => $shopItemList) {
                $shopTotal = array_sum(array_column($shopItemList, 'subtotal'));

                $order = Order::create([
                    'shop_id' => $shopId,
                    'user_id' => Auth::id(),
                    'total_amount' => $shopTotal,
                    'status' => Order::STATUS_NEW,
                    'delete_flg' => 0,
                ]);

                // 注文明細を作成
                foreach ($shopItemList as $item) {
                    $product = $item['product'];
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->price,
                        'delete_flg' => 0,
                    ]);

                    // 在庫を減らす
                    $product->stock -= $item['quantity'];
                    if ($product->stock <= 0) {
                        $product->status = Product::STATUS_OUT_OF_STOCK;
                    }
                    $product->save();
                }

                $orders[] = $order;
            }

            DB::commit();

            // カートをクリア
            Session::forget('cart');

            // 最初の注文IDを返す（複数注文の場合は最初のものを表示）
            return redirect()->route('orders.complete', $orders[0])->with('status', '注文が確定しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', '注文処理中にエラーが発生しました。もう一度お試しください。');
        }
    }

    /**
     * 注文完了画面
     */
    public function complete(Order $order)
    {
        if (!Auth::check() || $order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['shop.company', 'orderItems.product']);

        return view('orders.complete', compact('order'));
    }

    /**
     * 注文履歴一覧（マイページ）
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        
        // デバッグ用ログ
        Log::info('Fetching orders for user', ['user_id' => $userId]);
        
        $orders = Order::where('user_id', $userId)
            ->where('delete_flg', 0)
            ->with(['shop.company', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // デバッグ用ログ
        Log::info('Orders found', [
            'user_id' => $userId,
            'count' => $orders->total(),
            'order_ids' => $orders->pluck('id')->toArray(),
        ]);

        // 各注文の支払い情報URLを取得（銀行振込・コンビニ決済の場合のみ）
        $paymentUrls = [];
        foreach ($orders as $order) {
            $paymentUrl = null;
            $paymentMethodType = null;
            
            if ($order->stripe_checkout_session_id) {
                try {
                    $checkoutSession = StripeSession::retrieve($order->stripe_checkout_session_id);
                    
                    // 支払い方法を確認
                    $paymentMethodTypes = $checkoutSession->payment_method_types ?? [];
                    if (in_array('konbini', $paymentMethodTypes) || in_array('customer_balance', $paymentMethodTypes)) {
                        // コンビニ決済や銀行振込の場合、支払い情報URLを取得
                        // まず、PaymentIntentから支払い情報を取得（コンビニ決済の場合）
                        if (in_array('konbini', $paymentMethodTypes) && $order->stripe_payment_intent_id) {
                            try {
                                $paymentIntent = \Stripe\PaymentIntent::retrieve($order->stripe_payment_intent_id);
                                // コンビニ決済の場合、next_actionから支払い情報URLを取得
                                if (isset($paymentIntent->next_action->display_details->hosted_voucher_url)) {
                                    $paymentUrl = $paymentIntent->next_action->display_details->hosted_voucher_url;
                                } elseif (isset($paymentIntent->next_action->konbini_display_details->hosted_voucher_url)) {
                                    $paymentUrl = $paymentIntent->next_action->konbini_display_details->hosted_voucher_url;
                                }
                            } catch (\Exception $e) {
                                Log::warning('Failed to retrieve PaymentIntent for konbini payment', [
                                    'order_id' => $order->id,
                                    'payment_intent_id' => $order->stripe_payment_intent_id,
                                    'error' => $e->getMessage(),
                                ]);
                            }
                        }
                        
                        // PaymentIntentから取得できなかった場合、Checkout Sessionから取得
                        if (!$paymentUrl) {
                            if (isset($checkoutSession->hosted_invoice_url)) {
                                $paymentUrl = $checkoutSession->hosted_invoice_url;
                            } elseif (isset($checkoutSession->url)) {
                                // hosted_invoice_urlがない場合は、Checkout URLを使用
                                $paymentUrl = $checkoutSession->url;
                            }
                        }
                        
                        // 支払い方法の種類を判定
                        if (in_array('konbini', $paymentMethodTypes)) {
                            $paymentMethodType = 'konbini';
                        } elseif (in_array('customer_balance', $paymentMethodTypes)) {
                            $paymentMethodType = 'bank_transfer';
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to retrieve Stripe Checkout Session', [
                        'order_id' => $order->id,
                        'session_id' => $order->stripe_checkout_session_id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
            
            if ($paymentUrl) {
                $paymentUrls[$order->id] = [
                    'url' => $paymentUrl,
                    'type' => $paymentMethodType,
                ];
            }
        }

        return view('mypage.orders.index', compact('orders', 'paymentUrls'));
    }

    /**
     * 注文詳細（マイページ）
     */
    public function show(Order $order)
    {
        if (!Auth::check() || $order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['shop.company', 'orderItems.product']);

        // Stripe Checkout Sessionから支払い情報URLを取得（銀行振込・コンビニ決済の場合）
        $paymentUrl = null;
        $paymentMethodType = null;
        
        if ($order->stripe_checkout_session_id) {
            try {
                $checkoutSession = StripeSession::retrieve($order->stripe_checkout_session_id);
                
                // 支払い方法を確認
                $paymentMethodTypes = $checkoutSession->payment_method_types ?? [];
                if (in_array('konbini', $paymentMethodTypes) || in_array('customer_balance', $paymentMethodTypes)) {
                    // コンビニ決済や銀行振込の場合、支払い情報URLを取得
                    // まず、PaymentIntentから支払い情報を取得（コンビニ決済の場合）
                    if (in_array('konbini', $paymentMethodTypes) && $order->stripe_payment_intent_id) {
                        try {
                            $paymentIntent = \Stripe\PaymentIntent::retrieve($order->stripe_payment_intent_id);
                            // コンビニ決済の場合、next_actionから支払い情報URLを取得
                            if (isset($paymentIntent->next_action->display_details->hosted_voucher_url)) {
                                $paymentUrl = $paymentIntent->next_action->display_details->hosted_voucher_url;
                            } elseif (isset($paymentIntent->next_action->konbini_display_details->hosted_voucher_url)) {
                                $paymentUrl = $paymentIntent->next_action->konbini_display_details->hosted_voucher_url;
                            }
                        } catch (\Exception $e) {
                            Log::warning('Failed to retrieve PaymentIntent for konbini payment', [
                                'order_id' => $order->id,
                                'payment_intent_id' => $order->stripe_payment_intent_id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                    
                    // PaymentIntentから取得できなかった場合、Checkout Sessionから取得
                    if (!$paymentUrl) {
                        if (isset($checkoutSession->hosted_invoice_url)) {
                            $paymentUrl = $checkoutSession->hosted_invoice_url;
                        } elseif (isset($checkoutSession->url)) {
                            // hosted_invoice_urlがない場合は、Checkout URLを使用
                            $paymentUrl = $checkoutSession->url;
                        }
                    }
                    
                    // 支払い方法の種類を判定
                    if (in_array('konbini', $paymentMethodTypes)) {
                        $paymentMethodType = 'konbini';
                    } elseif (in_array('customer_balance', $paymentMethodTypes)) {
                        $paymentMethodType = 'bank_transfer';
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Failed to retrieve Stripe Checkout Session', [
                    'order_id' => $order->id,
                    'session_id' => $order->stripe_checkout_session_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return view('mypage.orders.show', compact('order', 'paymentUrl', 'paymentMethodType'));
    }
}

