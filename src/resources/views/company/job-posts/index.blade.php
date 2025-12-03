@extends('layouts.company')

@section('title', '求人管理')

@section('content')
<div class="company-header">
    <h1 class="company-title">求人管理</h1>
    <a href="{{ route('company.job-posts.create') }}" class="btn-primary">新規求人作成</a>
</div>

<div class="company-card">
    <table class="company-table">
        <thead>
            <tr>
                <th>求人タイトル</th>
                <th>ステータス</th>
                <th>雇用形態</th>
                <th>給与</th>
                <th>作成日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobPosts as $jobPost)
            <tr>
                <td>{{ $jobPost->title }}</td>
                <td>
                    @if($jobPost->status === 0)
                        <span class="badge badge-secondary">下書き</span>
                    @elseif($jobPost->status === 1)
                        <span class="badge badge-success">公開中</span>
                    @elseif($jobPost->status === 2)
                        <span class="badge badge-danger">停止</span>
                    @endif
                </td>
                <td>
                    @if($jobPost->employment_type === 1) 正社員
                    @elseif($jobPost->employment_type === 2) パート
                    @elseif($jobPost->employment_type === 3) 業務委託
                    @else その他
                    @endif
                </td>
                <td>
                    @if($jobPost->min_salary && $jobPost->max_salary)
                        {{ number_format($jobPost->min_salary) }}円〜{{ number_format($jobPost->max_salary) }}円
                    @elseif($jobPost->min_salary)
                        {{ number_format($jobPost->min_salary) }}円〜
                    @else
                        応相談
                    @endif
                </td>
                <td>{{ $jobPost->created_at->format('Y-m-d') }}</td>
                <td class="company-actions">
                    <a href="{{ route('jobs.show', $jobPost) }}" class="btn-secondary btn-sm" target="_blank">表示</a>
                    <a href="{{ route('company.job-posts.edit', $jobPost) }}" class="btn-secondary btn-sm">編集</a>
                    <form action="{{ route('company.job-posts.destroy', $jobPost) }}" method="POST" style="display:inline;" onsubmit="return confirm('この求人を削除してもよろしいですか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">削除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="empty-message">求人が登録されていません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

