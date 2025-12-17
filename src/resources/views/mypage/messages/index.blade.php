@extends('layouts.app')

@section('title', 'メッセージ | Bellbi')

@section('sidebar')
    <div class="sidebar-card">
        <h3 class="sidebar-title">メニュー</h3>
        <ul class="sidebar-menu">
            <li><a href="{{ route('mypage') }}" class="sidebar-menu-link">応募履歴</a></li>
            <li><a href="{{ route('mypage.scouts.index') }}" class="sidebar-menu-link">スカウト受信</a></li>
            <li><a href="{{ route('mypage.messages.index') }}" class="sidebar-menu-link active">メッセージ</a></li>
            <li><a href="{{ route('mypage.scout-profile.edit') }}" class="sidebar-menu-link">スカウト用プロフィール</a></li>
            <li><a href="{{ route('mypage.reservations.index') }}" class="sidebar-menu-link">予約履歴</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="page-header">
        <p class="page-label">Messages</p>
        <h1 class="page-title">メッセージ</h1>
        <p class="page-lead">応募・スカウトのあった企業とのメッセージ一覧です。</p>
    </div>

    @forelse($conversationsWithInfo as $item)
    @php
        $conversation = $item['conversation'];
        $latestMessage = $conversation->latestMessage;
    @endphp
    <div class="job-card" style="margin-bottom: 16px;">
        <div class="job-card-body">
            <h3 class="job-card-title">
                <a href="{{ route('mypage.messages.show', $conversation) }}">{{ $item['title'] }}</a>
            </h3>
            <p class="job-card-salon">{{ $conversation->company->name }}</p>
            @if($latestMessage)
                <p class="job-card-location" style="margin-top: 8px;">
                    {{ \Str::limit($latestMessage->body, 80) }}
                </p>
                <p class="job-card-location" style="margin-top: 4px; font-size: 12px; color: #6b7280;">
                    {{ $latestMessage->created_at->format('Y年m月d日 H:i') }}
                    @if($latestMessage->sender_type === 'company' && $latestMessage->read_flg === 0)
                        <span style="color: #ef4444; margin-left: 8px;">未読</span>
                    @endif
                </p>
            @else
                <p class="job-card-location" style="margin-top: 8px; font-size: 12px; color: #6b7280;">
                    まだメッセージがありません
                </p>
            @endif
        </div>
        <div class="job-card-footer">
            <a href="{{ route('mypage.messages.show', $conversation) }}" style="
                padding: 8px 16px;
                background: transparent;
                color: #5D535E;
                border: 1px solid #5D535E;
                border-radius: 20px;
                font-size: 13px;
                font-weight: 700;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.2s ease;
                display: inline-block;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                メッセージを見る
            </a>
        </div>
    </div>
    @empty
    <p class="empty-message">まだメッセージはありません。</p>
    <p style="margin-top: 16px;">
        <a href="{{ route('mypage') }}" style="
            padding: 12px 32px;
            background: #5D535E;
            color: #ffffff;
            border: none;
            border-radius: 24px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            display: inline-block;
        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
            応募履歴を見る
        </a>
        <a href="{{ route('mypage.scouts.index') }}" style="
            padding: 12px 24px;
            background: transparent;
            color: #5D535E;
            border: 1px solid #5D535E;
            border-radius: 24px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            display: inline-block;
            margin-left: 8px;
        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
            スカウト受信を見る
        </a>
    </p>
    @endforelse
@endsection

