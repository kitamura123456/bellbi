@extends('layouts.app')

@section('title', '予約詳細 | Bellbi')

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
        <h1 class="page-title">予約詳細</h1>
        <p class="page-lead">
            <a href="{{ route('mypage.reservations.index') }}" style="
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
                display: inline-block;
            " onmouseover="this.style.backgroundColor='#333333';" onmouseout="this.style.backgroundColor='#1a1a1a';">
                一覧に戻る
            </a>
        </p>
    </div>

    <div class="job-detail-card">
        <h3 style="margin-top: 0;">予約情報</h3>
        <table class="company-table">
            <tr>
                <th style="width: 150px;">店舗名</th>
                <td>{{ $reservation->store->name }}<br>
                    <span style="font-size: 13px; color: #6b7280;">{{ $reservation->store->company->name }}</span>
                </td>
            </tr>
            <tr>
                <th>予約日</th>
                <td>{{ $reservation->reservation_date->format('Y年m月d日') }}</td>
            </tr>
            <tr>
                <th>予約時間</th>
                <td>{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}</td>
            </tr>
            <tr>
                <th>担当スタッフ</th>
                <td>{{ $reservation->staff ? $reservation->staff->name : '指名なし' }}</td>
            </tr>
            <tr>
                <th>ステータス</th>
                <td>
                    @if($reservation->status === 1)
                        <span class="badge badge-primary">予約確定</span>
                    @elseif($reservation->status === 2)
                        <span class="badge badge-success">来店済</span>
                    @elseif($reservation->status === 3)
                        <span class="badge">店舗キャンセル</span>
                    @elseif($reservation->status === 4)
                        <span class="badge">キャンセル済</span>
                    @elseif($reservation->status === 9)
                        <span class="badge badge-danger">無断キャンセル</span>
                    @endif
                </td>
            </tr>
        </table>

        <h3 style="margin-top: 24px;">予約メニュー</h3>
        <table class="company-table">
            <thead>
                <tr>
                    <th>メニュー名</th>
                    <th>所要時間</th>
                    <th>料金</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservation->reservationMenus as $menu)
                <tr>
                    <td>{{ $menu->menu_name }}</td>
                    <td>{{ $menu->duration_minutes }}分</td>
                    <td>{{ number_format($menu->price) }}円</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold;">
                    <td>合計</td>
                    <td>{{ $reservation->total_duration_minutes }}分</td>
                    <td>{{ number_format($reservation->total_price) }}円</td>
                </tr>
            </tbody>
        </table>

        @if($reservation->customer_note)
        <h3 style="margin-top: 24px;">ご要望</h3>
        <div style="white-space: pre-wrap; background-color: #f9fafb; padding: 16px; border-radius: 8px; font-size: 14px; line-height: 1.7;">{{ $reservation->customer_note }}</div>
        @endif
    </div>

    @if($canCancel)
    <div class="job-detail-card">
        <h3 style="margin-top: 0;">予約のキャンセル</h3>
        <p style="font-size: 13px; color: #6b7280; margin-bottom: 16px;">
            この予約をキャンセルする場合は、以下のボタンからキャンセル処理を行ってください。
        </p>
        <form action="{{ route('mypage.reservations.cancel', $reservation) }}" method="POST" onsubmit="return confirm('予約をキャンセルしてもよろしいですか？')">
            @csrf
            <button type="submit" style="
                padding: 8px 16px;
                background: #dc2626;
                color: #ffffff;
                border: none;
                border-radius: 4px;
                font-size: 13px;
                font-weight: 500;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                cursor: pointer;
                transition: all 0.15s ease;
            " onmouseover="this.style.backgroundColor='#b91c1c';" onmouseout="this.style.backgroundColor='#dc2626';">
                予約をキャンセルする
            </button>
        </form>
    </div>
    @elseif($reservation->status === 1)
    <div class="job-detail-card">
        <p style="font-size: 13px; color: #dc2626;">
            キャンセル期限を過ぎています。キャンセルをご希望の場合は、店舗に直接ご連絡ください。
        </p>
    </div>
    @endif
@endsection

