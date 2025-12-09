@extends('layouts.company')

@section('title', '予約管理')

@section('content')
<div class="company-header">
    <h1 class="company-title">予約管理</h1>
</div>

<div class="company-card" style="margin-bottom: 20px;">
    <form action="{{ route('company.reservations.index') }}" method="GET" class="company-form">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 100px; gap: 12px; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="date">予約日</label>
                <input type="date" id="date" name="date" value="{{ request('date') }}">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="store_id">店舗</label>
                <select id="store_id" name="store_id">
                    <option value="">すべて</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="status">ステータス</label>
                <select id="status" name="status">
                    <option value="">すべて</option>
                    <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>予約確定</option>
                    <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>来店済</option>
                    <option value="3" {{ request('status') == 3 ? 'selected' : '' }}>店舗キャンセル</option>
                    <option value="4" {{ request('status') == 4 ? 'selected' : '' }}>顧客キャンセル</option>
                    <option value="9" {{ request('status') == 9 ? 'selected' : '' }}>無断キャンセル</option>
                </select>
            </div>

            <button type="submit" class="btn-primary">検索</button>
        </div>
    </form>
</div>

<div class="company-card">
    @if($reservations->isEmpty())
        <p class="empty-message">予約がありません。</p>
    @else
        <table class="company-table">
            <thead>
                <tr>
                    <th>予約日時</th>
                    <th>店舗</th>
                    <th>顧客名</th>
                    <th>スタッフ</th>
                    <th>メニュー</th>
                    <th>金額</th>
                    <th>ステータス</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->reservation_date->format('Y-m-d') }}<br>{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}</td>
                    <td>{{ $reservation->store->name }}</td>
                    <td>{{ $reservation->user->name }}</td>
                    <td>{{ $reservation->staff ? $reservation->staff->name : '指名なし' }}</td>
                    <td>
                        @foreach($reservation->reservationMenus as $menu)
                            {{ $menu->menu_name }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td>{{ number_format($reservation->total_price) }}円</td>
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
                    <td class="company-actions">
                        <a href="{{ route('company.reservations.show', $reservation) }}" class="btn-secondary btn-sm">詳細</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination-wrapper">
            {{ $reservations->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

