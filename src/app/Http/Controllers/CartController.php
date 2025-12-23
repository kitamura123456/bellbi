<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * カート一覧表示
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $items = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::with('shop.company')
                ->where('id', $productId)
                ->where('delete_flg', 0)
                ->whereIn('status', [Product::STATUS_ON_SALE, Product::STATUS_OUT_OF_STOCK])
                ->first();

            if ($product && $product->shop && $product->shop->status === Shop::STATUS_PUBLIC && !$product->shop->delete_flg) {
                // 在庫数を考慮して数量を調整
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

        return view('cart.index', compact('items', 'total'));
    }

    /**
     * カートに商品を追加
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $product = Product::with('shop')
            ->where('id', $validated['product_id'])
            ->where('delete_flg', 0)
            ->whereIn('status', [Product::STATUS_ON_SALE, Product::STATUS_OUT_OF_STOCK])
            ->first();

        if (!$product) {
            return redirect()->back()->with('error', '商品が見つかりません。');
        }

        if (!$product->shop || $product->shop->status !== Shop::STATUS_PUBLIC || $product->shop->delete_flg) {
            return redirect()->back()->with('error', 'この商品は現在購入できません。');
        }

        if ($product->stock < $validated['quantity']) {
            return redirect()->back()->with('error', '在庫が不足しています。');
        }

        $cart = Session::get('cart', []);
        $currentQuantity = $cart[$validated['product_id']] ?? 0;
        $newQuantity = $currentQuantity + $validated['quantity'];

        if ($newQuantity > $product->stock) {
            return redirect()->back()->with('error', '在庫数を超える数量は追加できません。');
        }

        $cart[$validated['product_id']] = $newQuantity;
        Session::put('cart', $cart);

        return redirect()->route('cart.index')->with('status', 'カートに追加しました。');
    }

    /**
     * カート内の商品数量を更新
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:999'],
        ]);

        $product->load('shop');

        if ($product->delete_flg || !in_array($product->status, [Product::STATUS_ON_SALE, Product::STATUS_OUT_OF_STOCK])) {
            return redirect()->back()->with('error', '商品が見つかりません。');
        }

        if (!$product->shop || $product->shop->status !== Shop::STATUS_PUBLIC || $product->shop->delete_flg) {
            return redirect()->back()->with('error', 'この商品は現在購入できません。');
        }

        $cart = Session::get('cart', []);

        if ($validated['quantity'] == 0) {
            unset($cart[$product->id]);
        } else {
            if ($validated['quantity'] > $product->stock) {
                return redirect()->back()->with('error', '在庫数を超える数量は設定できません。');
            }
            $cart[$product->id] = $validated['quantity'];
        }

        Session::put('cart', $cart);

        return redirect()->route('cart.index')->with('status', 'カートを更新しました。');
    }

    /**
     * カートから商品を削除
     */
    public function remove(Product $product)
    {
        $cart = Session::get('cart', []);
        unset($cart[$product->id]);
        Session::put('cart', $cart);

        return redirect()->route('cart.index')->with('status', 'カートから削除しました。');
    }

    /**
     * カートをクリア
     */
    public function clear()
    {
        Session::forget('cart');

        return redirect()->route('cart.index')->with('status', 'カートを空にしました。');
    }
}

