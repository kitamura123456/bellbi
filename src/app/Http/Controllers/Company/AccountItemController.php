<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\AccountItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountItemController extends Controller
{
    /**
     * 科目一覧画面
     */
    public function index()
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        $revenueItems = $company->accountItems()
            ->where('type', AccountItem::TYPE_REVENUE)
            ->where('delete_flg', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        $expenseItems = $company->accountItems()
            ->where('type', AccountItem::TYPE_EXPENSE)
            ->where('delete_flg', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('company.account-items.index', compact('revenueItems', 'expenseItems'));
    }

    /**
     * 科目作成画面
     */
    public function create()
    {
        return view('company.account-items.create');
    }

    /**
     * 科目作成処理
     */
    public function store(Request $request)
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        $validated = $request->validate([
            'type' => 'required|integer|in:1,2',
            'name' => 'required|string|max:255',
            'default_tax_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        AccountItem::create([
            'company_id' => $company->id,
            'type' => $validated['type'],
            'name' => $validated['name'],
            'default_tax_rate' => $validated['default_tax_rate'] ?? null,
            'delete_flg' => 0,
        ]);

        return redirect()->route('company.account-items.index')
            ->with('success', '科目を作成しました。');
    }

    /**
     * 科目編集画面
     */
    public function edit($id)
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        $accountItem = AccountItem::where('id', $id)
            ->where('company_id', $company->id)
            ->where('delete_flg', 0)
            ->firstOrFail();

        return view('company.account-items.edit', compact('accountItem'));
    }

    /**
     * 科目更新処理
     */
    public function update(Request $request, $id)
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        $accountItem = AccountItem::where('id', $id)
            ->where('company_id', $company->id)
            ->where('delete_flg', 0)
            ->firstOrFail();

        $validated = $request->validate([
            'type' => 'required|integer|in:1,2',
            'name' => 'required|string|max:255',
            'default_tax_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $accountItem->update([
            'type' => $validated['type'],
            'name' => $validated['name'],
            'default_tax_rate' => $validated['default_tax_rate'] ?? null,
        ]);

        return redirect()->route('company.account-items.index')
            ->with('success', '科目を更新しました。');
    }

    /**
     * 科目削除処理（論理削除）
     */
    public function destroy($id)
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        $accountItem = AccountItem::where('id', $id)
            ->where('company_id', $company->id)
            ->where('delete_flg', 0)
            ->firstOrFail();

        $accountItem->update(['delete_flg' => 1]);

        return redirect()->route('company.account-items.index')
            ->with('success', '科目を削除しました。');
    }
}


