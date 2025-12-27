@extends('layouts.company')

@section('title', '売上・経費管理')

@section('content')
<div style="margin-bottom: 24px; margin-top: 48px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">売上・経費管理</h1>
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <a href="{{ route('company.account-items.index') }}" style="
            padding: 6px 16px;
            background: transparent;
            color: #5D535E;
            border: 1px solid #5D535E;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 700;
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
            科目マスタ
        </a>
        <a href="{{ route('company.transactions.report') }}" style="
            padding: 6px 16px;
            background: transparent;
            color: #5D535E;
            border: 1px solid #5D535E;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 700;
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
            月次レポート
        </a>
        <a href="{{ route('company.transactions.create') }}" style="
            padding: 12px 24px;
            background: #5D535E;
            color: #ffffff;
            border: none;
            border-radius: 24px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
            取引を登録
        </a>
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
                <button type="submit" style="
                    padding: 8px 20px;
                    background: #5D535E;
                    color: #ffffff;
                    border: none;
                    border-radius: 16px;
                    font-size: 13px;
                    font-weight: 700;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    margin-right: 8px;
                " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                    検索
                </button>
                <a href="{{ route('company.transactions.index') }}" style="
                    padding: 8px 20px;
                    background: transparent;
                    color: #5D535E;
                    border: 1px solid #5D535E;
                    border-radius: 16px;
                    font-size: 13px;
                    font-weight: 700;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    text-decoration: none;
                    transition: all 0.2s ease;
                    display: inline-block;
                " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                    クリア
                </a>
            </div>
        </div>
    </form>

    <div class="export-action">
        <a href="{{ route('company.transactions.export') }}?{{ http_build_query(request()->all()) }}" style="
            padding: 8px 20px;
            background: transparent;
            color: #5D535E;
            border: 1px solid #5D535E;
            border-radius: 16px;
            font-size: 13px;
            font-weight: 700;
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-block;
        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
            CSVエクスポート
        </a>
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
                                <a href="{{ route('company.transactions.edit', $transaction) }}" style="
                                    padding: 6px 16px;
                                    background: transparent;
                                    color: #5D535E;
                                    border: 1px solid #5D535E;
                                    border-radius: 16px;
                                    font-size: 12px;
                                    font-weight: 700;
                                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                                    text-decoration: none;
                                    transition: all 0.2s ease;
                                    position: relative;
                                    display: inline-block;
                                " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                                    編集
                                </a>
                                <form action="{{ route('company.transactions.destroy', $transaction) }}" method="POST" class="inline-form" onsubmit="return confirm('この取引を削除してもよろしいですか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="
                                        padding: 6px 16px;
                                        background: #763626;
                                        color: #ffffff;
                                        border: none;
                                        border-radius: 16px;
                                        font-size: 12px;
                                        font-weight: 700;
                                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                                        cursor: pointer;
                                        transition: all 0.2s ease;
                                        position: relative;
                                        margin-left: 6px;
                                    " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                                        削除
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- スマホ用カードレイアウト -->
        <div class="transactions-cards">
            @foreach ($transactions as $transaction)
                <div class="transaction-card">
                    <div class="transaction-card-header">
                        <div>
                            <div class="transaction-card-date">{{ $transaction->date->format('Y年m月d日') }}</div>
                            <div class="transaction-card-store">{{ $transaction->store->name }}</div>
                        </div>
                        <span class="badge badge-{{ $transaction->transaction_type == 1 ? 'success' : 'warning' }}">
                            {{ $transaction->type_name }}
                        </span>
                    </div>
                    <div class="transaction-card-body">
                        <div class="transaction-card-row">
                            <span class="transaction-card-label">科目</span>
                            <span class="transaction-card-value">{{ $transaction->accountItem->name }}</span>
                        </div>
                        <div class="transaction-card-row">
                            <span class="transaction-card-label">金額</span>
                            <span class="transaction-card-value">¥{{ number_format($transaction->amount) }}</span>
                        </div>
                        <div class="transaction-card-row">
                            <span class="transaction-card-label">税額</span>
                            <span class="transaction-card-value">¥{{ number_format($transaction->tax_amount) }}</span>
                        </div>
                        <div class="transaction-card-row highlight">
                            <span class="transaction-card-label">合計</span>
                            <span class="transaction-card-value amount-highlight">¥{{ number_format($transaction->total_amount) }}</span>
                        </div>
                        @if($transaction->note)
                        <div class="transaction-card-row">
                            <span class="transaction-card-label">備考</span>
                            <span class="transaction-card-value">{{ Str::limit($transaction->note, 50) }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="transaction-card-actions">
                        <a href="{{ route('company.transactions.edit', $transaction) }}" class="transaction-card-btn transaction-card-btn-edit">
                            編集
                        </a>
                        <form action="{{ route('company.transactions.destroy', $transaction) }}" method="POST" class="transaction-card-form" onsubmit="return confirm('この取引を削除してもよろしいですか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="transaction-card-btn transaction-card-btn-delete">
                                削除
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ページネーション -->
        <div class="pagination-container">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
    @else
        <div class="empty-state">
            <p>取引が登録されていません。</p>
            <a href="{{ route('company.transactions.create') }}" style="
                padding: 12px 24px;
                background: #5D535E;
                color: #ffffff;
                border: none;
                border-radius: 24px;
                font-size: 14px;
                font-weight: 700;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.2s ease;
                position: relative;
                display: inline-block;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                最初の取引を登録
            </a>
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

/* スマホ用カードレイアウト（デフォルトは非表示） */
.transactions-cards {
    display: none;
}

.transaction-card {
    background: #ffffff;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
}

.transaction-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e8e8e8;
}

.transaction-card-date {
    font-size: 16px;
    font-weight: 700;
    color: #5D535E;
    margin-bottom: 4px;
}

.transaction-card-store {
    font-size: 13px;
    color: #6b7280;
}

.transaction-card-body {
    display: grid;
    gap: 10px;
    margin-bottom: 12px;
}

.transaction-card-row {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    padding: 4px 0;
}

.transaction-card-row.highlight {
    padding-top: 8px;
    border-top: 1px solid #e8e8e8;
    margin-top: 4px;
}

.transaction-card-label {
    color: #6b7280;
    font-weight: 500;
}

.transaction-card-value {
    color: #111827;
    font-weight: 600;
    text-align: right;
}

.transaction-card-actions {
    display: flex;
    gap: 8px;
    padding-top: 12px;
    border-top: 1px solid #e8e8e8;
}

.transaction-card-btn {
    flex: 1;
    padding: 10px 16px;
    border-radius: 16px;
    font-size: 13px;
    font-weight: 700;
    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
    text-decoration: none;
    text-align: center;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.transaction-card-btn-edit {
    background: transparent;
    color: #5D535E;
    border: 1px solid #5D535E;
}

.transaction-card-btn-delete {
    background: #763626;
    color: #ffffff;
}

.transaction-card-form {
    flex: 1;
    margin: 0;
}

/* スマホ用レスポンシブデザイン */
@media (max-width: 768px) {
    .transactions-list {
        padding: 16px !important;
    }

    .filter-container {
        padding: 16px !important;
    }

    .filter-row {
        flex-direction: column !important;
        gap: 12px !important;
    }

    .filter-item {
        width: 100%;
    }

    .filter-item .form-control {
        width: 100%;
        font-size: 16px;
        padding: 10px 12px;
    }

    .filter-item button,
    .filter-item a {
        width: 100%;
        text-align: center;
        font-size: 14px;
        padding: 10px 16px;
    }

    .export-action {
        text-align: left;
        margin-top: 12px;
    }

    .export-action a {
        width: 100%;
        text-align: center;
        display: block;
        font-size: 14px;
        padding: 10px 16px;
    }

    .transactions-table {
        display: none;
    }

    .transactions-cards {
        display: block;
    }

    .transaction-card {
        margin-bottom: 16px;
    }

    .transaction-card-date {
        font-size: 18px;
    }

    .transaction-card-store {
        font-size: 14px;
    }

    .transaction-card-row {
        font-size: 15px;
    }

    .transaction-card-btn {
        font-size: 14px;
        padding: 12px 16px;
    }

    .pagination-container {
        margin-top: 16px;
    }

    /* ヘッダー部分の調整 */
    div[style*="margin-top: 48px"] {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 12px !important;
    }

    div[style*="margin-top: 48px"] h1 {
        font-size: 20px !important;
        margin-bottom: 0 !important;
    }

    div[style*="margin-top: 48px"] > div {
        width: 100%;
        flex-direction: column;
        gap: 8px !important;
    }

    div[style*="margin-top: 48px"] > div > a {
        width: 100%;
        text-align: center;
        font-size: 13px;
        padding: 10px 16px;
    }
}

@media (max-width: 480px) {
    .transaction-card {
        padding: 12px;
    }

    .transaction-card-date {
        font-size: 16px;
    }

    .transaction-card-row {
        font-size: 14px;
    }

    .transaction-card-btn {
        font-size: 13px;
        padding: 10px 12px;
    }
}
</style>
@endsection


