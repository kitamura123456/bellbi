<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard')->with('status', '会社情報が登録されていません。');
        }

        $shops = $company->shops()->where('delete_flg', 0)->get();

        return view('company.shops.index', compact('company', 'shops'));
    }

    public function create()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $stores = $company->stores()->where('delete_flg', 0)->get();

        return view('company.shops.create', compact('company', 'stores'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'store_id' => ['nullable', 'exists:stores,id'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'integer', 'in:0,1,9'],
        ]);

        // store_idが指定されている場合、その店舗が自社のものか確認
        if ($validated['store_id']) {
            $store = $company->stores()->find($validated['store_id']);
            if (!$store) {
                return redirect()->back()->withErrors(['store_id' => '選択された店舗が見つかりません。'])->withInput();
            }
        }

        $data = [
            'company_id' => $company->id,
            'store_id' => $validated['store_id'] ?? null,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'delete_flg' => 0,
        ];

        $shop = Shop::create($data);

        return redirect()->route('company.shops.index')->with('status', 'ショップを開設しました。');
    }

    public function edit(Shop $shop)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $shop->company_id !== $company->id) {
            abort(403);
        }

        $stores = $company->stores()->where('delete_flg', 0)->get();

        return view('company.shops.edit', compact('company', 'shop', 'stores'));
    }

    public function update(Request $request, Shop $shop)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $shop->company_id !== $company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'store_id' => ['nullable', 'exists:stores,id'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'integer', 'in:0,1,9'],
        ]);

        // store_idが指定されている場合、その店舗が自社のものか確認
        if ($validated['store_id']) {
            $store = $company->stores()->find($validated['store_id']);
            if (!$store) {
                return redirect()->back()->withErrors(['store_id' => '選択された店舗が見つかりません。'])->withInput();
            }
        }

        $data = [
            'store_id' => $validated['store_id'] ?? null,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ];

        $shop->update($data);

        return redirect()->route('company.shops.index')->with('status', 'ショップ情報を更新しました。');
    }

    public function destroy(Shop $shop)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $shop->company_id !== $company->id) {
            abort(403);
        }

        $shop->delete_flg = 1;
        $shop->save();

        return redirect()->route('company.shops.index')->with('status', 'ショップを削除しました。');
    }
}

