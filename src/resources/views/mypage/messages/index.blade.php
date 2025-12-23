@extends('layouts.app')

@section('title', 'メッセージ | Bellbi')

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
            <li><a href="{{ route('mypage.scouts.index') }}" class="sidebar-menu-link">スカウト受信</a></li>
            <li><a href="{{ route('mypage.messages.index') }}" class="sidebar-menu-link active">メッセージ</a></li>
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
            .content-wrapper {
                width: 100% !important;
                max-width: 100% !important;
                overflow-x: hidden !important;
                box-sizing: border-box !important;
            }
            .messages-list {
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            .message-item {
                padding: 16px 12px !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            .message-item-inner {
                flex-direction: column !important;
                gap: 12px !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            .message-item-inner > div[style*="flex: 1"] {
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            .message-item-inner > div[style*="min-width: 100px"] {
                width: 100% !important;
                min-width: auto !important;
                max-width: 100% !important;
                align-items: stretch !important;
                box-sizing: border-box !important;
            }
            .message-item-inner > div[style*="min-width: 100px"] > a {
                width: 100% !important;
                max-width: 100% !important;
                text-align: center !important;
                box-sizing: border-box !important;
            }
            .messages-list > div[style*="padding: 40px 24px"] {
                padding: 40px 16px !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
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
    <div class="content-wrapper">
        <div class="page-header">
            <p class="page-label">Messages</p>
            <h1 class="page-title">メッセージ</h1>
            <p class="page-lead">応募・スカウトのあった企業とのメッセージ一覧です。</p>
        </div>

        <div class="messages-list" style="background: #ffffff; border-radius: 0; padding: 0; box-shadow: none; border: none; border-bottom: 1px solid #f0f0f0;">
        @forelse($conversationsWithInfo as $item)
        @php
            $conversation = $item['conversation'];
            $latestMessage = $conversation->latestMessage;
        @endphp
        <div class="message-item" style="
            padding: 20px 24px;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.15s ease;
        " onmouseover="this.style.backgroundColor='#fafafa';" onmouseout="this.style.backgroundColor='transparent';">
            <div class="message-item-inner" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px;">
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        @if($latestMessage && $latestMessage->sender_type === 'company' && $latestMessage->read_flg === 0)
                            <span style="
                                display: inline-block;
                                padding: 5px 12px;
                                border-radius: 4px;
                                font-size: 12px;
                                font-weight: 600;
                                background-color: #3b82f6;
                                color: #ffffff;
                            ">未読</span>
                        @endif
                        @if($latestMessage)
                            <span style="font-size: 12px; color: #999;">{{ $latestMessage->created_at->format('Y年m月d日 H:i') }}</span>
                        @endif
                    </div>
                    <a href="{{ route('mypage.messages.show', $conversation) }}" style="
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
                            {{ $item['title'] }}
                        </h3>
                    </a>
                    <p style="
                        margin: 0 0 12px 0;
                        font-size: 14px;
                        color: #666;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    ">{{ $conversation->company->name }}</p>
                    @if($latestMessage)
                        <div style="display: flex; flex-direction: column; gap: 6px;">
                            <div style="display: flex; align-items: flex-start; gap: 8px;">
                                <span style="font-size: 12px; color: #999; min-width: 60px;">最新メッセージ</span>
                                <span style="font-size: 12px; color: #666; line-height: 1.5;">{{ \Str::limit($latestMessage->body, 80) }}</span>
                            </div>
                        </div>
                    @else
                        <p style="
                            margin: 0;
                            font-size: 12px;
                            color: #999;
                        ">まだメッセージがありません</p>
                    @endif
                </div>
                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px; min-width: 100px;">
                    <a href="{{ route('mypage.messages.show', $conversation) }}" style="
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
                        メッセージを見る
                    </a>
                </div>
            </div>
            </div>
        </div>
        @empty
        <div style="padding: 40px 24px; text-align: center;">
            <p style="margin: 0 0 16px 0; font-size: 14px; color: #666;">まだメッセージはありません。</p>
            <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('mypage') }}" style="
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
                    応募履歴を見る
                </a>
                <a href="{{ route('mypage.scouts.index') }}" style="
                    padding: 10px 24px;
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
                    display: inline-block;
                " onmouseover="this.style.borderColor='#1a1a1a'; this.style.color='#1a1a1a';" onmouseout="this.style.borderColor='#e0e0e0'; this.style.color='#1a1a1a';">
                    スカウト受信を見る
                </a>
            </div>
        </div>
        @endforelse
        </div>
    </div>
@endsection

