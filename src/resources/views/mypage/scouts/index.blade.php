@extends('layouts.app')

@section('title', 'スカウト受信 | Bellbi')

@section('sidebar')
    <div class="sidebar-card">
        <h3 class="sidebar-title">メニュー</h3>
        <ul class="sidebar-menu">
            <li><a href="{{ route('mypage') }}" class="sidebar-menu-link">応募履歴</a></li>
            <li><a href="{{ route('mypage.scouts.index') }}" class="sidebar-menu-link active">スカウト受信</a></li>
            <li><a href="{{ route('mypage.messages.index') }}" class="sidebar-menu-link">メッセージ</a></li>
            <li><a href="{{ route('mypage.scout-profile.edit') }}" class="sidebar-menu-link">スカウト用プロフィール</a></li>
            <li><a href="{{ route('mypage.reservations.index') }}" class="sidebar-menu-link">予約履歴</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="page-header">
        <p class="page-label">Scout Messages</p>
        <h1 class="page-title">スカウト受信</h1>
        <p class="page-lead">企業から届いたスカウトメッセージ一覧です。</p>
    </div>

    @forelse($scouts as $scout)
    <div class="job-card" style="margin-bottom: 16px;">
        <div class="job-card-body">
            <span class="job-card-tag">
                @if($scout->status === 1) 未読
                @elseif($scout->status === 2) 既読
                @elseif($scout->status === 3) 返信済
                @elseif($scout->status === 9) クローズ
                @endif
            </span>
            <h3 class="job-card-title">
                <a href="{{ route('mypage.scouts.show', $scout) }}">{{ $scout->subject }}</a>
            </h3>
            <p class="job-card-salon">{{ $scout->fromCompany->name }}
                @if($scout->fromStore)
                    <span class="job-card-separator">/</span>{{ $scout->fromStore->name }}
                @endif
            </p>
            <p class="job-card-location" style="margin-top: 8px;">受信日：{{ $scout->created_at->format('Y年m月d日') }}</p>
        </div>
        <div class="job-card-footer">
            <a href="{{ route('mypage.scouts.show', $scout) }}" style="
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
                詳細を見る
            </a>
        </div>
    </div>
    @empty
    <p class="empty-message">まだスカウトは届いていません。</p>
    <p style="margin-top: 16px;">
        <a href="{{ route('mypage.scout-profile.edit') }}" style="
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
            スカウト用プロフィールを設定する
        </a>
    </p>
    @endforelse
@endsection


