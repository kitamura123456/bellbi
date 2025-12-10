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
            <table class="data-table">
                <thead>
                    <tr>
                        <th>科目名</th>
                        <th>デフォルト税率</th>
                        <th>登録日</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($revenueItems as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->default_tax_rate ? $item->default_tax_rate . '%' : '-' }}</td>
                            <td>{{ $item->created_at->format('Y年m月d日') }}</td>
                            <td class="actions-cell">
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
        @else
            <p class="empty-message">売上科目が登録されていません。</p>
        @endif
    </div>

    <!-- 経費科目 -->
    <div class="account-items-section">
        <h2 class="section-title">経費科目</h2>
        
        @if ($expenseItems->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>科目名</th>
                        <th>デフォルト税率</th>
                        <th>登録日</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenseItems as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->default_tax_rate ? $item->default_tax_rate . '%' : '-' }}</td>
                            <td>{{ $item->created_at->format('Y年m月d日') }}</td>
                            <td class="actions-cell">
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

.empty-message {
    color: #666;
    text-align: center;
    padding: 40px;
    background: #f5f5f5;
    border-radius: 4px;
}

.inline-form {
    display: inline;
    margin-left: 8px;
}
</style>
@endsection


