@extends('layouts.company')

@section('title', '営業スケジュール管理')

@section('content')
<div class="company-header">
    <h1 class="company-title">営業スケジュール管理</h1>
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
                    <a href="{{ route('company.schedules.edit', $store) }}" class="btn-secondary btn-sm">編集</a>
                </td>
            </tr>
            @endfor
        </tbody>
    </table>
</div>
@endforeach

@if($stores->isEmpty())
    <div class="company-card">
        <p class="empty-message">店舗が登録されていません。先に店舗を登録してください。</p>
        <a href="{{ route('company.stores.create') }}" class="btn-primary">店舗を登録する</a>
    </div>
@endif
@endsection

