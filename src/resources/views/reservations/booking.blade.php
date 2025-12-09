@extends('layouts.app')

@section('title', '予約日時選択 | Bellbi')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/themes/material_pink.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/ja.js"></script>

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
                    <input type="text" id="reservation_date" name="reservation_date" placeholder="カレンダーから日付を選択してください" required readonly style="cursor: pointer; background-color: white;">
                    @error('reservation_date')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" id="time-slot-wrapper" style="display: none;">
                    <label for="start_time">予約時間</label>
                    <div id="time-slots-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 10px; margin-top: 10px;">
                        <!-- 時間枠ボタンがここに動的に追加されます -->
                    </div>
                    <input type="hidden" id="start_time" name="start_time" required>
                    @error('start_time')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <style>
                .time-slot-btn {
                    padding: 12px 8px;
                    border: 2px solid #fb7185;
                    background-color: white;
                    color: #fb7185;
                    border-radius: 8px;
                    cursor: pointer;
                    transition: all 0.2s;
                    font-size: 13px;
                    text-align: center;
                }
                .time-slot-btn:hover {
                    background-color: #fce7f3;
                }
                .time-slot-btn.selected {
                    background-color: #fb7185;
                    color: white;
                }
                </style>

                <script>
                const availableDates = @json($availableDates);
                const availableDatesList = Object.keys(availableDates);
                
                // Flatpickrの初期化
                const datePicker = flatpickr("#reservation_date", {
                    locale: "ja",
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    maxDate: new Date().fp_incr(60), // 60日先まで
                    enable: availableDatesList,
                    onChange: function(selectedDates, dateStr, instance) {
                        if (dateStr && availableDates[dateStr]) {
                            showTimeSlots(dateStr);
                        } else {
                            hideTimeSlots();
                        }
                    }
                });
                
                function showTimeSlots(selectedDate) {
                    const timeSlotWrapper = document.getElementById('time-slot-wrapper');
                    const timeSlotsGrid = document.getElementById('time-slots-grid');
                    const startTimeInput = document.getElementById('start_time');
                    
                    const slots = availableDates[selectedDate];
                    timeSlotsGrid.innerHTML = '';
                    startTimeInput.value = '';
                    
                    slots.forEach(function(slot) {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'time-slot-btn';
                        button.textContent = slot.start_time + '\n〜\n' + slot.end_time;
                        button.style.whiteSpace = 'pre-line';
                        button.dataset.time = slot.start_time;
                        
                        button.addEventListener('click', function() {
                            // 他のボタンの選択を解除
                            document.querySelectorAll('.time-slot-btn').forEach(function(btn) {
                                btn.classList.remove('selected');
                            });
                            
                            // このボタンを選択
                            this.classList.add('selected');
                            startTimeInput.value = this.dataset.time;
                        });
                        
                        timeSlotsGrid.appendChild(button);
                    });
                    
                    timeSlotWrapper.style.display = 'block';
                }
                
                function hideTimeSlots() {
                    const timeSlotWrapper = document.getElementById('time-slot-wrapper');
                    const timeSlotsGrid = document.getElementById('time-slots-grid');
                    const startTimeInput = document.getElementById('start_time');
                    
                    timeSlotWrapper.style.display = 'none';
                    timeSlotsGrid.innerHTML = '';
                    startTimeInput.value = '';
                }
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

