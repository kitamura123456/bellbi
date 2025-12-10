@extends('layouts.company')

@section('title', '科目マスタ管理')

@section('content')
<div class="content-header">
    <h1 class="content-title">科目マスタ管理</h1>
    <div class="content-actions">
        <a href="{{ route('company.account-items.create') }}" class="btn btn-primary">新しい科目を追加</a>
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
                <table class="account-items-table">
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
                                    <a href="{{ route('company.account-items.edit', $item) }}" class="btn btn-sm btn-secondary">編集</a>
                                    <form action="{{ route('company.account-items.destroy', $item) }}" method="POST" class="inline-form" onsubmit="return confirm('この科目を削除してもよろしいですか？');">
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
        @else
            <p class="empty-message">売上科目が登録されていません。</p>
        @endif
    </div>

    <!-- 経費科目 -->
    <div class="account-items-section">
        <h2 class="section-title">経費科目</h2>
        
        @if ($expenseItems->count() > 0)
            <div class="table-wrapper">
                <table class="account-items-table">
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
                                    <a href="{{ route('company.account-items.edit', $item) }}" class="btn btn-sm btn-secondary">編集</a>
                                    <form action="{{ route('company.account-items.destroy', $item) }}" method="POST" class="inline-form" onsubmit="return confirm('この科目を削除してもよろしいですか？');">
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
</style>
@endsection


