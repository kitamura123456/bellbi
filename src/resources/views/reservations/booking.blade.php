@extends('layouts.app')

@section('title', '予約日時選択 | Bellbi')

@section('content')
    <div class="page-header">
        <h1 class="page-title">予約日時選択</h1>
        <p class="page-lead">{{ $store->name }}</p>
    </div>

    <div class="job-detail-card">
        <h3 style="margin-top: 0;">選択メニュー</h3>
        <ul style="list-style: none; padding: 0; margin: 0;">
            @foreach($menus as $menu)
                <li style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                    {{ $menu->name }} <span style="color: #6b7280;">（{{ $menu->duration_minutes }}分 / {{ number_format($menu->price) }}円）</span>
                </li>
            @endforeach
            <li style="padding: 8px 0; font-weight: bold;">
                合計：{{ $totalDuration }}分 / {{ number_format($totalPrice) }}円
            </li>
        </ul>
        @if($staff)
            <p style="margin-top: 12px;">担当スタッフ：<strong>{{ $staff->name }}</strong></p>
        @endif
    </div>

    <form action="{{ route('reservations.confirm', $store) }}" method="POST">
        @csrf
        @foreach($menus as $menu)
            <input type="hidden" name="menu_ids[]" value="{{ $menu->id }}">
        @endforeach
        @if($staff)
            <input type="hidden" name="staff_id" value="{{ $staff->id }}">
        @endif

        <div class="job-detail-card">
            <h3 style="margin-top: 0;">予約日時を選択 <span class="required">必須</span></h3>
            
            @if(empty($availableDates))
                <p class="empty-message">申し訳ございません。現在予約可能な時間帯がありません。</p>
            @else
                <div class="form-group">
                    <label for="reservation_date">予約日</label>
                    <select id="reservation_date" name="reservation_date" required>
                        <option value="">選択してください</option>
                        @foreach($availableDates as $date => $slots)
                            <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->isoFormat('M月D日(ddd)') }}</option>
                        @endforeach
                    </select>
                    @error('reservation_date')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" id="time-slot-wrapper" style="display: none;">
                    <label for="start_time">予約時間</label>
                    <select id="start_time" name="start_time" required>
                        <option value="">先に日付を選択してください</option>
                    </select>
                    @error('start_time')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <script>
                const availableDates = @json($availableDates);
                
                document.getElementById('reservation_date').addEventListener('change', function() {
                    const selectedDate = this.value;
                    const timeSlotWrapper = document.getElementById('time-slot-wrapper');
                    const startTimeSelect = document.getElementById('start_time');
                    
                    if (selectedDate && availableDates[selectedDate]) {
                        const slots = availableDates[selectedDate];
                        startTimeSelect.innerHTML = '<option value="">時間を選択してください</option>';
                        
                        slots.forEach(function(slot) {
                            const option = document.createElement('option');
                            option.value = slot.start_time;
                            option.textContent = slot.start_time + ' - ' + slot.end_time;
                            startTimeSelect.appendChild(option);
                        });
                        
                        timeSlotWrapper.style.display = 'block';
                    } else {
                        timeSlotWrapper.style.display = 'none';
                        startTimeSelect.innerHTML = '<option value="">先に日付を選択してください</option>';
                    }
                });
                </script>
            @endif
        </div>

        <div class="job-detail-card">
            <h3 style="margin-top: 0;">ご要望・メモ（任意）</h3>
            <textarea name="customer_note" placeholder="アレルギーやご要望などがあればご記入ください">{{ old('customer_note') }}</textarea>
            @error('customer_note')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">予約を確定する</button>
            <a href="{{ route('reservations.store', $store) }}" class="btn-secondary">戻る</a>
        </div>
    </form>
@endsection

