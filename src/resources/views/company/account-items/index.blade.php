@extends('layouts.company')

@section('title', '科目マスタ管理')

@section('content')
<div style="margin-bottom: 24px; margin-top: 48px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">科目マスタ管理</h1>
    <div>
        <a href="{{ route('company.account-items.create') }}" style="
            padding: 12px 32px;
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
            新しい科目を追加
        </a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
@endif

<div class="account-items-container">
    <!-- 売上科目 -->
    <div class="account-items-section">
        <h2 class="section-title">売上科目</h2>
        
        @if ($revenueItems->count() > 0)
            <div class="table-wrapper">
                <table class="account-items-table revenue-items-table">
                    <thead>
                        <tr>
                            <th class="col-name">科目名</th>
                            <th class="col-tax">デフォルト税率</th>
                            <th class="col-date">登録日</th>
                            <th class="col-actions">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($revenueItems as $item)
                            <tr>
                                <td class="col-name">{{ $item->name }}</td>
                                <td class="col-tax">{{ $item->default_tax_rate ? $item->default_tax_rate . '%' : '-' }}</td>
                                <td class="col-date">{{ $item->created_at->format('Y年m月d日') }}</td>
                                <td class="col-actions">
                                    <a href="{{ route('company.account-items.edit', $item) }}" style="
                                        padding: 8px 16px;
                                        background: transparent;
                                        color: #5D535E;
                                        border: 1px solid #5D535E;
                                        border-radius: 20px;
                                        font-size: 13px;
                                        font-weight: 700;
                                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                                        text-decoration: none;
                                        cursor: pointer;
                                        transition: all 0.2s ease;
                                        display: inline-block;
                                    " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                                        編集
                                    </a>
                                    <form action="{{ route('company.account-items.destroy', $item) }}" method="POST" class="inline-form" onsubmit="return confirm('この科目を削除してもよろしいですか？');" style="display: inline; margin-left: 6px;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="
                                            padding: 8px 16px;
                                            background: transparent;
                                            color: #dc2626;
                                            border: 1px solid #dc2626;
                                            border-radius: 20px;
                                            font-size: 13px;
                                            font-weight: 700;
                                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                                            cursor: pointer;
                                            transition: all 0.2s ease;
                                        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#dc2626'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#dc2626';">
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
            <div class="account-items-cards revenue-items-cards">
                @foreach ($revenueItems as $item)
                <div class="account-item-card">
                    <div class="account-item-card-header">
                        <div class="account-item-card-name">{{ $item->name }}</div>
                    </div>
                    <div class="account-item-card-body">
                        <div class="account-item-card-row">
                            <span class="account-item-card-label">デフォルト税率</span>
                            <span class="account-item-card-value">{{ $item->default_tax_rate ? $item->default_tax_rate . '%' : '-' }}</span>
                        </div>
                        <div class="account-item-card-row">
                            <span class="account-item-card-label">登録日</span>
                            <span class="account-item-card-value">{{ $item->created_at->format('Y年m月d日') }}</span>
                        </div>
                    </div>
                    <div class="account-item-card-actions">
                        <a href="{{ route('company.account-items.edit', $item) }}" class="account-item-card-btn account-item-card-btn-edit">
                            編集
                        </a>
                        <form action="{{ route('company.account-items.destroy', $item) }}" method="POST" class="account-item-card-form" onsubmit="return confirm('この科目を削除してもよろしいですか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="account-item-card-btn account-item-card-btn-delete">
                                削除
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <p class="empty-message">売上科目が登録されていません。</p>
        @endif
    </div>

    <!-- 経費科目 -->
    <div class="account-items-section">
        <h2 class="section-title">経費科目</h2>
        
        @if ($expenseItems->count() > 0)
            <div class="table-wrapper">
                <table class="account-items-table expense-items-table">
                    <thead>
                        <tr>
                            <th class="col-name">科目名</th>
                            <th class="col-tax">デフォルト税率</th>
                            <th class="col-date">登録日</th>
                            <th class="col-actions">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenseItems as $item)
                            <tr>
                                <td class="col-name">{{ $item->name }}</td>
                                <td class="col-tax">{{ $item->default_tax_rate ? $item->default_tax_rate . '%' : '-' }}</td>
                                <td class="col-date">{{ $item->created_at->format('Y年m月d日') }}</td>
                                <td class="col-actions">
                                    <a href="{{ route('company.account-items.edit', $item) }}" style="
                                        padding: 8px 16px;
                                        background: transparent;
                                        color: #5D535E;
                                        border: 1px solid #5D535E;
                                        border-radius: 20px;
                                        font-size: 13px;
                                        font-weight: 700;
                                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                                        text-decoration: none;
                                        cursor: pointer;
                                        transition: all 0.2s ease;
                                        display: inline-block;
                                    " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                                        編集
                                    </a>
                                    <form action="{{ route('company.account-items.destroy', $item) }}" method="POST" class="inline-form" onsubmit="return confirm('この科目を削除してもよろしいですか？');" style="display: inline; margin-left: 6px;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="
                                            padding: 8px 16px;
                                            background: transparent;
                                            color: #dc2626;
                                            border: 1px solid #dc2626;
                                            border-radius: 20px;
                                            font-size: 13px;
                                            font-weight: 700;
                                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                                            cursor: pointer;
                                            transition: all 0.2s ease;
                                        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#dc2626'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#dc2626';">
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
            <div class="account-items-cards expense-items-cards">
                @foreach ($expenseItems as $item)
                <div class="account-item-card">
                    <div class="account-item-card-header">
                        <div class="account-item-card-name">{{ $item->name }}</div>
                    </div>
                    <div class="account-item-card-body">
                        <div class="account-item-card-row">
                            <span class="account-item-card-label">デフォルト税率</span>
                            <span class="account-item-card-value">{{ $item->default_tax_rate ? $item->default_tax_rate . '%' : '-' }}</span>
                        </div>
                        <div class="account-item-card-row">
                            <span class="account-item-card-label">登録日</span>
                            <span class="account-item-card-value">{{ $item->created_at->format('Y年m月d日') }}</span>
                        </div>
                    </div>
                    <div class="account-item-card-actions">
                        <a href="{{ route('company.account-items.edit', $item) }}" class="account-item-card-btn account-item-card-btn-edit">
                            編集
                        </a>
                        <form action="{{ route('company.account-items.destroy', $item) }}" method="POST" class="account-item-card-form" onsubmit="return confirm('この科目を削除してもよろしいですか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="account-item-card-btn account-item-card-btn-delete">
                                削除
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <p class="empty-message">経費科目が登録されていません。</p>
        @endif
    </div>
</div>

<style>
.account-items-container {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.account-items-section {
    background: white;
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.section-title {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
}

.table-wrapper {
    overflow-x: auto;
}

.account-items-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 15px;
    border: 1px solid #d1d5db;
}

.account-items-table thead {
    background: #f3f4f6;
}

.account-items-table thead th {
    padding: 14px 16px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 2px solid #d1d5db;
    border-right: 1px solid #d1d5db;
}

.account-items-table thead th:last-child {
    border-right: none;
}

.account-items-table tbody tr {
    border-bottom: 1px solid #e5e7eb;
    transition: background-color 0.15s;
}

.account-items-table tbody tr:hover {
    background: #f9fafb;
}

.account-items-table tbody td {
    padding: 12px 16px;
    color: #111827;
    border-right: 1px solid #e5e7eb;
}

.account-items-table tbody td:last-child {
    border-right: none;
}

.col-name {
    text-align: left;
    min-width: 180px;
}

.col-tax {
    text-align: center;
    min-width: 140px;
    font-weight: 600;
}

.col-date {
    text-align: left;
    min-width: 140px;
    font-family: 'Courier New', monospace;
}

.col-actions {
    text-align: center;
    min-width: 160px;
    white-space: nowrap;
}

.empty-message {
    color: #666;
    text-align: center;
    padding: 40px;
    background: #f5f5f5;
    border-radius: 4px;
}

.inline-form {
    display: inline;
    margin-left: 6px;
}

/* スマホ用カードレイアウト（デフォルトは非表示） */
.account-items-cards {
    display: none;
}

.account-item-card {
    background: #ffffff;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
}

.account-item-card-header {
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e8e8e8;
}

.account-item-card-name {
    font-size: 18px;
    font-weight: 700;
    color: #5D535E;
}

.account-item-card-body {
    display: grid;
    gap: 10px;
    margin-bottom: 12px;
}

.account-item-card-row {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    padding: 4px 0;
}

.account-item-card-label {
    color: #6b7280;
    font-weight: 500;
}

.account-item-card-value {
    color: #111827;
    font-weight: 600;
    text-align: right;
    flex: 1;
    margin-left: 12px;
}

.account-item-card-actions {
    display: flex;
    gap: 8px;
    padding-top: 12px;
    border-top: 1px solid #e8e8e8;
}

.account-item-card-btn {
    flex: 1;
    padding: 10px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
    text-decoration: none;
    text-align: center;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.account-item-card-btn-edit {
    background: transparent;
    color: #5D535E;
    border: 1px solid #5D535E;
}

.account-item-card-btn-delete {
    background: transparent;
    color: #dc2626;
    border: 1px solid #dc2626;
}

.account-item-card-form {
    flex: 1;
    margin: 0;
}

/* スマホ用レスポンシブデザイン */
@media (max-width: 768px) {
    div[style*="margin-top: 48px"] {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 12px !important;
    }

    div[style*="margin-top: 48px"] h1 {
        font-size: 20px !important;
        margin-bottom: 0 !important;
    }

    div[style*="margin-top: 48px"] > div > a {
        width: 100%;
        text-align: center;
        font-size: 13px;
        padding: 10px 16px;
    }

    .account-items-table {
        display: none;
    }

    .account-items-cards {
        display: block;
    }

    .account-item-card {
        margin-bottom: 16px;
    }

    .account-item-card-name {
        font-size: 20px;
    }

    .account-item-card-row {
        font-size: 15px;
    }

    .account-item-card-btn {
        font-size: 14px;
        padding: 12px 16px;
    }
}

@media (max-width: 480px) {
    .account-item-card {
        padding: 12px;
    }

    .account-item-card-name {
        font-size: 18px;
    }

    .account-item-card-row {
        font-size: 14px;
    }

    .account-item-card-btn {
        font-size: 13px;
        padding: 10px 12px;
    }
}
</style>
@endsection


