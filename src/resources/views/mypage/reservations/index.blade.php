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
        </ul>
    </div>
@endsection

@section('content')
    <div class="page-header">
        <p class="page-label">Reservations</p>
        <h1 class="page-title">予約履歴</h1>
        <p class="page-lead">ご予約いただいた履歴です。</p>
    </div>

    @forelse($reservations as $reservation)
    <div class="job-card" style="margin-bottom: 16px;">
        <div class="job-card-body">
            <span class="job-card-tag">
                @if($reservation->status === 1) 予約確定
                @elseif($reservation->status === 2) 来店済
                @elseif($reservation->status === 3) 店舗キャンセル
                @elseif($reservation->status === 4) キャンセル済
                @elseif($reservation->status === 9) 無断キャンセル
                @endif
            </span>
            <h3 class="job-card-title">
                <a href="{{ route('mypage.reservations.show', $reservation) }}">{{ $reservation->store->name }}</a>
            </h3>
            <p class="job-card-salon">{{ $reservation->store->company->name }}</p>
            <p class="job-card-location" style="margin-top: 8px;">
                予約日時：{{ $reservation->reservation_date->format('Y年m月d日') }} {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}
                @if($reservation->staff)
                    / 担当：{{ $reservation->staff->name }}
                @endif
            </p>
            <p class="job-card-location">
                メニュー：
                @foreach($reservation->reservationMenus as $menu)
                    {{ $menu->menu_name }}@if(!$loop->last), @endif
                @endforeach
            </p>
        </div>
        <div class="job-card-footer">
            <a href="{{ route('mypage.reservations.show', $reservation) }}" class="btn-secondary btn-sm">詳細を見る</a>
        </div>
    </div>
    @empty
    <p class="empty-message">まだ予約がありません。</p>
    <p style="margin-top: 16px;">
        <a href="{{ route('reservations.search') }}" class="btn-primary">店舗を探して予約する</a>
    </p>
    @endforelse
@endsection

