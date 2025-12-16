@extends('layouts.company')

@section('title', 'スカウト詳細')

@section('content')
<div class="company-header">
    <h1 class="company-title">スカウト詳細</h1>
    <div>
        <a href="{{ route('company.messages.create-from-scout', $scout) }}" class="btn-primary" style="margin-right: 8px;">メッセージでやりとりする</a>
        <a href="{{ route('company.scouts.sent') }}" class="btn-secondary">一覧に戻る</a>
    </div>
</div>

<div class="company-card">
    <h3 style="margin-top: 0;">スカウト情報</h3>
    <table class="company-table">
        <tr>
            <th style="width: 150px;">送信日時</th>
            <td>{{ $scout->created_at->format('Y年m月d日 H:i') }}</td>
        </tr>
        <tr>
            <th>送信先</th>
            <td>{{ $scout->toUser->name }}</td>
        </tr>
        <tr>
            <th>件名</th>
            <td>{{ $scout->subject }}</td>
        </tr>
        <tr>
            <th>ステータス</th>
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
        </tr>
    </table>

    <div style="margin-top: 20px;">
        <h4>メッセージ</h4>
        <div style="white-space: pre-wrap; background-color: #f9fafb; padding: 16px; border-radius: 8px; font-size: 14px; line-height: 1.7;">{{ $scout->body }}</div>
    </div>
</div>
@endsection


