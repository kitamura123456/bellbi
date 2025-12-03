<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard')->with('status', '会社情報が登録されていません。');
        }

        $stores = $company->stores()->where('delete_flg', 0)->get();

        return view('company.stores.index', compact('company', 'stores'));
    }

    public function create()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        return view('company.stores.create', compact('company'));
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
            'store_type' => ['required', 'integer'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'tel' => ['nullable', 'string', 'max:50'],
        ]);

        $company->stores()->create([
            'name' => $validated['name'],
            'store_type' => $validated['store_type'],
            'postal_code' => $validated['postal_code'],
            'address' => $validated['address'],
            'tel' => $validated['tel'],
            'delete_flg' => 0,
        ]);

        return redirect()->route('company.stores.index')->with('status', '店舗を追加しました。');
    }

    public function edit(Store $store)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $store->company_id !== $company->id) {
            abort(403);
        }

        return view('company.stores.edit', compact('company', 'store'));
    }

    public function update(Request $request, Store $store)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $store->company_id !== $company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'store_type' => ['required', 'integer'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'tel' => ['nullable', 'string', 'max:50'],
        ]);

        $store->update($validated);

        return redirect()->route('company.stores.index')->with('status', '店舗情報を更新しました。');
    }

    public function destroy(Store $store)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $store->company_id !== $company->id) {
            abort(403);
        }

        $store->delete_flg = 1;
        $store->save();

        return redirect()->route('company.stores.index')->with('status', '店舗を削除しました。');
    }
}

