@extends('layouts.admin')

@section('title', '求人管理')

@section('content')
<div class="admin-header">
    <h1 class="admin-title">求人管理</h1>
</div>

<div class="admin-card">
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
                    <a href="{{ route('admin.companies.edit', $jobPost->company) }}" style="color: #1e40af; text-decoration: none;">
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

    <div class="pagination-wrapper">
        {{ $jobPosts->links() }}
    </div>
</div>
@endsection

