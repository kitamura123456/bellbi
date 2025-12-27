@extends('layouts.admin')

@section('title', '求人管理')

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

    .company-link {
        color: #1e40af;
        text-decoration: none;
    }

    .company-link:hover {
        text-decoration: underline;
    }

    /* モバイル用カード表示 */
    .mobile-job-post-card {
        display: none;
    }

    .mobile-job-post-card-item {
        padding: 12px 0;
        border-bottom: 1px solid #e8e8e8;
    }

    .mobile-job-post-card-item:last-child {
        border-bottom: none;
    }

    .mobile-job-post-card-label {
        font-size: 11px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .mobile-job-post-card-value {
        font-size: 14px;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .mobile-job-post-card-actions {
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

        .mobile-job-post-card {
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

        .mobile-job-post-card-item {
            padding: 10px 0;
        }

        .mobile-job-post-card-label {
            font-size: 10px;
        }

        .mobile-job-post-card-value {
            font-size: 13px;
        }
    }
</style>

<div class="admin-header">
    <h1 class="admin-title">求人管理</h1>
</div>

<div class="admin-card">
    <!-- デスクトップ用テーブル -->
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>求人タイトル</th>
                <th>事業者</th>
                <th>職種</th>
                <th>雇用形態</th>
                <th>ステータス</th>
                <th>登録日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobPosts as $jobPost)
            <tr>
                <td>{{ $jobPost->id }}</td>
                <td>{{ Str::limit($jobPost->title, 50) }}</td>
                <td>
                    <a href="{{ route('admin.companies.edit', $jobPost->company) }}" class="company-link">
                        {{ $jobPost->company->name }}
                    </a>
                </td>
                <td>
                    @if($jobPost->job_category === 1) スタイリスト
                    @elseif($jobPost->job_category === 2) アシスタント
                    @elseif($jobPost->job_category === 3) エステティシャン
                    @elseif($jobPost->job_category === 4) 看護師
                    @elseif($jobPost->job_category === 5) 歯科衛生士
                    @elseif($jobPost->job_category === 99) その他
                    @else 未設定
                    @endif
                </td>
                <td>
                    @if($jobPost->employment_type === 1) 正社員
                    @elseif($jobPost->employment_type === 2) パート・アルバイト
                    @elseif($jobPost->employment_type === 3) 業務委託
                    @elseif($jobPost->employment_type === 4) 契約社員
                    @else その他
                    @endif
                </td>
                <td>
                    @if($jobPost->status === 0)
                        <span class="badge" style="background-color: #94a3b8; color: #ffffff;">下書き</span>
                    @elseif($jobPost->status === 1)
                        <span class="badge badge-success">公開中</span>
                    @elseif($jobPost->status === 2)
                        <span class="badge badge-danger">停止</span>
                    @endif
                </td>
                <td>{{ $jobPost->created_at->format('Y-m-d') }}</td>
                <td class="admin-actions">
                    <a href="{{ route('jobs.show', $jobPost) }}" target="_blank" class="btn-secondary btn-sm">表示</a>
                    <a href="{{ route('admin.job-posts.edit', $jobPost) }}" class="btn-secondary btn-sm">編集</a>
                    <form action="{{ route('admin.job-posts.destroy', $jobPost) }}" method="POST" style="display:inline;" onsubmit="return confirm('この求人を削除してもよろしいですか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">削除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="empty-message">求人が登録されていません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- モバイル用カード表示 -->
    <div class="mobile-job-post-card">
        @forelse($jobPosts as $jobPost)
        <div class="mobile-job-post-card-item">
            <div class="mobile-job-post-card-label">ID</div>
            <div class="mobile-job-post-card-value">{{ $jobPost->id }}</div>

            <div class="mobile-job-post-card-label">求人タイトル</div>
            <div class="mobile-job-post-card-value">{{ $jobPost->title }}</div>

            <div class="mobile-job-post-card-label">事業者</div>
            <div class="mobile-job-post-card-value">
                <a href="{{ route('admin.companies.edit', $jobPost->company) }}" class="company-link">
                    {{ $jobPost->company->name }}
                </a>
            </div>

            <div class="mobile-job-post-card-label">職種</div>
            <div class="mobile-job-post-card-value">
                @if($jobPost->job_category === 1) スタイリスト
                @elseif($jobPost->job_category === 2) アシスタント
                @elseif($jobPost->job_category === 3) エステティシャン
                @elseif($jobPost->job_category === 4) 看護師
                @elseif($jobPost->job_category === 5) 歯科衛生士
                @elseif($jobPost->job_category === 99) その他
                @else 未設定
                @endif
            </div>

            <div class="mobile-job-post-card-label">雇用形態</div>
            <div class="mobile-job-post-card-value">
                @if($jobPost->employment_type === 1) 正社員
                @elseif($jobPost->employment_type === 2) パート・アルバイト
                @elseif($jobPost->employment_type === 3) 業務委託
                @elseif($jobPost->employment_type === 4) 契約社員
                @else その他
                @endif
            </div>

            <div class="mobile-job-post-card-label">ステータス</div>
            <div class="mobile-job-post-card-value">
                @if($jobPost->status === 0)
                    <span class="badge" style="background-color: #94a3b8; color: #ffffff;">下書き</span>
                @elseif($jobPost->status === 1)
                    <span class="badge badge-success">公開中</span>
                @elseif($jobPost->status === 2)
                    <span class="badge badge-danger">停止</span>
                @endif
            </div>

            <div class="mobile-job-post-card-label">登録日</div>
            <div class="mobile-job-post-card-value">{{ $jobPost->created_at->format('Y-m-d') }}</div>

            <div class="mobile-job-post-card-actions admin-actions">
                <a href="{{ route('jobs.show', $jobPost) }}" target="_blank" class="btn-secondary">表示</a>
                <a href="{{ route('admin.job-posts.edit', $jobPost) }}" class="btn-secondary">編集</a>
                <form action="{{ route('admin.job-posts.destroy', $jobPost) }}" method="POST" style="display:inline;" onsubmit="return confirm('この求人を削除してもよろしいですか？');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger">削除</button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-message">求人が登録されていません。</div>
        @endforelse
    </div>

    <div class="pagination-wrapper">
        {{ $jobPosts->links() }}
    </div>
</div>
@endsection
