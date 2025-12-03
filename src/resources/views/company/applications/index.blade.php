@extends('layouts.company')

@section('title', '応募管理')

@section('content')
<div class="company-header">
    <h1 class="company-title">応募管理</h1>
</div>

<div class="company-card">
    <table class="company-table">
        <thead>
            <tr>
                <th>応募日</th>
                <th>求人タイトル</th>
                <th>応募者</th>
                <th>ステータス</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $application)
            <tr>
                <td>{{ $application->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $application->jobPost->title }}</td>
                <td>{{ $application->user->name }}</td>
                <td>
                    @if($application->status === 1)
                        <span class="badge badge-primary">応募済</span>
                    @elseif($application->status === 2)
                        <span class="badge badge-info">書類選考中</span>
                    @elseif($application->status === 3)
                        <span class="badge badge-info">面接中</span>
                    @elseif($application->status === 4)
                        <span class="badge badge-success">内定</span>
                    @elseif($application->status === 5)
                        <span class="badge badge-danger">不採用</span>
                    @elseif($application->status === 9)
                        <span class="badge">キャンセル</span>
                    @endif
                </td>
                <td class="company-actions">
                    <a href="{{ route('company.applications.show', $application) }}" class="btn-secondary btn-sm">詳細</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="empty-message">応募がありません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

