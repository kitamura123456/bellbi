@extends('layouts.admin')

@section('title', 'プラン管理')

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

    .badge-success {
        background-color: #d4edda;
        color: #155724;
    }

    .badge-secondary {
        background-color: #e2e8f0;
        color: #64748b;
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
    .mobile-plan-card {
        display: none;
    }

    .mobile-plan-card-item {
        padding: 12px 0;
        border-bottom: 1px solid #e8e8e8;
    }

    .mobile-plan-card-item:last-child {
        border-bottom: none;
    }

    .mobile-plan-card-label {
        font-size: 11px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .mobile-plan-card-value {
        font-size: 14px;
        color: #1a1a1a;
        margin-bottom: 8px;
        word-wrap: break-word;
    }

    .mobile-plan-card-actions {
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

        .mobile-plan-card {
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

        .mobile-plan-card-item {
            padding: 10px 0;
        }

        .mobile-plan-card-label {
            font-size: 10px;
        }

        .mobile-plan-card-value {
            font-size: 13px;
        }
    }
</style>

<div class="admin-header">
    <h1 class="admin-title">プラン管理</h1>
    <a href="{{ route('admin.plans.create') }}" class="btn-primary">新規プラン追加</a>
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
                <th>プラン名</th>
                <th>説明</th>
                <th>月額料金</th>
                <th>機能ビットマスク</th>
                <th>ステータス</th>
                <th>登録日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($plans as $plan)
            <tr>
                <td>{{ $plan->id }}</td>
                <td>{{ $plan->name }}</td>
                <td style="max-width: 300px;">
                    @if($plan->description)
                        <span style="display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $plan->description }}">
                            {{ Str::limit($plan->description, 50) }}
                        </span>
                    @else
                        <span style="color: #999;">未設定</span>
                    @endif
                </td>
                <td>¥{{ number_format($plan->price_monthly) }}/月</td>
                <td>{{ $plan->features_bitmask }}</td>
                <td>
                    @if($plan->status === \App\Models\Plan::STATUS_ACTIVE)
                        <span class="badge badge-success">有効</span>
                    @else
                        <span class="badge badge-secondary">無効</span>
                    @endif
                </td>
                <td>{{ $plan->created_at->format('Y-m-d') }}</td>
                <td class="admin-actions">
                    <a href="{{ route('admin.plans.edit', $plan) }}" class="btn-secondary btn-sm">編集</a>
                    <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" style="display:inline;" onsubmit="return confirm('このプランを削除してもよろしいですか？\n\n注意: 有効な契約が存在する場合は削除できません。');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">削除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="empty-message">プランが登録されていません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- モバイル用カード表示 -->
    <div class="mobile-plan-card">
        @forelse($plans as $plan)
        <div class="mobile-plan-card-item">
            <div class="mobile-plan-card-label">ID</div>
            <div class="mobile-plan-card-value">{{ $plan->id }}</div>

            <div class="mobile-plan-card-label">プラン名</div>
            <div class="mobile-plan-card-value">{{ $plan->name }}</div>

            <div class="mobile-plan-card-label">説明</div>
            <div class="mobile-plan-card-value">
                @if($plan->description)
                    {{ $plan->description }}
                @else
                    <span style="color: #999;">未設定</span>
                @endif
            </div>

            <div class="mobile-plan-card-label">月額料金</div>
            <div class="mobile-plan-card-value">¥{{ number_format($plan->price_monthly) }}/月</div>

            <div class="mobile-plan-card-label">機能ビットマスク</div>
            <div class="mobile-plan-card-value">{{ $plan->features_bitmask }}</div>

            <div class="mobile-plan-card-label">ステータス</div>
            <div class="mobile-plan-card-value">
                @if($plan->status === \App\Models\Plan::STATUS_ACTIVE)
                    <span class="badge badge-success">有効</span>
                @else
                    <span class="badge badge-secondary">無効</span>
                @endif
            </div>

            <div class="mobile-plan-card-label">登録日</div>
            <div class="mobile-plan-card-value">{{ $plan->created_at->format('Y-m-d') }}</div>

            <div class="mobile-plan-card-actions admin-actions">
                <a href="{{ route('admin.plans.edit', $plan) }}" class="btn-secondary">編集</a>
                <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" style="display:inline;" onsubmit="return confirm('このプランを削除してもよろしいですか？\n\n注意: 有効な契約が存在する場合は削除できません。');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger">削除</button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-message">プランが登録されていません。</div>
        @endforelse
    </div>
</div>
@endsection
