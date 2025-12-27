<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Carbon\Carbon;

class StripeWebhookController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Stripe Webhookを処理
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        Log::info('Stripe Webhook Received', [
            'has_payload' => !empty($payload),
            'payload_length' => strlen($payload),
            'has_signature' => !empty($sigHeader),
            'signature_preview' => $sigHeader ? substr($sigHeader, 0, 50) . '...' : null,
            'webhook_secret_set' => !empty($webhookSecret),
            'webhook_secret_preview' => $webhookSecret ? substr($webhookSecret, 0, 20) . '...' : null,
            'environment' => config('app.env'),
        ]);

        // Webhook Secretが設定されていない場合
        if (!$webhookSecret) {
            if (config('app.env') === 'production') {
                // 本番環境では必須
                Log::error('Stripe Webhook Secretが設定されていません（本番環境）');
                return response()->json(['error' => 'Webhook secret not configured'], 500);
            } else {
                // 開発環境では警告のみ（Stripe CLIを使用している場合など）
                Log::warning('Stripe Webhook Secretが設定されていません（開発環境）。本番環境では必ず設定してください。');
                $event = json_decode($payload, false);
                if (!$event || !isset($event->type)) {
                    Log::error('Stripe Webhook: Invalid payload structure');
                    return response()->json(['error' => 'Invalid payload'], 400);
                }
                $this->handleEvent($event);
                return response()->json(['received' => true, 'warning' => 'Signature verification skipped (development mode)']);
            }
        }

        // 署名検証を実行（開発環境・本番環境ともに）
        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
            Log::info('Stripe Webhook: Signature verified successfully');
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe Webhook: Invalid payload', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            // 開発環境では警告のみ、本番環境ではエラー
            if (config('app.env') === 'production') {
                Log::error('Stripe Webhook: Invalid signature (production)', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Invalid signature'], 400);
            } else {
                // 開発環境では警告のみ（Stripe CLIの署名が異なる場合など）
                Log::warning('Stripe Webhook: Signature verification failed (development mode)', ['error' => $e->getMessage()]);
                $event = json_decode($payload, false);
                if (!$event || !isset($event->type)) {
                    Log::error('Stripe Webhook: Invalid payload structure');
                    return response()->json(['error' => 'Invalid payload'], 400);
                }
                $this->handleEvent($event);
                return response()->json(['received' => true, 'warning' => 'Signature verification failed but processed (development mode)']);
            }
        }

        // イベントタイプに応じて処理
        $this->handleEvent($event);

        return response()->json(['received' => true]);
    }

    /**
     * イベントを処理（検証済み）
     */
    private function handleEvent($event)
    {
        try {
            Log::info('Stripe Webhook: Processing event', ['type' => $event->type]);
            
            switch ($event->type) {
                case 'checkout.session.completed':
                    $this->handleCheckoutSessionCompleted($event->data->object);
                    break;

                case 'checkout.session.async_payment_succeeded':
                    // 非同期決済（コンビニ決済など）が成功した場合
                    $this->handleCheckoutSessionAsyncPaymentSucceeded($event->data->object);
                    break;

                case 'checkout.session.async_payment_failed':
                    // 非同期決済が失敗した場合
                    $this->handleCheckoutSessionAsyncPaymentFailed($event->data->object);
                    break;

                case 'payment_intent.succeeded':
                    $this->handlePaymentIntentSucceeded($event->data->object);
                    break;

                case 'customer.subscription.created':
                case 'customer.subscription.updated':
                    $this->handleSubscriptionUpdated($event->data->object);
                    break;

                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event->data->object);
                    break;

                case 'invoice.payment_succeeded':
                    $this->handleInvoicePaymentSucceeded($event->data->object);
                    break;

                case 'invoice.payment_failed':
                    $this->handleInvoicePaymentFailed($event->data->object);
                    break;

                default:
                    Log::info('Stripe Webhook: Unhandled event type', ['type' => $event->type]);
            }
            
            Log::info('Stripe Webhook: Event processed successfully', ['type' => $event->type]);
        } catch (\Exception $e) {
            Log::error('Stripe Webhook: Error processing event', [
                'type' => $event->type ?? 'unknown',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }

    /**
     * イベントを処理（検証なし - 開発環境のみ）
     */
    private function handleEventWithoutVerification($event)
    {
        $eventObj = (object) [
            'type' => $event['type'],
            'data' => (object) [
                'object' => (object) ($event['data']['object'] ?? [])
            ]
        ];
        $this->handleEvent($eventObj);
    }

    /**
     * Checkoutセッション完了時の処理
     */
    private function handleCheckoutSessionCompleted($session)
    {
        try {
            Log::info('=== CHECKPOINT 1: handleCheckoutSessionCompleted called ===', [
                'session_id' => $session->id ?? 'N/A',
            ]);
            
            Log::info('Stripe Checkout Session Completed', [
                'session_id' => $session->id,
                'payment_status' => $session->payment_status,
                'payment_method_types' => $session->payment_method_types ?? [],
            ]);
            
            // 既に注文が存在するかチェック
            $order = Order::where('stripe_checkout_session_id', $session->id)->first();
            
            if ($order) {
                // 注文が既に存在する場合、ステータスを更新
                $paymentMethodTypes = $session->payment_method_types ?? [];
                $isAsyncPayment = in_array('konbini', $paymentMethodTypes) || in_array('customer_balance', $paymentMethodTypes);
                
                // 非同期決済でpayment_statusが'unpaid'の場合は入金待ち
                if ($session->payment_status === 'unpaid' && $isAsyncPayment) {
                    $order->status = Order::STATUS_NEW; // 入金待ち
                    $order->save();
                    Log::info('Order status set to unpaid (async payment)', ['order_id' => $order->id]);
                } elseif ($session->payment_status === 'paid') {
                    $order->status = Order::STATUS_PAID; // 入金確認済み
                    $order->save();
                    Log::info('Order status set to paid', ['order_id' => $order->id]);
                }
            } else {
                // 注文が存在しない場合（stripeSuccessが呼ばれていない可能性）
                // metadataからセッションデータを取得して注文を作成
                $userId = $session->metadata->user_id ?? null;
                $shopItemsJson = $session->metadata->shop_items ?? null;
                
                if ($userId && $shopItemsJson) {
                    try {
                        $shopItems = json_decode($shopItemsJson, true);
                        if ($shopItems && is_array($shopItems)) {
                            $this->createOrderFromWebhook($session, $userId, $shopItems);
                        } else {
                            Log::warning('Invalid shop_items in metadata', [
                                'session_id' => $session->id,
                                'user_id' => $userId,
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to create order from webhook', [
                            'session_id' => $session->id,
                            'user_id' => $userId,
                            'error' => $e->getMessage(),
                        ]);
                    }
                } else {
                    Log::warning('Order not found and cannot create from webhook (missing data)', [
                        'session_id' => $session->id,
                        'user_id' => $userId,
                        'has_shop_items' => !empty($shopItemsJson),
                    ]);
                }
            }
            
            Log::info('=== CHECKPOINT 2: handleCheckoutSessionCompleted completed successfully ===');
        } catch (\Exception $e) {
            Log::error('=== ERROR in handleCheckoutSessionCompleted ===', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Webhookから注文を作成
     */
    private function createOrderFromWebhook($session, $userId, $shopItems)
    {
        $paymentMethodTypes = $session->payment_method_types ?? [];
        $isAsyncPayment = in_array('konbini', $paymentMethodTypes) || in_array('customer_balance', $paymentMethodTypes);
        
        // 非同期決済の場合は入金待ち、即時決済の場合は入金確認済み
        $orderStatus = ($session->payment_status === 'paid') 
            ? Order::STATUS_PAID 
            : Order::STATUS_NEW;
        
        DB::beginTransaction();
        try {
            $orders = [];
            
            // ショップごとに注文を作成
            foreach ($shopItems as $shopId => $shopItemList) {
                $shopTotal = array_sum(array_column($shopItemList, 'subtotal'));
                
                $order = Order::create([
                    'shop_id' => $shopId,
                    'user_id' => $userId,
                    'total_amount' => $shopTotal,
                    'status' => $orderStatus,
                    'stripe_checkout_session_id' => $session->id,
                    'stripe_payment_intent_id' => $session->payment_intent,
                    'delete_flg' => 0,
                ]);
                
                // 注文明細を作成
                foreach ($shopItemList as $item) {
                    // 商品IDを取得（productオブジェクトまたはproduct_id）
                    $productId = null;
                    if (isset($item['product']['id'])) {
                        $productId = $item['product']['id'];
                    } elseif (isset($item['product_id'])) {
                        $productId = $item['product_id'];
                    } elseif (isset($item['product']) && is_numeric($item['product'])) {
                        $productId = $item['product'];
                    }
                    
                    $quantity = $item['quantity'] ?? 1;
                    $unitPrice = $item['product']['price'] ?? $item['unit_price'] ?? $item['subtotal'] / $quantity;
                    
                    if (!$productId) {
                        Log::warning('Product ID not found in shop item', [
                            'item' => $item,
                        ]);
                        continue;
                    }
                    
                    $product = Product::lockForUpdate()->find($productId);
                    if (!$product) {
                        Log::warning('Product not found', ['product_id' => $productId]);
                        continue;
                    }
                    
                    if ($product->stock < $quantity) {
                        Log::warning('Insufficient stock', [
                            'product_id' => $productId,
                            'required' => $quantity,
                            'available' => $product->stock,
                        ]);
                        // 在庫不足でも注文は作成する（後で対応）
                    }
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'delete_flg' => 0,
                    ]);
                    
                    // 在庫を減らす
                    $product->stock -= $quantity;
                    if ($product->stock <= 0) {
                        $product->status = Product::STATUS_OUT_OF_STOCK;
                    }
                    $product->save();
                }
                
                $orders[] = $order;
            }
            
            DB::commit();
            
            Log::info('Order created from webhook', [
                'session_id' => $session->id,
                'user_id' => $userId,
                'order_count' => count($orders),
                'order_ids' => array_column($orders, 'id'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create order from webhook', [
                'session_id' => $session->id,
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * 非同期決済成功時の処理（コンビニ決済など）
     */
    private function handleCheckoutSessionAsyncPaymentSucceeded($session)
    {
        $order = Order::where('stripe_checkout_session_id', $session->id)->first();
        if ($order && $order->status === Order::STATUS_NEW) {
            $order->status = Order::STATUS_PAID;
            $order->save();
            Log::info('Async payment succeeded for order', ['order_id' => $order->id]);
        }
    }

    /**
     * 非同期決済失敗時の処理
     */
    private function handleCheckoutSessionAsyncPaymentFailed($session)
    {
        $order = Order::where('stripe_checkout_session_id', $session->id)->first();
        if ($order) {
            // 決済失敗の場合はキャンセル扱い
            $order->status = Order::STATUS_CANCELLED;
            $order->save();
            Log::warning('Async payment failed for order', ['order_id' => $order->id]);
        }
    }

    /**
     * Payment Intent成功時の処理
     */
    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        // 注文のステータスを更新
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        if ($order && $order->status === Order::STATUS_NEW) {
            $order->status = Order::STATUS_PAID;
            $order->save();
            Log::info('Order payment confirmed', ['order_id' => $order->id]);
        }
    }

    /**
     * サブスクリプション更新時の処理
     */
    private function handleSubscriptionUpdated($subscription)
    {
        $stripeSubscriptionId = $subscription->id;
        $subscriptionModel = Subscription::where('stripe_subscription_id', $stripeSubscriptionId)->first();

        if ($subscriptionModel) {
            $subscriptionModel->status = $this->mapStripeStatusToLocalStatus($subscription->status);
            $subscriptionModel->started_at = Carbon::createFromTimestamp($subscription->current_period_start);
            $subscriptionModel->next_billing_at = Carbon::createFromTimestamp($subscription->current_period_end);
            $subscriptionModel->save();

            // Companyテーブルも更新
            $company = $subscriptionModel->company;
            if ($company) {
                $company->plan_status = $subscriptionModel->status;
                $company->save();
            }

            Log::info('Subscription updated', ['subscription_id' => $subscriptionModel->id]);
        }
    }

    /**
     * サブスクリプション削除時の処理
     */
    private function handleSubscriptionDeleted($subscription)
    {
        $stripeSubscriptionId = $subscription->id;
        $subscriptionModel = Subscription::where('stripe_subscription_id', $stripeSubscriptionId)->first();

        if ($subscriptionModel) {
            $subscriptionModel->status = Subscription::STATUS_CANCELLED;
            $subscriptionModel->ended_at = Carbon::now();
            $subscriptionModel->save();

            // Companyテーブルも更新
            $company = $subscriptionModel->company;
            if ($company) {
                $company->plan_status = Subscription::STATUS_CANCELLED;
                $company->save();
            }

            Log::info('Subscription cancelled', ['subscription_id' => $subscriptionModel->id]);
        }
    }

    /**
     * インボイス支払い成功時の処理
     */
    private function handleInvoicePaymentSucceeded($invoice)
    {
        if ($invoice->subscription) {
            $subscriptionModel = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();
            if ($subscriptionModel) {
                // 次の請求日を更新
                $subscriptionModel->next_billing_at = Carbon::createFromTimestamp($invoice->period_end);
                $subscriptionModel->status = Subscription::STATUS_ACTIVE;
                $subscriptionModel->save();

                Log::info('Invoice payment succeeded', ['subscription_id' => $subscriptionModel->id]);
            }
        }
    }

    /**
     * インボイス支払い失敗時の処理
     */
    private function handleInvoicePaymentFailed($invoice)
    {
        if ($invoice->subscription) {
            $subscriptionModel = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();
            if ($subscriptionModel) {
                $subscriptionModel->status = Subscription::STATUS_PAYMENT_DELAYED;
                $subscriptionModel->save();

                // Companyテーブルも更新
                $company = $subscriptionModel->company;
                if ($company) {
                    $company->plan_status = Subscription::STATUS_PAYMENT_DELAYED;
                    $company->save();
                }

                Log::warning('Invoice payment failed', ['subscription_id' => $subscriptionModel->id]);
            }
        }
    }

    /**
     * Stripeのステータスをローカルのステータスにマッピング
     */
    private function mapStripeStatusToLocalStatus($stripeStatus)
    {
        switch ($stripeStatus) {
            case 'active':
                return Subscription::STATUS_ACTIVE;
            case 'trialing':
                return Subscription::STATUS_TRIAL;
            case 'past_due':
            case 'unpaid':
                return Subscription::STATUS_PAYMENT_DELAYED;
            case 'canceled':
            case 'incomplete_expired':
                return Subscription::STATUS_CANCELLED;
            default:
                return Subscription::STATUS_ACTIVE;
        }
    }
}
