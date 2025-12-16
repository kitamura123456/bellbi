@extends('layouts.company')

@section('title', 'メッセージ')

@section('content')
<div class="company-header">
    <h1 class="company-title">メッセージ</h1>
    <p class="company-lead">応募者・スカウト送信先とのメッセージ一覧です。</p>
</div>

@forelse($conversationsWithInfo as $item)
@php
    $conversation = $item['conversation'];
    $latestMessage = $conversation->latestMessage;
@endphp
<div class="company-card" style="margin-bottom: 16px;">
    <h3 style="margin-top: 0;">
        <a href="{{ route('company.messages.show', $conversation) }}">{{ $item['title'] }}</a>
    </h3>
    <table class="company-table">
        <tr>
            <th style="width: 150px;">応募者・送信先</th>
            <td>{{ $conversation->user->name }}</td>
        </tr>
        @if($latestMessage)
            <tr>
                <th>最新メッセージ</th>
                <td>
                    <p style="margin: 0;">{{ \Str::limit($latestMessage->body, 100) }}</p>
                    <p style="margin: 4px 0 0 0; font-size: 12px; color: #6b7280;">
                        {{ $latestMessage->created_at->format('Y年m月d日 H:i') }}
                        @if($latestMessage->sender_type === 'user' && $latestMessage->read_flg === 0)
                            <span style="color: #ef4444; margin-left: 8px;">未読</span>
                        @endif
                    </p>
                </td>
            </tr>
        @else
            <tr>
                <th>最新メッセージ</th>
                <td style="color: #6b7280; font-size: 12px;">まだメッセージがありません</td>
            </tr>
        @endif
    </table>
    <div style="margin-top: 16px;">
        <a href="{{ route('company.messages.show', $conversation) }}" class="btn-secondary btn-sm">メッセージを見る</a>
    </div>
</div>
@empty
<div class="company-card">
    <p class="empty-message">まだメッセージはありません。</p>
    <p style="margin-top: 16px;">
        <a href="{{ route('company.applications.index') }}" class="btn-primary">応募管理を見る</a>
        <a href="{{ route('company.scouts.sent') }}" class="btn-secondary" style="margin-left: 8px;">スカウト送信一覧を見る</a>
    </p>
</div>
@endforelse
@endsection

