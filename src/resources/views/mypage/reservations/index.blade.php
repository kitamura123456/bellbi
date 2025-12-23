@extends('layouts.app')

@section('title', '予約履歴 | Bellbi')

@section('sidebar')
    <div class="sidebar-card">
        <h3 class="sidebar-title">メニュー</h3>
        <ul class="sidebar-menu">
            <li><a href="{{ route('mypage') }}" class="sidebar-menu-link">応募履歴</a></li>
            <li><a href="{{ route('mypage.scouts.index') }}" class="sidebar-menu-link">スカウト受信</a></li>
            <li><a href="{{ route('mypage.messages.index') }}" class="sidebar-menu-link">メッセージ</a></li>
            <li><a href="{{ route('mypage.scout-profile.edit') }}" class="sidebar-menu-link">スカウト用プロフィール</a></li>
            <li><a href="{{ route('mypage.reservations.index') }}" class="sidebar-menu-link active">予約履歴</a></li>
            <li><a href="{{ route('mypage.orders.index') }}" class="sidebar-menu-link">注文履歴</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="page-header">
        <p class="page-label">Reservations</p>
        <h1 class="page-title">予約履歴</h1>
        <p class="page-lead">ご予約いただいた履歴です。</p>
    </div>

    <div style="background: #ffffff; border-radius: 0; padding: 0; box-shadow: none; border: none; border-bottom: 1px solid #f0f0f0;">
        @forelse($reservations as $reservation)
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
                            background-color: {{ $reservation->status === 2 ? '#10b981' : ($reservation->status === 3 || $reservation->status === 9 ? '#dc2626' : '#f5f5f5') }};
                            color: {{ $reservation->status === 2 || $reservation->status === 3 || $reservation->status === 9 ? '#ffffff' : '#1a1a1a' }};
                            border: {{ $reservation->status === 2 || $reservation->status === 3 || $reservation->status === 9 ? 'none' : '1px solid #e0e0e0' }};
                        ">
                            @if($reservation->status === 1) 予約確定
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
                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px; min-width: 100px;">
                    <a href="{{ route('mypage.reservations.show', $reservation) }}" style="
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

