@extends('layouts.app')

@section('title', 'マイページ | Bellbi')

@section('sidebar')
    <div class="sidebar-card">
        <h3 class="sidebar-title">メニュー</h3>
        <ul class="sidebar-menu">
            <li><a href="{{ route('mypage') }}" class="sidebar-menu-link active">応募履歴</a></li>
            <li><a href="{{ route('mypage.scouts.index') }}" class="sidebar-menu-link">スカウト受信</a></li>
            <li><a href="{{ route('mypage.messages.index') }}" class="sidebar-menu-link">メッセージ</a></li>
            <li><a href="{{ route('mypage.scout-profile.edit') }}" class="sidebar-menu-link">スカウト用プロフィール</a></li>
            <li><a href="{{ route('mypage.reservations.index') }}" class="sidebar-menu-link">予約履歴</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="page-header">
        <p class="page-label">My Page</p>
        <h1 class="page-title">応募履歴</h1>
        <p class="page-lead">あなたが応募した求人の一覧です。</p>
    </div>

    @forelse($applications as $application)
    <div class="job-card" style="margin-bottom: 16px;">
        <div class="job-card-body">
            <span class="job-card-tag">
                @if($application->status === 1) 応募済
                @elseif($application->status === 2) 書類選考中
                @elseif($application->status === 3) 面接中
                @elseif($application->status === 4) 内定
                @elseif($application->status === 5) 不採用
                @elseif($application->status === 9) キャンセル
                @endif
            </span>
            <h3 class="job-card-title">
                <a href="{{ route('jobs.show', $application->jobPost) }}">{{ $application->jobPost->title }}</a>
            </h3>
            <p class="job-card-salon">{{ $application->jobPost->company->name }}</p>
            <p class="job-card-location" style="margin-top: 8px;">応募日：{{ $application->created_at->format('Y年m月d日') }}</p>
            @if($application->message)
                <p class="job-card-location" style="margin-top: 4px; font-size: 12px; color: #6b7280;">
                    メッセージ：{{ \Str::limit($application->message, 50) }}
                </p>
            @endif
        </div>
        <div class="job-card-footer">
            <a href="{{ route('mypage.messages.create-from-application', $application) }}" style="
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
                メッセージを送る
            </a>
        </div>
    </div>
    @empty
    <p class="empty-message">まだ応募した求人はありません。</p>
    <p style="margin-top: 16px;">
        <a href="{{ route('jobs.index') }}" style="
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
            求人を探す
        </a>
    </p>
    @endforelse
@endsection
