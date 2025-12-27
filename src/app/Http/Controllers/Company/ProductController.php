<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard')->with('status', '会社情報が登録されていません。');
        }

        $shopId = $request->input('shop_id');
        $shops = $company->shops()->where('delete_flg', 0)->get();

        $query = Product::where('delete_flg', 0)
            ->whereHas('shop', function($q) use ($company) {
                $q->where('company_id', $company->id)
                  ->where('delete_flg', 0);
            });

        if ($shopId) {
            $query->where('shop_id', $shopId);
        }

        $products = $query->with('shop')->orderBy('created_at', 'desc')->paginate(20);

        return view('company.products.index', compact('company', 'products', 'shops', 'shopId'));
    }

    public function create()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $shops = $company->shops()->where('delete_flg', 0)->get();

        if ($shops->isEmpty()) {
            return redirect()->route('company.shops.index')->with('status', 'まずショップを開設してください。');
        }

        return view('company.products.create', compact('company', 'shops'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $validated = $request->validate([
            'shop_id' => ['required', 'exists:shops,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'category' => ['nullable', 'integer'],
            'status' => ['required', 'integer', 'in:1,2,9'],
        ]);

        // ショップが自社のものか確認
        $shop = $company->shops()->find($validated['shop_id']);
        if (!$shop) {
            return redirect()->back()->withErrors(['shop_id' => '選択されたショップが見つかりません。'])->withInput();
        }

        // 在庫数に応じてステータスを自動設定
        $status = $validated['status'];
        if ($validated['stock'] <= 0) {
            // 在庫が0以下の場合は在庫切れステータスに
            $status = Product::STATUS_OUT_OF_STOCK;
        } elseif ($validated['stock'] > 0 && $status === Product::STATUS_OUT_OF_STOCK) {
            // 在庫が0より大きいのに在庫切れステータスの場合は販売中に戻す
            $status = Product::STATUS_ON_SALE;
        }

        $data = [
            'shop_id' => $validated['shop_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'category' => $validated['category'] ?? null,
            'status' => $status,
            'delete_flg' => 0,
        ];

        Product::create($data);

        return redirect()->route('company.products.index')->with('status', '商品を登録しました。');
    }

    public function edit(Product $product)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        // 商品が自社のショップのものか確認
        $shop = $company->shops()->find($product->shop_id);
        if (!$shop) {
            abort(403);
        }

        $shops = $company->shops()->where('delete_flg', 0)->get();

        return view('company.products.edit', compact('company', 'product', 'shops'));
    }

    public function update(Request $request, Product $product)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        // 商品が自社のショップのものか確認
        $shop = $company->shops()->find($product->shop_id);
        if (!$shop) {
            abort(403);
        }

        $validated = $request->validate([
            'shop_id' => ['required', 'exists:shops,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'category' => ['nullable', 'integer'],
            'status' => ['required', 'integer', 'in:1,2,9'],
        ]);

        // 新しいショップが自社のものか確認
        $newShop = $company->shops()->find($validated['shop_id']);
        if (!$newShop) {
            return redirect()->back()->withErrors(['shop_id' => '選択されたショップが見つかりません。'])->withInput();
        }

        // 在庫数に応じてステータスを自動設定
        $status = $validated['status'];
        if ($validated['stock'] <= 0) {
            // 在庫が0以下の場合は在庫切れステータスに
            $status = Product::STATUS_OUT_OF_STOCK;
        } elseif ($validated['stock'] > 0) {
            // 在庫が0より大きい場合は、ステータスが在庫切れまたは非公開でない限り販売中にする
            if ($status === Product::STATUS_OUT_OF_STOCK) {
                // 在庫切れから在庫が復活した場合は販売中に戻す
                $status = Product::STATUS_ON_SALE;
            } elseif ($status === Product::STATUS_PRIVATE) {
                // 非公開の場合はそのまま
                $status = Product::STATUS_PRIVATE;
            } else {
                // その他の場合は販売中
                $status = Product::STATUS_ON_SALE;
            }
        }

        $data = [
            'shop_id' => $validated['shop_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'category' => $validated['category'] ?? null,
            'status' => $status,
        ];

        // 在庫とステータスを確実に更新
        $product->stock = $validated['stock'];
        $product->status = $status;
        $product->shop_id = $validated['shop_id'];
        $product->name = $validated['name'];
        $product->description = $validated['description'] ?? null;
        $product->price = $validated['price'];
        $product->category = $validated['category'] ?? null;
        $product->save();

        return redirect()->route('company.products.index')->with('status', '商品情報を更新しました。');
    }

    public function destroy(Product $product)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        // 商品が自社のショップのものか確認
        $shop = $company->shops()->find($product->shop_id);
        if (!$shop) {
            abort(403);
        }

        $product->delete_flg = 1;
        $product->save();

        return redirect()->route('company.products.index')->with('status', '商品を削除しました。');
    }
}

