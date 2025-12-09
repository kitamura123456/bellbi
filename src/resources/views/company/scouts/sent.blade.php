@extends('layouts.company')

@section('title', '送信済みスカウト')

@section('content')
<div class="company-header">
    <h1 class="company-title">送信済みスカウト</h1>
    <a href="{{ route('company.scouts.search') }}" class="btn-primary">候補者を検索</a>
</div>

<div class="company-card">
    <table class="company-table">
        <thead>
            <tr>
                <th>送信日</th>
                <th>送信先</th>
                <th>件名</th>
                <th>ステータス</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($scouts as $scout)
            <tr>
                <td>{{ $scout->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $scout->toUser->name }}</td>
                <td>{{ $scout->subject }}</td>
                <td>
                    @if($scout->status === 1)
                        <span class="badge badge-primary">送信済</span>
                    @elseif($scout->status === 2)
                        <span class="badge badge-info">既読</span>
                    @elseif($scout->status === 3)
                        <span class="badge badge-success">返信あり</span>
                    @elseif($scout->status === 9)
                        <span class="badge">クローズ</span>
                    @endif
                </td>
                <td class="company-actions">
                    <a href="{{ route('company.scouts.show', $scout) }}" class="btn-secondary btn-sm">詳細</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="empty-message">まだスカウトを送信していません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection


