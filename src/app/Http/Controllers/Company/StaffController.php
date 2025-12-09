<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $stores = $company->stores()->where('delete_flg', 0)->with(['staffs' => function($query) {
            $query->where('delete_flg', 0)->orderBy('display_order');
        }])->get();

        return view('company.staffs.index', compact('company', 'stores'));
    }

    public function create()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $stores = $company->stores()->where('delete_flg', 0)->get();

        return view('company.staffs.create', compact('company', 'stores'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $validated = $request->validate([
            'store_id' => ['required', 'exists:stores,id'],
            'name' => ['required', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'integer', 'in:0,1'],
        ]);

        $store = Store::where('id', $validated['store_id'])
            ->where('company_id', $company->id)
            ->firstOrFail();

        StoreStaff::create(array_merge($validated, ['delete_flg' => 0]));

        return redirect()->route('company.staffs.index')->with('status', 'スタッフを登録しました。');
    }

    public function edit(StoreStaff $staff)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $staff->store->company_id !== $company->id) {
            abort(403);
        }

        $stores = $company->stores()->where('delete_flg', 0)->get();

        return view('company.staffs.edit', compact('company', 'staff', 'stores'));
    }

    public function update(Request $request, StoreStaff $staff)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $staff->store->company_id !== $company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'store_id' => ['required', 'exists:stores,id'],
            'name' => ['required', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'integer', 'in:0,1'],
        ]);

        $store = Store::where('id', $validated['store_id'])
            ->where('company_id', $company->id)
            ->firstOrFail();

        $staff->update($validated);

        return redirect()->route('company.staffs.index')->with('status', 'スタッフ情報を更新しました。');
    }

    public function destroy(StoreStaff $staff)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $staff->store->company_id !== $company->id) {
            abort(403);
        }

        $staff->delete_flg = 1;
        $staff->save();

        return redirect()->route('company.staffs.index')->with('status', 'スタッフを削除しました。');
    }
}

