@extends('layouts.company')

@section('title', '営業スケジュール管理')

@section('content')
<div style="margin-bottom: 24px; margin-top: 48px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">営業スケジュール管理</h1>
</div>

@foreach($stores as $store)
<div class="company-card" style="margin-bottom: 24px;">
    <h3 style="margin-top: 0;">{{ $store->name }}</h3>
    
    @php
        $weekdays = ['日', '月', '火', '水', '木', '金', '土'];
        $schedulesByDay = $store->schedules->keyBy('day_of_week');
    @endphp
    
    <table class="company-table">
        <thead>
            <tr>
                <th>曜日</th>
                <th>営業状態</th>
                <th>営業時間</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @for($i = 0; $i < 7; $i++)
            @php
                $schedule = $schedulesByDay->get($i);
            @endphp
            <tr>
                <td>{{ $weekdays[$i] }}曜日</td>
                <td>
                    @if($schedule && $schedule->is_open)
                        <span class="badge badge-success">営業</span>
                    @else
                        <span class="badge">定休日</span>
                    @endif
                </td>
                <td>
                    @if($schedule && $schedule->is_open)
                        {{ \Carbon\Carbon::parse($schedule->open_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->close_time)->format('H:i') }}
                    @else
                        -
                    @endif
                </td>
                <td class="company-actions">
                    <a href="{{ route('company.schedules.edit', $store) }}" style="
                        padding: 8px 16px;
                        background: transparent;
                        color: #5D535E;
                        border: 1px solid #5D535E;
                        border-radius: 20px;
                        font-size: 13px;
                        font-weight: 700;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                        text-decoration: none;
                        cursor: pointer;
                        transition: all 0.2s ease;
                        display: inline-block;
                    " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                        編集
                    </a>
                </td>
            </tr>
            @endfor
        </tbody>
    </table>

    <!-- スマホ用カードレイアウト -->
    <div class="schedules-cards">
        @for($i = 0; $i < 7; $i++)
        @php
            $schedule = $schedulesByDay->get($i);
        @endphp
        <div class="schedule-card">
            <div class="schedule-card-header">
                <div class="schedule-card-day">{{ $weekdays[$i] }}曜日</div>
                <div>
                    @if($schedule && $schedule->is_open)
                        <span class="badge badge-success">営業</span>
                    @else
                        <span class="badge">定休日</span>
                    @endif
                </div>
            </div>
            <div class="schedule-card-body">
                <div class="schedule-card-row">
                    <span class="schedule-card-label">営業時間</span>
                    <span class="schedule-card-value">
                        @if($schedule && $schedule->is_open)
                            {{ \Carbon\Carbon::parse($schedule->open_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->close_time)->format('H:i') }}
                        @else
                            -
                        @endif
                    </span>
                </div>
            </div>
            <div class="schedule-card-actions">
                <a href="{{ route('company.schedules.edit', $store) }}" class="schedule-card-btn">
                    編集
                </a>
            </div>
        </div>
        @endfor
    </div>
</div>
@endforeach

@if($stores->isEmpty())
    <div class="company-card">
        <p class="empty-message">店舗が登録されていません。先に店舗を登録してください。</p>
        <a href="{{ route('company.stores.create') }}" class="empty-state-btn" style="
            padding: 12px 32px;
            background: #5D535E;
            color: #ffffff;
            border: none;
            border-radius: 24px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            display: inline-block;
        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
            店舗を登録する
        </a>
    </div>
@endif

<style>
/* スマホ用カードレイアウト（デフォルトは非表示） */
.schedules-cards {
    display: none;
}

.schedule-card {
    background: #ffffff;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
}

.schedule-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e8e8e8;
}

.schedule-card-day {
    font-size: 18px;
    font-weight: 700;
    color: #5D535E;
}

.schedule-card-body {
    display: grid;
    gap: 10px;
}

.schedule-card-row {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    padding: 4px 0;
}

.schedule-card-label {
    color: #6b7280;
    font-weight: 500;
}

.schedule-card-value {
    color: #111827;
    font-weight: 600;
    text-align: right;
    flex: 1;
    margin-left: 12px;
}

.schedule-card-actions {
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid #e8e8e8;
}

.schedule-card-btn {
    width: 100%;
    padding: 12px 16px;
    background: transparent;
    color: #5D535E;
    border: 1px solid #5D535E;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 700;
    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
    text-decoration: none;
    text-align: center;
    display: block;
    box-sizing: border-box;
    transition: all 0.2s ease;
}

.schedule-card-btn:active {
    background: #5D535E;
    color: #ffffff;
}

.empty-state-btn {
    display: inline-block;
    box-sizing: border-box;
}

/* スマホ用レスポンシブデザイン */
@media (max-width: 768px) {
    div[style*="margin-top: 48px"] {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 12px !important;
        margin-top: 24px !important;
    }

    div[style*="margin-top: 48px"] h1 {
        font-size: 20px !important;
        margin-bottom: 0 !important;
    }

    .company-table {
        display: none !important;
    }

    .company-card {
        margin-bottom: 16px !important;
        padding: 16px !important;
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .company-card h3 {
        font-size: 18px !important;
        margin-bottom: 16px !important;
        color: #5D535E;
        font-weight: 700;
    }

    .schedules-cards {
        display: block !important;
    }

    .schedule-card {
        margin-bottom: 16px;
    }

    .empty-state-btn {
        width: 100%;
        text-align: center;
        padding: 12px 16px !important;
        font-size: 14px !important;
    }
}

@media (max-width: 480px) {
    div[style*="margin-top: 48px"] {
        margin-top: 24px !important;
    }

    .company-card {
        padding: 12px !important;
    }

    .schedule-card {
        padding: 12px;
    }

    .schedule-card-day {
        font-size: 16px;
    }

    .schedule-card-row {
        font-size: 13px;
    }

    .schedule-card-btn {
        padding: 10px 14px;
        font-size: 13px;
    }
}
</style>
@endsection

