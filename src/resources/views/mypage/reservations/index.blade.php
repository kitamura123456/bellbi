@extends('layouts.app')

@section('title', '予約履歴 | Bellbi')

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
            <li><a href="{{ route('mypage.messages.index') }}" class="sidebar-menu-link">メッセージ</a></li>
            <li><a href="{{ route('mypage.scout-profile.edit') }}" class="sidebar-menu-link">スカウト用プロフィール</a></li>
            <li><a href="{{ route('mypage.reservations.index') }}" class="sidebar-menu-link active">予約履歴</a></li>
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
            .reservation-status-text {
                display: block;
                line-height: 1.2;
            }
            .reservation-item {
                position: relative !important;
                padding: 16px 12px 60px 12px !important;
            }
            .reservation-item-inner {
                flex-direction: column !important;
                gap: 12px !important;
                align-items: center !important;
            }
            .reservation-status-badge {
                display: block !important;
                left: 12px !important;
                right: auto !important;
            }
            .reservation-status-badge-desktop {
                display: none !important;
            }
            .reservation-content {
                text-align: left !important;
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 auto !important;
                padding-left: 80px !important;
                box-sizing: border-box !important;
            }
            .reservation-content > div[style*="display: flex"] {
                justify-content: flex-start !important;
            }
            .reservation-content > div[style*="display: flex"] > span[style*="font-size: 12px"] {
                display: none !important;
            }
            .reservation-content > a,
            .reservation-content > p,
            .reservation-content > div[style*="display: flex; flex-direction: column"] {
                text-align: left !important;
                width: 100% !important;
            }
            .reservation-button-wrapper {
                position: absolute !important;
                bottom: 12px !important;
                right: 12px !important;
                min-width: auto !important;
            }
            .reservation-detail-button {
                padding: 8px 16px !important;
                font-size: 12px !important;
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
        <p class="page-label">Reservations</p>
        <h1 class="page-title">予約履歴</h1>
        <p class="page-lead">ご予約いただいた履歴です。</p>
    </div>

    <div style="background: #ffffff; border-radius: 0; padding: 0; box-shadow: none; border: none; border-bottom: 1px solid #f0f0f0;">
        @forelse($reservations as $reservation)
        <div class="reservation-item" style="
            padding: 20px 24px;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.15s ease;
        " onmouseover="this.style.backgroundColor='#fafafa';" onmouseout="this.style.backgroundColor='transparent';">
            <div class="reservation-item-inner" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px;">
                <div class="reservation-status-badge" style="
                    position: absolute;
                    top: 16px;
                    right: 12px;
                    display: none;
                ">
                    <span style="
                        display: inline-block;
                        padding: 5px 12px;
                        border-radius: 4px;
                        font-size: 12px;
                        font-weight: 600;
                        background-color: {{ $reservation->status === 2 ? '#10b981' : ($reservation->status === 3 || $reservation->status === 9 ? '#dc2626' : '#f5f5f5') }};
                        color: {{ $reservation->status === 2 || $reservation->status === 3 || $reservation->status === 9 ? '#ffffff' : '#1a1a1a' }};
                        border: {{ $reservation->status === 2 || $reservation->status === 3 || $reservation->status === 9 ? 'none' : '1px solid #e0e0e0' }};
                    ">
                        @if($reservation->status === 1) <span class="reservation-status-text">予約</span><span class="reservation-status-text">確定</span>
                        @elseif($reservation->status === 2) 来店済
                        @elseif($reservation->status === 3) 店舗キャンセル
                        @elseif($reservation->status === 4) キャンセル済
                        @elseif($reservation->status === 9) 無断キャンセル
                        @endif
                    </span>
                </div>
                <div class="reservation-content" style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <span class="reservation-status-badge-desktop" style="
                            display: inline-block;
                            padding: 5px 12px;
                            border-radius: 4px;
                            font-size: 12px;
                            font-weight: 600;
                            background-color: {{ $reservation->status === 2 ? '#10b981' : ($reservation->status === 3 || $reservation->status === 9 ? '#dc2626' : '#f5f5f5') }};
                            color: {{ $reservation->status === 2 || $reservation->status === 3 || $reservation->status === 9 ? '#ffffff' : '#1a1a1a' }};
                            border: {{ $reservation->status === 2 || $reservation->status === 3 || $reservation->status === 9 ? 'none' : '1px solid #e0e0e0' }};
                        ">
                            @if($reservation->status === 1) <span class="reservation-status-text">予約</span><span class="reservation-status-text">確定</span>
                            @elseif($reservation->status === 2) 来店済
                            @elseif($reservation->status === 3) 店舗キャンセル
                            @elseif($reservation->status === 4) キャンセル済
                            @elseif($reservation->status === 9) 無断キャンセル
                            @endif
                        </span>
                        <span style="font-size: 12px; color: #999;">予約日：{{ $reservation->reservation_date->format('Y年m月d日') }}</span>
                    </div>
                    <a href="{{ route('mypage.reservations.show', $reservation) }}" style="
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
                            {{ $reservation->store->name }}
                        </h3>
                    </a>
                    <p style="
                        margin: 0 0 12px 0;
                        font-size: 14px;
                        color: #666;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    ">{{ $reservation->store->company->name }}</p>
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 12px; color: #999; min-width: 60px;">予約日時</span>
                            <span style="font-size: 13px; color: #1a1a1a; font-weight: 500;">{{ $reservation->reservation_date->format('Y年m月d日') }} {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}</span>
                        </div>
                        @if($reservation->staff)
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="font-size: 12px; color: #999; min-width: 60px;">担当</span>
                                <span style="font-size: 13px; color: #1a1a1a; font-weight: 500;">{{ $reservation->staff->name }}</span>
                            </div>
                        @endif
                        <div style="display: flex; align-items: flex-start; gap: 8px;">
                            <span style="font-size: 12px; color: #999; min-width: 60px;">メニュー</span>
                            <span style="font-size: 12px; color: #666; line-height: 1.5;">
                                @foreach($reservation->reservationMenus as $menu)
                                    {{ $menu->menu_name }}@if(!$loop->last)、@endif
                                @endforeach
                            </span>
                        </div>
                    </div>
                </div>
                <div class="reservation-button-wrapper" style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px; min-width: 100px;">
                    <a href="{{ route('mypage.reservations.show', $reservation) }}" class="reservation-detail-button" style="
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
            <p style="margin: 0 0 16px 0; font-size: 14px; color: #666;">まだ予約がありません。</p>
            <a href="{{ route('reservations.search') }}" style="
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
                店舗を探して予約する
            </a>
        </div>
        @endforelse
    </div>
@endsection

