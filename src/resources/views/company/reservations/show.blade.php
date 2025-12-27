@extends('layouts.company')

@section('title', '予約詳細')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">予約詳細</h1>
    <a href="{{ route('company.reservations.index') }}" style="
        padding: 12px 24px;
        background: transparent;
        color: #5D535E;
        border: 1px solid #5D535E;
        border-radius: 24px;
        font-size: 14px;
        font-weight: 700;
        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
        一覧に戻る
    </a>
</div>

<div class="company-card">
    <h3 style="margin-top: 0;">予約情報</h3>
    <table class="company-table">
        <tr>
            <th style="width: 150px;">予約日</th>
            <td>{{ $reservation->reservation_date->format('Y年m月d日') }}</td>
        </tr>
        <tr>
            <th>予約時間</th>
            <td>{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }} ({{ $reservation->total_duration_minutes }}分)</td>
        </tr>
        <tr>
            <th>店舗</th>
            <td>{{ $reservation->store->name }}</td>
        </tr>
        <tr>
            <th>担当スタッフ</th>
            <td>{{ $reservation->staff ? $reservation->staff->name : '指名なし' }}</td>
        </tr>
        <tr>
            <th>顧客名</th>
            <td>{{ $reservation->user->name }}</td>
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
                    <span class="badge">顧客キャンセル</span>
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
    <h3 style="margin-top: 24px;">顧客からのメモ</h3>
    <div style="white-space: pre-wrap; background-color: #f9fafb; padding: 16px; border-radius: 8px; font-size: 14px; line-height: 1.7;">{{ $reservation->customer_note }}</div>
    @endif

    @if($reservation->store_note)
    <h3 style="margin-top: 24px;">店舗メモ</h3>
    <div style="white-space: pre-wrap; background-color: #f9fafb; padding: 16px; border-radius: 8px; font-size: 14px; line-height: 1.7;">{{ $reservation->store_note }}</div>
    @endif
</div>

@if($reservation->status === 1)
<div class="company-card">
    <h3 style="margin-top: 0;">ステータス変更</h3>
    <form action="{{ route('company.reservations.update-status', $reservation) }}" method="POST" class="company-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="status">ステータス <span class="required">必須</span></label>
            <select id="status" name="status" required>
                <option value="1" {{ $reservation->status === 1 ? 'selected' : '' }}>予約確定</option>
                <option value="2">来店済</option>
                <option value="3">店舗キャンセル</option>
                <option value="9">無断キャンセル</option>
            </select>
            @error('status')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="store_note">店舗メモ</label>
            <textarea id="store_note" name="store_note" placeholder="店舗側のメモを入力">{{ old('store_note', $reservation->store_note) }}</textarea>
            @error('store_note')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" style="
                padding: 12px 32px;
                background: #5D535E;
                color: #ffffff;
                border: none;
                border-radius: 24px;
                font-size: 14px;
                font-weight: 700;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                cursor: pointer;
                transition: all 0.2s ease;
                position: relative;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                更新する
            </button>
        </div>
    </form>
</div>
@endif

<style>
/* スマホ用レスポンシブデザイン */
@media (max-width: 768px) {
    div[style*="margin-bottom: 24px; display: flex"] {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 12px !important;
    }

    div[style*="margin-bottom: 24px; display: flex"] h1 {
        font-size: 20px !important;
        margin-bottom: 0 !important;
    }

    div[style*="margin-bottom: 24px; display: flex"] > a {
        width: 100%;
        text-align: center;
        font-size: 13px;
        padding: 10px 16px;
    }

    div[style*="padding: 20px 24px"] {
        padding: 16px !important;
    }

    div[style*="padding: 24px"] {
        padding: 16px !important;
    }

    div[style*="display: flex; gap: 12px"] {
        flex-direction: column !important;
        gap: 8px !important;
    }

    div[style*="display: flex; gap: 12px"] button,
    div[style*="display: flex; gap: 12px"] a {
        width: 100%;
        text-align: center;
        font-size: 13px;
        padding: 12px 16px;
    }

    input[type="text"],
    input[type="number"],
    select,
    textarea {
        font-size: 16px !important;
        padding: 10px 12px !important;
    }
}

@media (max-width: 480px) {
    div[style*="padding: 24px"] {
        padding: 12px !important;
    }
}
</style>
@endsection

