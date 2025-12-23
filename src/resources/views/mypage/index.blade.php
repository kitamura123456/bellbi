@extends('layouts.app')

@section('title', 'マイページ | Bellbi')

@section('sidebar')
<div class="sidebar-card">
    <div class="mypage-menu-header" style="display: flex; align-items: center; justify-content: space-between; cursor: pointer;" onclick="if(window.innerWidth <= 768) toggleMypageMenu()">
        <h3 class="sidebar-title" style="margin: 0;">メニュー</h3>
        <span class="mypage-toggle-icon" style="
            display: none;
            font-size: 16px;
            color: #1a1a1a;
            transition: transform 0.3s ease;
            user-select: none;
            flex-shrink: 0;
            margin-left: 8px;
        ">▼</span>
    </div>
    <ul class="sidebar-menu mypage-menu-list" id="mypageMenuList">
        <li><a href="{{ route('mypage') }}" class="sidebar-menu-link active">応募履歴</a></li>
        <li><a href="{{ route('mypage.scouts.index') }}" class="sidebar-menu-link">スカウト受信</a></li>
        <li><a href="{{ route('mypage.messages.index') }}" class="sidebar-menu-link">メッセージ</a></li>
        <li><a href="{{ route('mypage.scout-profile.edit') }}" class="sidebar-menu-link">スカウト用プロフィール</a></li>
        <li><a href="{{ route('mypage.reservations.index') }}" class="sidebar-menu-link">予約履歴</a></li>
        <li><a href="{{ route('mypage.orders.index') }}" class="sidebar-menu-link">注文履歴</a></li>
    </ul>
</div>
<style>
    /* デスクトップ版の固定メニュー */
    .sidebar {
        position: sticky !important;
        top: 0 !important;
        align-self: flex-start !important;
        z-index: 40 !important;
        max-height: 100vh !important;
        overflow-y: auto !important;
    }
    .sidebar-card {
        position: sticky !important;
        top: 0 !important;
    }
    .sidebar-menu,
    .mypage-menu-list {
        position: relative !important;
    }
    
    /* スマホ版の折りたたみ機能 */
    @media (max-width: 768px) {
        .sidebar {
            position: sticky !important;
            top: 0 !important;
            z-index: 50 !important;
            background: #ffffff !important;
            margin-bottom: 0 !important;
        }
        .sidebar-card {
            position: sticky !important;
            top: 0 !important;
            z-index: 50 !important;
            background: #ffffff !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05) !important;
            margin-bottom: 0 !important;
            padding: 8px 12px !important;
        }
        .sidebar-menu,
        .mypage-menu-list {
            position: relative !important;
        }
        .mypage-menu-header {
            padding: 4px 0 !important;
            margin-bottom: 0 !important;
        }
        .sidebar-title {
            font-size: 11px !important;
            margin-bottom: 0 !important;
        }
        .mypage-toggle-icon {
            display: block !important;
            font-size: 14px !important;
        }
        .mypage-menu-list {
            display: none;
            margin-top: 8px;
        }
        .mypage-menu-list.active {
            display: block !important;
        }
        .mypage-toggle-icon.active {
            transform: rotate(180deg);
        }
        .container.main-inner {
            flex-direction: column !important;
        }
        .sidebar {
            order: -1 !important;
        }
        .page-header {
            margin-top: 24px !important;
        }
    }
</style>
<script>
    function toggleMypageMenu() {
        const menu = document.getElementById('mypageMenuList');
        const icon = document.querySelector('.mypage-toggle-icon');
        
        if (menu && icon) {
            menu.classList.toggle('active');
            icon.classList.toggle('active');
        }
    }
</script>
    @if($upcomingInterviews->isNotEmpty())
    <div class="sidebar-card" style="margin-top: 16px;">
        <h3 class="sidebar-title">面接予定</h3>
        @foreach($upcomingInterviews as $interview)
        <div style="
            padding: 12px;
            margin-bottom: 8px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        ">
            <p style="
                margin: 0 0 4px 0;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                {{ $interview->interview_date->format('Y年m月d日') }}
            </p>
            <p style="
                margin: 0 0 4px 0;
                font-size: 12px;
                color: #6b7280;
            ">
                {{ $interview->jobPost->company->name }}
            </p>
            <a href="{{ route('jobs.show', $interview->jobPost) }}" style="
                font-size: 12px;
                color: #90AFC5;
                text-decoration: none;
                transition: color 0.2s ease;
            " onmouseover="this.style.color='#7FB3D3'; this.style.textDecoration='underline';" onmouseout="this.style.color='#90AFC5'; this.style.textDecoration='none';">
                求人詳細を見る →
            </a>
        </div>
        @endforeach
    </div>
    @endif
@endsection

@section('content')
    <div class="page-header">
        <p class="page-label">My Page</p>
        <h1 class="page-title">応募履歴</h1>
        <p class="page-lead">あなたが応募した求人の一覧です。</p>
    </div>

    <div style="background: #ffffff; border-radius: 0; padding: 0; box-shadow: none; border: none; border-bottom: 1px solid #f0f0f0;">
        @forelse($applications as $application)
        <div style="
            padding: 20px 24px;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.15s ease;
        " onmouseover="this.style.backgroundColor='#fafafa';" onmouseout="this.style.backgroundColor='transparent';">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px;">
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <span style="
                            display: inline-block;
                            padding: 5px 12px;
                            border-radius: 4px;
                            font-size: 12px;
                            font-weight: 600;
                            background-color: {{ $application->status === 3 ? '#3b82f6' : ($application->status === 4 ? '#10b981' : ($application->status === 5 ? '#6b7280' : '#f5f5f5')) }};
                            color: {{ $application->status === 3 || $application->status === 4 || $application->status === 5 ? '#ffffff' : '#1a1a1a' }};
                            border: {{ $application->status === 3 || $application->status === 4 || $application->status === 5 ? 'none' : '1px solid #e0e0e0' }};
                        ">
                            @if($application->status === 1) 応募済
                            @elseif($application->status === 2) 書類選考中
                            @elseif($application->status === 3) 面接中
                            @elseif($application->status === 4) 内定
                            @elseif($application->status === 5) 不採用
                            @elseif($application->status === 9) キャンセル
                            @endif
                        </span>
                        <span style="font-size: 12px; color: #999;">応募日：{{ $application->created_at->format('Y年m月d日') }}</span>
                    </div>
                    <a href="{{ route('jobs.show', $application->jobPost) }}" style="
                        display: block;
                        margin-bottom: 8px;
                        text-decoration: none;
                        color: #1a1a1a;
                    ">
                        <h3 style="
                            margin: 0 0 8px 0;
                            font-size: 18px;
                            font-weight: 600;
                            color: #1a1a1a;
                            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            transition: color 0.15s ease;
                        " onmouseover="this.style.color='#333333';" onmouseout="this.style.color='#1a1a1a';">
                            {{ $application->jobPost->title }}
                        </h3>
                    </a>
                    <p style="
                        margin: 0 0 12px 0;
                        font-size: 14px;
                        color: #666;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    ">{{ $application->jobPost->company->name }}</p>
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        @if($application->interview_date)
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="font-size: 12px; color: #999; min-width: 60px;">面接日</span>
                                <span style="font-size: 13px; color: #1a1a1a; font-weight: 500;">{{ $application->interview_date->format('Y年m月d日') }}</span>
                            </div>
                        @endif
                        @if($application->message)
                            <div style="display: flex; align-items: flex-start; gap: 8px;">
                                <span style="font-size: 12px; color: #999; min-width: 60px;">メッセージ</span>
                                <span style="font-size: 12px; color: #666; line-height: 1.5;">{{ \Str::limit($application->message, 80) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px; min-width: 100px;">
                    <a href="{{ route('mypage.messages.create-from-application', $application) }}" style="
                        padding: 8px 16px;
                        background: #1a1a1a;
                        color: #ffffff;
                        border: none;
                        border-radius: 4px;
                        font-size: 13px;
                        font-weight: 500;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        text-decoration: none;
                        cursor: pointer;
                        transition: all 0.15s ease;
                        white-space: nowrap;
                        text-align: center;
                    " onmouseover="this.style.backgroundColor='#333333';" onmouseout="this.style.backgroundColor='#1a1a1a';">
                        メッセージ
                    </a>
                    <a href="{{ route('jobs.show', $application->jobPost) }}" style="
                        padding: 8px 16px;
                        background: transparent;
                        color: #1a1a1a;
                        border: 1px solid #e0e0e0;
                        border-radius: 4px;
                        font-size: 13px;
                        font-weight: 500;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        text-decoration: none;
                        cursor: pointer;
                        transition: all 0.15s ease;
                        white-space: nowrap;
                        text-align: center;
                    " onmouseover="this.style.backgroundColor='#f5f5f5'; this.style.borderColor='#1a1a1a';" onmouseout="this.style.backgroundColor='transparent'; this.style.borderColor='#e0e0e0';">
                        詳細を見る
                    </a>
                </div>
            </div>
        </div>
            @empty
        <div style="padding: 40px 24px; text-align: center;">
            <p style="margin: 0 0 16px 0; font-size: 14px; color: #666;">まだ応募した求人はありません。</p>
            <a href="{{ route('jobs.index') }}" style="
                padding: 10px 24px;
                background: #1a1a1a;
                color: #ffffff;
                border: none;
                border-radius: 4px;
                font-size: 13px;
                font-weight: 500;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.15s ease;
                display: inline-block;
            " onmouseover="this.style.backgroundColor='#333333';" onmouseout="this.style.backgroundColor='#1a1a1a';">
                求人を探す
            </a>
        </div>
        @endforelse
    </div>
@endsection
