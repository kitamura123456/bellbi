@extends('layouts.admin')

@section('title', '補助金情報管理')

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

    .btn-primary {
        padding: 8px 16px;
        background: #2271b1;
        color: #fff;
        text-decoration: none;
        border-radius: 3px;
        font-size: 13px;
        font-weight: 400;
        display: inline-block;
    }

    .btn-primary:hover {
        background: #135e96;
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

    .badge-info {
        background-color: #e0e7ff;
        color: #4338ca;
    }

    .badge-success {
        background-color: #d4edda;
        color: #155724;
    }

    .badge-secondary {
        background-color: #e2e8f0;
        color: #64748b;
    }

    .badge-warning {
        background-color: #fff3cd;
        color: #856404;
    }

    .empty-message {
        text-align: center;
        padding: 40px 20px;
        color: #666;
    }

    .alert {
        padding: 12px;
        margin-bottom: 20px;
        border-left: 4px solid;
        background: #fff;
    }

    .alert-error {
        border-left-color: #dc3232;
        background: #fcf0f1;
        color: #721c24;
    }

    /* モバイル用カード表示 */
    .mobile-subsidy-card {
        display: none;
    }

    .mobile-subsidy-card-item {
        padding: 12px 0;
        border-bottom: 1px solid #e8e8e8;
    }

    .mobile-subsidy-card-item:last-child {
        border-bottom: none;
    }

    .mobile-subsidy-card-label {
        font-size: 11px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .mobile-subsidy-card-value {
        font-size: 14px;
        color: #1a1a1a;
        margin-bottom: 8px;
        word-wrap: break-word;
    }

    .mobile-subsidy-card-actions {
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

        .mobile-subsidy-card {
            display: block;
        }

        .btn-primary {
            width: 100%;
            text-align: center;
            padding: 10px 16px;
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

        .mobile-subsidy-card-item {
            padding: 10px 0;
        }

        .mobile-subsidy-card-label {
            font-size: 10px;
        }

        .mobile-subsidy-card-value {
            font-size: 13px;
        }
    }
</style>

<div class="admin-header">
    <h1 class="admin-title">補助金情報管理</h1>
    <a href="{{ route('admin.subsidies.create') }}" class="btn-primary">新規補助金追加</a>
</div>

@if (session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
@endif

<div class="admin-card">
    <!-- デスクトップ用テーブル -->
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>タイトル</th>
                <th>カテゴリ</th>
                <th>対象地域</th>
                <th>対象業種</th>
                <th>申請期間</th>
                <th>ステータス</th>
                <th>登録日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subsidies as $subsidy)
            <tr>
                <td>{{ $subsidy->id }}</td>
                <td style="max-width: 250px;">
                    <span style="display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $subsidy->title }}">
                        {{ $subsidy->title }}
                    </span>
                </td>
                <td>
                    @if($subsidy->category)
                        <span class="badge badge-info">{{ $subsidy->category_name }}</span>
                    @else
                        <span style="color: #999;">未設定</span>
                    @endif
                </td>
                <td>
                    @if($subsidy->target_region)
                        @php
                            $region = \App\Enums\Todofuken::tryFrom($subsidy->target_region);
                        @endphp
                        @if($region)
                            {{ $region->label() }}
                        @else
                            全国
                        @endif
                    @else
                        <span style="color: #999;">全国</span>
                    @endif
                </td>
                <td>
                    @if($subsidy->applicable_industry_type)
                        @php
                            $industryTypes = \App\Services\BusinessCategoryService::getIndustryTypes();
                        @endphp
                        {{ $industryTypes[$subsidy->applicable_industry_type] ?? '全業種' }}
                    @else
                        <span style="color: #999;">全業種</span>
                    @endif
                </td>
                <td style="font-size: 12px;">
                    @if($subsidy->application_start_at && $subsidy->application_end_at)
                        {{ $subsidy->application_start_at->format('Y/m/d') }}<br>
                        ～ {{ $subsidy->application_end_at->format('Y/m/d') }}
                    @elseif($subsidy->application_start_at)
                        {{ $subsidy->application_start_at->format('Y/m/d') }} 以降
                    @elseif($subsidy->application_end_at)
                        {{ $subsidy->application_end_at->format('Y/m/d') }} まで
                    @else
                        <span style="color: #999;">未設定</span>
                    @endif
                </td>
                <td>
                    @if($subsidy->status == \App\Models\Subsidy::STATUS_RECRUITING)
                        <span class="badge badge-success">募集中</span>
                    @elseif($subsidy->status == \App\Models\Subsidy::STATUS_CLOSED)
                        <span class="badge badge-secondary">締切</span>
                    @elseif($subsidy->status == \App\Models\Subsidy::STATUS_NOT_STARTED)
                        <span class="badge badge-warning">未開始</span>
                    @else
                        <span class="badge badge-secondary">不明</span>
                    @endif
                </td>
                <td>{{ $subsidy->created_at->format('Y-m-d') }}</td>
                <td class="admin-actions">
                    <a href="{{ route('admin.subsidies.edit', $subsidy) }}" class="btn-secondary btn-sm">編集</a>
                    <form action="{{ route('admin.subsidies.destroy', $subsidy) }}" method="POST" style="display:inline;" onsubmit="return confirm('この補助金情報を削除してもよろしいですか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">削除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="empty-message">補助金情報が登録されていません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- モバイル用カード表示 -->
    <div class="mobile-subsidy-card">
        @forelse($subsidies as $subsidy)
        <div class="mobile-subsidy-card-item">
            <div class="mobile-subsidy-card-label">ID</div>
            <div class="mobile-subsidy-card-value">{{ $subsidy->id }}</div>

            <div class="mobile-subsidy-card-label">タイトル</div>
            <div class="mobile-subsidy-card-value">{{ $subsidy->title }}</div>

            <div class="mobile-subsidy-card-label">カテゴリ</div>
            <div class="mobile-subsidy-card-value">
                @if($subsidy->category)
                    <span class="badge badge-info">{{ $subsidy->category_name }}</span>
                @else
                    <span style="color: #999;">未設定</span>
                @endif
            </div>

            <div class="mobile-subsidy-card-label">対象地域</div>
            <div class="mobile-subsidy-card-value">
                @if($subsidy->target_region)
                    @php
                        $region = \App\Enums\Todofuken::tryFrom($subsidy->target_region);
                    @endphp
                    @if($region)
                        {{ $region->label() }}
                    @else
                        全国
                    @endif
                @else
                    <span style="color: #999;">全国</span>
                @endif
            </div>

            <div class="mobile-subsidy-card-label">対象業種</div>
            <div class="mobile-subsidy-card-value">
                @if($subsidy->applicable_industry_type)
                    @php
                        $industryTypes = \App\Services\BusinessCategoryService::getIndustryTypes();
                    @endphp
                    {{ $industryTypes[$subsidy->applicable_industry_type] ?? '全業種' }}
                @else
                    <span style="color: #999;">全業種</span>
                @endif
            </div>

            <div class="mobile-subsidy-card-label">申請期間</div>
            <div class="mobile-subsidy-card-value">
                @if($subsidy->application_start_at && $subsidy->application_end_at)
                    {{ $subsidy->application_start_at->format('Y/m/d') }}<br>
                    ～ {{ $subsidy->application_end_at->format('Y/m/d') }}
                @elseif($subsidy->application_start_at)
                    {{ $subsidy->application_start_at->format('Y/m/d') }} 以降
                @elseif($subsidy->application_end_at)
                    {{ $subsidy->application_end_at->format('Y/m/d') }} まで
                @else
                    <span style="color: #999;">未設定</span>
                @endif
            </div>

            <div class="mobile-subsidy-card-label">ステータス</div>
            <div class="mobile-subsidy-card-value">
                @if($subsidy->status == \App\Models\Subsidy::STATUS_RECRUITING)
                    <span class="badge badge-success">募集中</span>
                @elseif($subsidy->status == \App\Models\Subsidy::STATUS_CLOSED)
                    <span class="badge badge-secondary">締切</span>
                @elseif($subsidy->status == \App\Models\Subsidy::STATUS_NOT_STARTED)
                    <span class="badge badge-warning">未開始</span>
                @else
                    <span class="badge badge-secondary">不明</span>
                @endif
            </div>

            <div class="mobile-subsidy-card-label">登録日</div>
            <div class="mobile-subsidy-card-value">{{ $subsidy->created_at->format('Y-m-d') }}</div>

            <div class="mobile-subsidy-card-actions admin-actions">
                <a href="{{ route('admin.subsidies.edit', $subsidy) }}" class="btn-secondary">編集</a>
                <form action="{{ route('admin.subsidies.destroy', $subsidy) }}" method="POST" style="display:inline;" onsubmit="return confirm('この補助金情報を削除してもよろしいですか？');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger">削除</button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-message">補助金情報が登録されていません。</div>
        @endforelse
    </div>
</div>

@if($subsidies->hasPages())
    <div style="margin-top: 24px;">
        {{ $subsidies->links() }}
    </div>
@endif
@endsection
