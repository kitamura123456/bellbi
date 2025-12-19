<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * 商品一覧ページ（マーケットプレイス形式）
     * 全ショップの商品を表示（在庫なしも含む）
     */
    public function index(Request $request)
    {
        $query = Product::where('delete_flg', 0)
            ->whereIn('status', [Product::STATUS_ON_SALE, Product::STATUS_OUT_OF_STOCK])
            ->with(['shop.company'])
            ->withCount(['orderItems as order_count' => function($q) {
                $q->whereHas('order', function($orderQuery) {
                    $orderQuery->where('delete_flg', 0);
                });
            }])
            ->whereHas('shop', function($q) {
                $q->where('status', Shop::STATUS_PUBLIC)
                  ->where('delete_flg', 0);
            });

        // キーワード検索
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // カテゴリ検索
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // 並び替え
        $sort = $request->input('sort', 'random');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('order_count', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'stock_available':
                $query->orderByRaw('CASE WHEN stock > 0 THEN 0 ELSE 1 END')->orderBy('price', 'asc');
                break;
            case 'stock_unavailable':
                $query->orderByRaw('CASE WHEN stock <= 0 THEN 0 ELSE 1 END')->orderBy('price', 'asc');
                break;
            default:
                // ランダムに並び替え（マーケットプレイス形式）
                $query->inRandomOrder();
                break;
        }

        $products = $query->paginate(20);

        return view('shops.index', compact('products'));
    }

    /**
     * 商品詳細ページ
     */
    public function show(Product $product)
    {
        if ($product->delete_flg || !in_array($product->status, [Product::STATUS_ON_SALE, Product::STATUS_OUT_OF_STOCK])) {
            abort(404);
        }

        $product->load(['shop.company']);

        if (!$product->shop || $product->shop->status !== Shop::STATUS_PUBLIC || $product->shop->delete_flg) {
            abort(404);
        }

        return view('shops.show', compact('product'));
    }
}

