<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\AccountItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * 取引一覧画面
     */
    public function index(Request $request)
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        // フィルター条件
        $storeId = $request->input('store_id');
        $transactionType = $request->input('transaction_type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = $company->transactions()
            ->with(['store', 'accountItem'])
            ->where('delete_flg', 0)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        if ($transactionType) {
            $query->where('transaction_type', $transactionType);
        }

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $transactions = $query->paginate(20);
        $stores = $company->stores()->where('delete_flg', 0)->get();

        return view('company.transactions.index', compact('transactions', 'stores'));
    }

    /**
     * 取引作成画面
     */
    public function create(Request $request)
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        $stores = $company->stores()->where('delete_flg', 0)->get();
        $revenueItems = $company->accountItems()
            ->where('type', AccountItem::TYPE_REVENUE)
            ->where('delete_flg', 0)
            ->get();
        $expenseItems = $company->accountItems()
            ->where('type', AccountItem::TYPE_EXPENSE)
            ->where('delete_flg', 0)
            ->get();

        $transactionType = $request->input('type', 1); // デフォルトは売上

        return view('company.transactions.create', compact(
            'stores',
            'revenueItems',
            'expenseItems',
            'transactionType'
        ));
    }

    /**
     * 取引作成処理
     */
    public function store(Request $request)
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'date' => 'required|date',
            'transaction_type' => 'required|integer|in:1,2',
            'account_item_id' => 'required|exists:account_items,id',
            'amount' => 'required|integer|min:0',
            'tax_amount' => 'nullable|integer|min:0',
            'note' => 'nullable|string|max:1000',
        ]);

        // 店舗が自社のものか確認
        $store = $company->stores()->where('id', $validated['store_id'])->first();
        if (!$store) {
            return back()->withErrors(['store_id' => '指定された店舗が見つかりません。'])->withInput();
        }

        // 科目が自社のものか確認
        $accountItem = $company->accountItems()->where('id', $validated['account_item_id'])->first();
        if (!$accountItem) {
            return back()->withErrors(['account_item_id' => '指定された科目が見つかりません。'])->withInput();
        }

        Transaction::create([
            'company_id' => $company->id,
            'store_id' => $validated['store_id'],
            'date' => $validated['date'],
            'account_item_id' => $validated['account_item_id'],
            'amount' => $validated['amount'],
            'tax_amount' => $validated['tax_amount'] ?? 0,
            'transaction_type' => $validated['transaction_type'],
            'source_type' => Transaction::SOURCE_MANUAL,
            'note' => $validated['note'],
            'delete_flg' => 0,
        ]);

        return redirect()->route('company.transactions.index')
            ->with('success', '取引を登録しました。');
    }

    /**
     * 取引編集画面
     */
    public function edit($id)
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        $transaction = Transaction::where('id', $id)
            ->where('company_id', $company->id)
            ->where('delete_flg', 0)
            ->firstOrFail();

        $stores = $company->stores()->where('delete_flg', 0)->get();
        $revenueItems = $company->accountItems()
            ->where('type', AccountItem::TYPE_REVENUE)
            ->where('delete_flg', 0)
            ->get();
        $expenseItems = $company->accountItems()
            ->where('type', AccountItem::TYPE_EXPENSE)
            ->where('delete_flg', 0)
            ->get();

        return view('company.transactions.edit', compact(
            'transaction',
            'stores',
            'revenueItems',
            'expenseItems'
        ));
    }

    /**
     * 取引更新処理
     */
    public function update(Request $request, $id)
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        $transaction = Transaction::where('id', $id)
            ->where('company_id', $company->id)
            ->where('delete_flg', 0)
            ->firstOrFail();

        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'date' => 'required|date',
            'transaction_type' => 'required|integer|in:1,2',
            'account_item_id' => 'required|exists:account_items,id',
            'amount' => 'required|integer|min:0',
            'tax_amount' => 'nullable|integer|min:0',
            'note' => 'nullable|string|max:1000',
        ]);

        $transaction->update([
            'store_id' => $validated['store_id'],
            'date' => $validated['date'],
            'account_item_id' => $validated['account_item_id'],
            'amount' => $validated['amount'],
            'tax_amount' => $validated['tax_amount'] ?? 0,
            'transaction_type' => $validated['transaction_type'],
            'note' => $validated['note'],
        ]);

        return redirect()->route('company.transactions.index')
            ->with('success', '取引を更新しました。');
    }

    /**
     * 取引削除処理（論理削除）
     */
    public function destroy($id)
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        $transaction = Transaction::where('id', $id)
            ->where('company_id', $company->id)
            ->where('delete_flg', 0)
            ->firstOrFail();

        $transaction->update(['delete_flg' => 1]);

        return redirect()->route('company.transactions.index')
            ->with('success', '取引を削除しました。');
    }

    /**
     * 月次レポート画面
     */
    public function report(Request $request)
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        // 対象年月（デフォルトは当月）
        $targetMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::parse($targetMonth . '-01')->startOfMonth();
        $endDate = Carbon::parse($targetMonth . '-01')->endOfMonth();

        // 店舗フィルター
        $storeId = $request->input('store_id');

        $query = $company->transactions()
            ->where('delete_flg', 0)
            ->betweenDates($startDate, $endDate);

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        // 売上合計
        $totalRevenue = (clone $query)->revenue()->sum('amount');
        $totalRevenueTax = (clone $query)->revenue()->sum('tax_amount');

        // 経費合計
        $totalExpense = (clone $query)->expense()->sum('amount');
        $totalExpenseTax = (clone $query)->expense()->sum('tax_amount');

        // 科目別集計
        $revenueByItem = (clone $query)->revenue()
            ->selectRaw('account_item_id, CAST(SUM(amount) AS INTEGER) as total_amount, CAST(SUM(tax_amount) AS INTEGER) as total_tax')
            ->groupBy('account_item_id')
            ->with('accountItem')
            ->get();

        $expenseByItem = (clone $query)->expense()
            ->selectRaw('account_item_id, CAST(SUM(amount) AS INTEGER) as total_amount, CAST(SUM(tax_amount) AS INTEGER) as total_tax')
            ->groupBy('account_item_id')
            ->with('accountItem')
            ->get();

        $stores = $company->stores()->where('delete_flg', 0)->get();

        return view('company.transactions.report', compact(
            'targetMonth',
            'totalRevenue',
            'totalRevenueTax',
            'totalExpense',
            'totalExpenseTax',
            'revenueByItem',
            'expenseByItem',
            'stores'
        ));
    }

    /**
     * CSVエクスポート
     */
    public function export(Request $request)
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $storeId = $request->input('store_id');

        $query = $company->transactions()
            ->with(['store', 'accountItem'])
            ->where('delete_flg', 0)
            ->orderBy('date', 'asc');

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        $transactions = $query->get();

        $filename = '取引データ_' . Carbon::now()->format('YmdHis') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // BOM付加（Excel対応）
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // ヘッダー行
            fputcsv($file, ['日付', '店舗名', '取引種別', '科目', '金額', '税額', '合計金額', 'ソース', '備考']);
            
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->date->format('Y-m-d'),
                    $transaction->store->name,
                    $transaction->type_name,
                    $transaction->accountItem->name,
                    $transaction->amount,
                    $transaction->tax_amount,
                    $transaction->total_amount,
                    $transaction->source_name,
                    $transaction->note,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}


