@extends('layouts.admin')

@section('title', '事業者管理')

@section('content')
<style>
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .admin-title {
        margin: 0;
        font-size: 23px;
        font-weight: 400;
        color: #1a1a1a;
    }

    .admin-card {
        background-color: #ffffff;
        border: 1px solid #c3c4c7;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
        padding: 20px;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .admin-table thead {
        background-color: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }

    .admin-table th {
        padding: 10px 12px;
        text-align: left;
        font-weight: 600;
        color: #334155;
        font-size: 13px;
    }

    .admin-table td {
        padding: 12px;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
    }

    .admin-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .admin-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .btn-secondary {
        padding: 5px 12px;
        background-color: #f6f7f7;
        color: #2c3338;
        border: 1px solid #dcdcde;
        text-decoration: none;
        border-radius: 3px;
        font-size: 12px;
        cursor: pointer;
        display: inline-block;
    }

    .btn-secondary:hover {
        background-color: #f0f0f1;
        border-color: #8c8f94;
        color: #1a1a1a;
    }

    .btn-danger {
        padding: 5px 12px;
        background-color: #b32d2e;
        color: #fff;
        border: none;
        border-radius: 3px;
        font-size: 12px;
        cursor: pointer;
    }

    .btn-danger:hover {
        background-color: #dc3232;
    }

    .badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 500;
    }

    .badge-primary {
        background-color: #cce5ff;
        color: #004085;
    }

    .badge-info {
        background-color: #e0e7ff;
        color: #4338ca;
    }

    .badge-success {
        background-color: #d4edda;
        color: #155724;
    }

    .badge-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .empty-message {
        text-align: center;
        padding: 40px 20px;
        color: #666;
    }

    .pagination-wrapper {
        margin-top: 20px;
    }

    /* モバイル用カード表示 */
    .mobile-company-card {
        display: none;
    }

    .mobile-company-card-item {
        padding: 12px 0;
        border-bottom: 1px solid #e8e8e8;
    }

    .mobile-company-card-item:last-child {
        border-bottom: none;
    }

    .mobile-company-card-label {
        font-size: 11px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .mobile-company-card-value {
        font-size: 14px;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .mobile-company-card-value small {
        display: block;
        margin-top: 4px;
        color: #64748b;
        font-size: 11px;
    }

    .mobile-company-card-actions {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #f0f0f1;
    }

    /* モバイル対応 */
    @media (max-width: 782px) {
        .admin-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 16px;
        }

        .admin-title {
            font-size: 20px;
        }

        .admin-card {
            padding: 16px;
        }

        .admin-table {
            display: none;
        }

        .mobile-company-card {
            display: block;
        }

        .admin-actions {
            flex-direction: column;
        }

        .admin-actions .btn-secondary,
        .admin-actions .btn-danger {
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .admin-header {
            margin-bottom: 12px;
        }

        .admin-title {
            font-size: 18px;
        }

        .admin-card {
            padding: 12px;
        }

        .mobile-company-card-item {
            padding: 10px 0;
        }

        .mobile-company-card-label {
            font-size: 10px;
        }

        .mobile-company-card-value {
            font-size: 13px;
        }
    }
</style>

<div class="admin-header">
    <h1 class="admin-title">事業者管理</h1>
</div>

<div class="admin-card">
    <!-- デスクトップ用テーブル -->
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>事業者名</th>
                <th>担当者名</th>
                <th>業種</th>
                <th>プラン</th>
                <th>プランステータス</th>
                <th>登録日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($companies as $company)
            <tr>
                <td>{{ $company->id }}</td>
                <td>{{ $company->name }}</td>
                <td>{{ $company->contact_name ?? '-' }}</td>
                <td>
                    @if($company->industry_type === 1)
                        <span class="badge badge-primary">美容</span>
                    @elseif($company->industry_type === 2)
                        <span class="badge badge-info">医療</span>
                    @elseif($company->industry_type === 3)
                        <span class="badge badge-success">歯科</span>
                    @else
                        <span class="badge">その他</span>
                    @endif
                    @if($company->business_category)
                        <br><small style="color: #64748b; font-size: 11px;">
                            {{ \App\Services\BusinessCategoryService::getCategoryName($company->business_category) }}
                        </small>
                    @endif
                </td>
                <td>
                    @if($company->plan)
                        {{ $company->plan->name }}
                    @else
                        <span style="color: #94a3b8;">未設定</span>
                    @endif
                </td>
                <td>
                    @if($company->plan_status === 1)
                        <span class="badge badge-primary">トライアル</span>
                    @elseif($company->plan_status === 2)
                        <span class="badge badge-success">有効</span>
                    @elseif($company->plan_status === 3)
                        <span class="badge badge-danger">遅延</span>
                    @elseif($company->plan_status === 9)
                        <span class="badge" style="background-color: #e2e8f0; color: #64748b;">解約</span>
                    @endif
                </td>
                <td>{{ $company->created_at->format('Y-m-d') }}</td>
                <td class="admin-actions">
                    <a href="{{ route('admin.companies.edit', $company) }}" class="btn-secondary btn-sm">編集</a>
                    <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" style="display:inline;" onsubmit="return confirm('この事業者を削除してもよろしいですか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">削除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="empty-message">事業者が登録されていません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- モバイル用カード表示 -->
    <div class="mobile-company-card">
        @forelse($companies as $company)
        <div class="mobile-company-card-item">
            <div class="mobile-company-card-label">ID</div>
            <div class="mobile-company-card-value">{{ $company->id }}</div>

            <div class="mobile-company-card-label">事業者名</div>
            <div class="mobile-company-card-value">{{ $company->name }}</div>

            <div class="mobile-company-card-label">担当者名</div>
            <div class="mobile-company-card-value">{{ $company->contact_name ?? '-' }}</div>

            <div class="mobile-company-card-label">業種</div>
            <div class="mobile-company-card-value">
                @if($company->industry_type === 1)
                    <span class="badge badge-primary">美容</span>
                @elseif($company->industry_type === 2)
                    <span class="badge badge-info">医療</span>
                @elseif($company->industry_type === 3)
                    <span class="badge badge-success">歯科</span>
                @else
                    <span class="badge">その他</span>
                @endif
                @if($company->business_category)
                    <small>
                        {{ \App\Services\BusinessCategoryService::getCategoryName($company->business_category) }}
                    </small>
                @endif
            </div>

            <div class="mobile-company-card-label">プラン</div>
            <div class="mobile-company-card-value">
                @if($company->plan)
                    {{ $company->plan->name }}
                @else
                    <span style="color: #94a3b8;">未設定</span>
                @endif
            </div>

            <div class="mobile-company-card-label">プランステータス</div>
            <div class="mobile-company-card-value">
                @if($company->plan_status === 1)
                    <span class="badge badge-primary">トライアル</span>
                @elseif($company->plan_status === 2)
                    <span class="badge badge-success">有効</span>
                @elseif($company->plan_status === 3)
                    <span class="badge badge-danger">遅延</span>
                @elseif($company->plan_status === 9)
                    <span class="badge" style="background-color: #e2e8f0; color: #64748b;">解約</span>
                @endif
            </div>

            <div class="mobile-company-card-label">登録日</div>
            <div class="mobile-company-card-value">{{ $company->created_at->format('Y-m-d') }}</div>

            <div class="mobile-company-card-actions admin-actions">
                <a href="{{ route('admin.companies.edit', $company) }}" class="btn-secondary">編集</a>
                <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" style="display:inline;" onsubmit="return confirm('この事業者を削除してもよろしいですか？');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger">削除</button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-message">事業者が登録されていません。</div>
        @endforelse
    </div>

    <div class="pagination-wrapper">
        {{ $companies->links() }}
    </div>
</div>
@endsection
