@extends('layouts.company')

@section('title', '月次レポート')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">月次レポート</h1>
    <div>
        <a href="{{ route('company.transactions.index') }}" style="
            padding: 12px 24px;
            background: transparent;
            color: #5D535E;
            border: 1px solid #5D535E;
            border-radius: 24px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
            取引一覧
        </a>
    </div>
</div>

<!-- フィルター -->
<div class="filter-container">
    <form action="{{ route('company.transactions.report') }}" method="GET" class="filter-form">
        <div class="filter-row">
            <div class="filter-item">
                <label for="month">対象年月</label>
                <input type="month" name="month" id="month" class="form-control" value="{{ $targetMonth }}">
            </div>

            <div class="filter-item">
                <label for="store_id">店舗</label>
                <select name="store_id" id="store_id" class="form-control">
                    <option value="">すべて</option>
                    @foreach ($stores as $store)
                        <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <button type="submit" style="
                    padding: 12px 32px;
                    background: #5D535E;
                    color: #ffffff;
                    border: none;
                    border-radius: 24px;
                    font-size: 14px;
                    font-weight: 700;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    position: relative;
                " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                    表示
                </button>
            </div>
        </div>
    </form>
</div>

<!-- サマリー -->
<div class="summary-container">
    <div class="summary-card revenue-card">
        <div class="summary-label">売上合計</div>
        <div class="summary-amount">¥{{ number_format($totalRevenue + $totalRevenueTax) }}</div>
        <div class="summary-detail">
            <span>税抜: ¥{{ number_format($totalRevenue) }}</span>
            <span>税額: ¥{{ number_format($totalRevenueTax) }}</span>
        </div>
    </div>

    <div class="summary-card expense-card">
        <div class="summary-label">経費合計</div>
        <div class="summary-amount">¥{{ number_format($totalExpense + $totalExpenseTax) }}</div>
        <div class="summary-detail">
            <span>税抜: ¥{{ number_format($totalExpense) }}</span>
            <span>税額: ¥{{ number_format($totalExpenseTax) }}</span>
        </div>
    </div>

    <div class="summary-card profit-card">
        <div class="summary-label">差引</div>
        <div class="summary-amount">¥{{ number_format(($totalRevenue + $totalRevenueTax) - ($totalExpense + $totalExpenseTax)) }}</div>
        <div class="summary-detail">
            売上 - 経費
        </div>
    </div>
</div>

<!-- 科目別売上 -->
<div class="report-section">
    <h2 class="section-title">売上科目別内訳</h2>
    
    @if ($revenueByItem->count() > 0)
        <div class="table-wrapper">
            <table class="report-table">
                <thead>
                    <tr>
                        <th class="col-name">科目名</th>
                        <th class="col-amount">税抜金額</th>
                        <th class="col-amount">税額</th>
                        <th class="col-amount">税込合計</th>
                        <th class="col-ratio">構成比</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($revenueByItem as $item)
                        <tr>
                            <td class="col-name">{{ $item->accountItem->name }}</td>
                            <td class="col-amount">¥{{ number_format($item->total_amount) }}</td>
                            <td class="col-amount">¥{{ number_format($item->total_tax) }}</td>
                            <td class="col-amount amount-highlight">¥{{ number_format($item->total_amount + $item->total_tax) }}</td>
                            <td class="col-ratio">{{ $totalRevenue > 0 ? number_format(($item->total_amount / $totalRevenue) * 100, 1) : 0 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td class="col-name">合計</td>
                        <td class="col-amount">¥{{ number_format($totalRevenue) }}</td>
                        <td class="col-amount">¥{{ number_format($totalRevenueTax) }}</td>
                        <td class="col-amount amount-highlight">¥{{ number_format($totalRevenue + $totalRevenueTax) }}</td>
                        <td class="col-ratio">100.0%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <p class="empty-message">売上データがありません。</p>
    @endif
</div>

<!-- 科目別経費 -->
<div class="report-section">
    <h2 class="section-title">経費科目別内訳</h2>
    
    @if ($expenseByItem->count() > 0)
        <div class="table-wrapper">
            <table class="report-table">
                <thead>
                    <tr>
                        <th class="col-name">科目名</th>
                        <th class="col-amount">税抜金額</th>
                        <th class="col-amount">税額</th>
                        <th class="col-amount">税込合計</th>
                        <th class="col-ratio">構成比</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenseByItem as $item)
                        <tr>
                            <td class="col-name">{{ $item->accountItem->name }}</td>
                            <td class="col-amount">¥{{ number_format($item->total_amount) }}</td>
                            <td class="col-amount">¥{{ number_format($item->total_tax) }}</td>
                            <td class="col-amount amount-highlight">¥{{ number_format($item->total_amount + $item->total_tax) }}</td>
                            <td class="col-ratio">{{ $totalExpense > 0 ? number_format(($item->total_amount / $totalExpense) * 100, 1) : 0 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td class="col-name">合計</td>
                        <td class="col-amount">¥{{ number_format($totalExpense) }}</td>
                        <td class="col-amount">¥{{ number_format($totalExpenseTax) }}</td>
                        <td class="col-amount amount-highlight">¥{{ number_format($totalExpense + $totalExpenseTax) }}</td>
                        <td class="col-ratio">100.0%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <p class="empty-message">経費データがありません。</p>
    @endif
</div>

<style>
.filter-container {
    background: white;
    border-radius: 8px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.filter-row {
    display: flex;
    gap: 16px;
    align-items: flex-end;
}

.filter-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.filter-item label {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
}

.filter-item .form-control {
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    font-size: 14px;
}

.summary-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.summary-card {
    background: white;
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border-left: 4px solid;
}

.revenue-card {
    border-left-color: #10b981;
}

.expense-card {
    border-left-color: #f59e0b;
}

.profit-card {
    border-left-color: #3b82f6;
}

.summary-label {
    font-size: 14px;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 8px;
}

.summary-amount {
    font-size: 32px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.summary-detail {
    display: flex;
    gap: 16px;
    font-size: 13px;
    color: #6b7280;
}

.report-section {
    background: white;
    border-radius: 8px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
}

.table-wrapper {
    overflow-x: auto;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 15px;
    border: 1px solid #d1d5db;
}

.report-table thead {
    background: #f3f4f6;
}

.report-table thead th {
    padding: 14px 16px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 2px solid #d1d5db;
    border-right: 1px solid #d1d5db;
}

.report-table thead th:last-child {
    border-right: none;
}

.report-table tbody tr {
    border-bottom: 1px solid #e5e7eb;
    transition: background-color 0.15s;
}

.report-table tbody tr:hover {
    background: #f9fafb;
}

.report-table tbody td {
    padding: 12px 16px;
    color: #111827;
    border-right: 1px solid #e5e7eb;
}

.report-table tbody td:last-child {
    border-right: none;
}

.report-table tfoot {
    background: #f9fafb;
    border-top: 2px solid #d1d5db;
}

.report-table tfoot td {
    padding: 14px 16px;
    font-weight: 700;
    font-size: 16px;
    color: #111827;
    border-right: 1px solid #d1d5db;
}

.report-table tfoot td:last-child {
    border-right: none;
}

.col-name {
    text-align: left;
    min-width: 180px;
}

.col-amount {
    text-align: right;
    min-width: 120px;
    font-family: 'Courier New', monospace;
}

.col-ratio {
    text-align: right;
    min-width: 100px;
    font-weight: 600;
    color: #6b7280;
}

.amount-highlight {
    font-weight: 600;
    color: #059669;
}

.total-row .amount-highlight {
    color: #047857;
    font-size: 17px;
}

.empty-message {
    text-align: center;
    padding: 40px;
    color: #6b7280;
    background: #f9fafb;
    border-radius: 4px;
}
</style>
@endsection


