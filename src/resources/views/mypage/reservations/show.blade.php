@extends('layouts.app')

@section('title', '予約詳細 | Bellbi')

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
        <h1 class="page-title">予約詳細</h1>
        <p class="page-lead">
            <a href="{{ route('mypage.reservations.index') }}" class="btn-secondary btn-sm">一覧に戻る</a>
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
            <button type="submit" class="btn-danger">予約をキャンセルする</button>
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

