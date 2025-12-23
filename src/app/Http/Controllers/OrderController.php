<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
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

        return view('orders.checkout', compact('items', 'total', 'user'));
    }

    /**
     * 注文確定処理
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

        $orders = Order::where('user_id', Auth::id())
            ->where('delete_flg', 0)
            ->with(['shop.company', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('mypage.orders.index', compact('orders'));
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

        return view('mypage.orders.show', compact('order'));
    }
}

