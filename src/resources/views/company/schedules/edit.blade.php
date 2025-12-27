@extends('layouts.company')

@section('title', '営業スケジュール編集')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">営業スケジュール編集 - {{ $store->name }}</h1>
    <a href="{{ route('company.schedules.index') }}" style="
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
    <form action="{{ route('company.schedules.update', $store) }}" method="POST" class="company-form">
        @csrf
        @method('PUT')

        @foreach($weekdays as $day => $dayName)
        @php
            $schedule = $schedules->get($day);
        @endphp
        <div style="padding: 16px; background-color: #f9fafb; border-radius: 8px; margin-bottom: 16px;">
            <h4 style="margin: 0 0 12px;">{{ $dayName }}</h4>
            
            <div class="form-group" style="margin-bottom: 12px;">
                <label>
                    <input type="radio" name="schedules[{{ $day }}][is_open]" value="1" {{ old("schedules.$day.is_open", $schedule->is_open ?? 1) == 1 ? 'checked' : '' }}> 営業
                </label>
                <label style="margin-left: 20px;">
                    <input type="radio" name="schedules[{{ $day }}][is_open]" value="0" {{ old("schedules.$day.is_open", $schedule->is_open ?? 1) == 0 ? 'checked' : '' }}> 定休日
                </label>
            </div>

            <div style="display: flex; gap: 16px; align-items: center;">
                <div class="form-group" style="margin-bottom: 0; flex: 1;">
                    <label for="open_time_{{ $day }}">開店時刻</label>
                    <input type="time" id="open_time_{{ $day }}" name="schedules[{{ $day }}][open_time]" value="{{ old("schedules.$day.open_time", $schedule->open_time ?? '10:00') }}">
                </div>
                <span style="margin-top: 24px;">〜</span>
                <div class="form-group" style="margin-bottom: 0; flex: 1;">
                    <label for="close_time_{{ $day }}">閉店時刻</label>
                    <input type="time" id="close_time_{{ $day }}" name="schedules[{{ $day }}][close_time]" value="{{ old("schedules.$day.close_time", $schedule->close_time ?? '19:00') }}">
                </div>
            </div>

            <div class="form-group" style="margin-top: 12px;">
                <label for="max_concurrent_{{ $day }}">同時対応可能予約数</label>
                <input type="number" id="max_concurrent_{{ $day }}" name="schedules[{{ $day }}][max_concurrent_reservations]" value="{{ old("schedules.$day.max_concurrent_reservations", $schedule->max_concurrent_reservations ?? 1) }}" min="1" max="50" style="width: 100px;">
                <small style="color: #666;">※ 同時に対応できる予約の数を設定してください</small>
            </div>
        </div>
        @endforeach

        @error('schedules')
            <span class="error">{{ $message }}</span>
        @enderror

        <div style="display: flex; gap: 12px; justify-content: flex-end;">
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
                保存する
            </button>
            <a href="{{ route('company.schedules.index') }}" style="
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
                キャンセル
            </a>
        </div>
    </form>
</div>

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

    div[style*="padding: 20px 24px"] {
        padding: 16px !important;
    }

    form[style*="padding: 24px"] {
        padding: 16px !important;
    }

    div[style*="display: grid"] {
        grid-template-columns: 1fr !important;
        gap: 12px !important;
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
    form[style*="padding: 24px"] {
        padding: 12px !important;
    }
}
</style>
@endsection

