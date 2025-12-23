@extends('layouts.app')

@section('title', 'スカウト受信 | Bellbi')

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
            <li><a href="{{ route('mypage') }}" class="sidebar-menu-link">応募履歴</a></li>
            <li><a href="{{ route('mypage.scouts.index') }}" class="sidebar-menu-link active">スカウト受信</a></li>
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
@endsection

@section('content')
    <div class="page-header">
        <p class="page-label">Scout Messages</p>
        <h1 class="page-title">スカウト受信</h1>
        <p class="page-lead">企業から届いたスカウトメッセージ一覧です。</p>
    </div>

    <div style="background: #ffffff; border-radius: 0; padding: 0; box-shadow: none; border: none; border-bottom: 1px solid #f0f0f0;">
        @forelse($scouts as $scout)
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
                            background-color: {{ $scout->status === 1 ? '#3b82f6' : ($scout->status === 3 ? '#10b981' : '#f5f5f5') }};
                            color: {{ $scout->status === 1 || $scout->status === 3 ? '#ffffff' : '#1a1a1a' }};
                            border: {{ $scout->status === 1 || $scout->status === 3 ? 'none' : '1px solid #e0e0e0' }};
                        ">
                            @if($scout->status === 1) 未読
                            @elseif($scout->status === 2) 既読
                            @elseif($scout->status === 3) 返信済
                            @elseif($scout->status === 9) クローズ
                            @endif
                        </span>
                        <span style="font-size: 12px; color: #999;">受信日：{{ $scout->created_at->format('Y年m月d日') }}</span>
                    </div>
                    <a href="{{ route('mypage.scouts.show', $scout) }}" style="
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
                            {{ $scout->subject }}
                        </h3>
                    </a>
                    <p style="
                        margin: 0 0 12px 0;
                        font-size: 14px;
                        color: #666;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    ">
                        {{ $scout->fromCompany->name }}
                        @if($scout->fromStore)
                            <span style="color: #999; margin: 0 4px;">/</span>{{ $scout->fromStore->name }}
                        @endif
                    </p>
                </div>
                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px; min-width: 100px;">
                    <a href="{{ route('mypage.scouts.show', $scout) }}" style="
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
                        詳細を見る
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div style="padding: 40px 24px; text-align: center;">
            <p style="margin: 0 0 16px 0; font-size: 14px; color: #666;">まだスカウトは届いていません。</p>
            <a href="{{ route('mypage.scout-profile.edit') }}" style="
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
                スカウト用プロフィールを設定する
            </a>
        </div>
        @endforelse
    </div>
@endsection


