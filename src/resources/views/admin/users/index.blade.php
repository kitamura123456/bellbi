@extends('layouts.admin')

@section('title', 'ユーザー管理')

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

    .badge-primary {
        background-color: #cce5ff;
        color: #004085;
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
    .mobile-user-card {
        display: none;
    }

    .mobile-user-card-item {
        padding: 12px 0;
        border-bottom: 1px solid #e8e8e8;
    }

    .mobile-user-card-item:last-child {
        border-bottom: none;
    }

    .mobile-user-card-label {
        font-size: 11px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .mobile-user-card-value {
        font-size: 14px;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .mobile-user-card-actions {
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

        .mobile-user-card {
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

        .mobile-user-card-item {
            padding: 10px 0;
        }

        .mobile-user-card-label {
            font-size: 10px;
        }

        .mobile-user-card-value {
            font-size: 13px;
        }
    }
</style>

<div class="admin-header">
    <h1 class="admin-title">ユーザー管理</h1>
    <a href="{{ route('admin.users.create') }}" class="btn-primary">新規ユーザー追加</a>
</div>

<div class="admin-card">
    <!-- デスクトップ用テーブル -->
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>ロール</th>
                <th>登録日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->role === 1)
                        <span class="badge badge-primary">求職者</span>
                    @elseif($user->role === 2)
                        <span class="badge badge-success">事業者</span>
                    @elseif($user->role === 9)
                        <span class="badge badge-danger">管理者</span>
                    @endif
                </td>
                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                <td class="admin-actions">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary btn-sm">編集</a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('このユーザーを削除してもよろしいですか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">削除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="empty-message">ユーザーが登録されていません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- モバイル用カード表示 -->
    <div class="mobile-user-card">
        @forelse($users as $user)
        <div class="mobile-user-card-item">
            <div class="mobile-user-card-label">ID</div>
            <div class="mobile-user-card-value">{{ $user->id }}</div>

            <div class="mobile-user-card-label">名前</div>
            <div class="mobile-user-card-value">{{ $user->name }}</div>

            <div class="mobile-user-card-label">メールアドレス</div>
            <div class="mobile-user-card-value">{{ $user->email }}</div>

            <div class="mobile-user-card-label">ロール</div>
            <div class="mobile-user-card-value">
                @if($user->role === 1)
                    <span class="badge badge-primary">求職者</span>
                @elseif($user->role === 2)
                    <span class="badge badge-success">事業者</span>
                @elseif($user->role === 9)
                    <span class="badge badge-danger">管理者</span>
                @endif
            </div>

            <div class="mobile-user-card-label">登録日</div>
            <div class="mobile-user-card-value">{{ $user->created_at->format('Y-m-d') }}</div>

            <div class="mobile-user-card-actions admin-actions">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary">編集</a>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('このユーザーを削除してもよろしいですか？');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger">削除</button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-message">ユーザーが登録されていません。</div>
        @endforelse
    </div>

    <div class="pagination-wrapper">
        {{ $users->links() }}
    </div>
</div>
@endsection
