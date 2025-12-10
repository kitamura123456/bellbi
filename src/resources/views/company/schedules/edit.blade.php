@extends('layouts.company')

@section('title', '営業スケジュール編集')

@section('content')
<div class="company-header">
    <h1 class="company-title">営業スケジュール編集 - {{ $store->name }}</h1>
    <a href="{{ route('company.schedules.index') }}" class="btn-secondary">一覧に戻る</a>
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

        <div class="form-actions">
            <button type="submit" class="btn-primary">保存する</button>
            <a href="{{ route('company.schedules.index') }}" class="btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection

