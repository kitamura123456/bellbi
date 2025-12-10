@extends('layouts.company')

@section('title', '売上・経費管理')

@section('content')
<div class="content-header">
    <h1 class="content-title">売上・経費管理</h1>
    <div class="content-actions">
        <a href="{{ route('company.account-items.index') }}" class="btn btn-secondary">科目マスタ</a>
        <a href="{{ route('company.transactions.report') }}" class="btn btn-secondary">月次レポート</a>
        <a href="{{ route('company.transactions.create') }}" class="btn btn-primary">取引を登録</a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- フィルター -->
<div class="filter-container">
    <form action="{{ route('company.transactions.index') }}" method="GET" class="filter-form">
        <div class="filter-row">
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
                <label for="transaction_type">種別</label>
                <select name="transaction_type" id="transaction_type" class="form-control">
                    <option value="">すべて</option>
                    <option value="1" {{ request('transaction_type') == 1 ? 'selected' : '' }}>売上</option>
                    <option value="2" {{ request('transaction_type') == 2 ? 'selected' : '' }}>経費</option>
                </select>
            </div>

            <div class="filter-item">
                <label for="start_date">開始日</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>

            <div class="filter-item">
                <label for="end_date">終了日</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>

            <div class="filter-item">
                <button type="submit" class="btn btn-primary">検索</button>
                <a href="{{ route('company.transactions.index') }}" class="btn btn-secondary">クリア</a>
            </div>
        </div>
    </form>

    <div class="export-action">
        <a href="{{ route('company.transactions.export') }}?{{ http_build_query(request()->all()) }}" class="btn btn-secondary">CSVエクスポート</a>
    </div>
</div>

<!-- 取引一覧 -->
<div class="transactions-list">
    @if ($transactions->count() > 0)
        <div class="table-wrapper">
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th class="col-date">日付</th>
                        <th class="col-store">店舗</th>
                        <th class="col-type">種別</th>
                        <th class="col-item">科目</th>
                        <th class="col-amount">金額</th>
                        <th class="col-amount">税額</th>
                        <th class="col-amount">合計</th>
                        <th class="col-note">備考</th>
                        <th class="col-actions">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="col-date">{{ $transaction->date->format('Y/m/d') }}</td>
                            <td class="col-store">{{ $transaction->store->name }}</td>
                            <td class="col-type">
                                <span class="badge badge-{{ $transaction->transaction_type == 1 ? 'success' : 'warning' }}">
                                    {{ $transaction->type_name }}
                                </span>
                            </td>
                            <td class="col-item">{{ $transaction->accountItem->name }}</td>
                            <td class="col-amount">¥{{ number_format($transaction->amount) }}</td>
                            <td class="col-amount">¥{{ number_format($transaction->tax_amount) }}</td>
                            <td class="col-amount amount-highlight">¥{{ number_format($transaction->total_amount) }}</td>
                            <td class="col-note">{{ Str::limit($transaction->note, 30) }}</td>
                            <td class="col-actions">
                                <a href="{{ route('company.transactions.edit', $transaction) }}" class="btn btn-sm btn-secondary">編集</a>
                                <form action="{{ route('company.transactions.destroy', $transaction) }}" method="POST" class="inline-form" onsubmit="return confirm('この取引を削除してもよろしいですか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">削除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- ページネーション -->
        <div class="pagination-container">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
    @else
        <div class="empty-state">
            <p>取引が登録されていません。</p>
            <a href="{{ route('company.transactions.create') }}" class="btn btn-primary">最初の取引を登録</a>
        </div>
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

.filter-form {
    margin-bottom: 16px;
}

.filter-row {
    display: flex;
    gap: 16px;
    align-items: flex-end;
    flex-wrap: wrap;
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

.export-action {
    text-align: right;
}

.transactions-list {
    background: white;
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.table-wrapper {
    overflow-x: auto;
}

.transactions-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    border: 1px solid #d1d5db;
}

.transactions-table thead {
    background: #f3f4f6;
}

.transactions-table thead th {
    padding: 12px 14px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 2px solid #d1d5db;
    border-right: 1px solid #d1d5db;
    white-space: nowrap;
}

.transactions-table thead th:last-child {
    border-right: none;
}

.transactions-table tbody tr {
    border-bottom: 1px solid #e5e7eb;
    transition: background-color 0.15s;
}

.transactions-table tbody tr:hover {
    background: #f9fafb;
}

.transactions-table tbody td {
    padding: 10px 14px;
    color: #111827;
    border-right: 1px solid #e5e7eb;
}

.transactions-table tbody td:last-child {
    border-right: none;
}

.col-date {
    text-align: left;
    min-width: 90px;
    font-family: 'Courier New', monospace;
}

.col-store {
    text-align: left;
    min-width: 140px;
}

.col-type {
    text-align: center;
    min-width: 70px;
}

.col-item {
    text-align: left;
    min-width: 140px;
}

.col-amount {
    text-align: right;
    min-width: 100px;
    font-family: 'Courier New', monospace;
}

.col-note {
    text-align: left;
    min-width: 120px;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.col-actions {
    text-align: center;
    min-width: 160px;
    white-space: nowrap;
}

.amount-highlight {
    font-weight: 600;
    color: #059669;
}

.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.badge-success {
    background: #d1fae5;
    color: #065f46;
}

.badge-warning {
    background: #fef3c7;
    color: #92400e;
}

.inline-form {
    display: inline;
    margin-left: 6px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state p {
    color: #6b7280;
    margin-bottom: 20px;
    font-size: 16px;
}

.pagination-container {
    margin-top: 24px;
    display: flex;
    justify-content: center;
}
</style>
@endsection


