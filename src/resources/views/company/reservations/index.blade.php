@extends('layouts.company')

@section('title', '予約管理')

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">予約管理</h1>
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
    margin-bottom: 24px;
">
    <form action="{{ route('company.reservations.index') }}" method="GET" style="padding: 24px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 12px; align-items: end;">
            <div style="margin-bottom: 0;">
                <label for="date" style="
                    display: block;
                    margin-bottom: 8px;
                    font-size: 13px;
                    font-weight: 700;
                    color: #5D535E;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">予約日</label>
                <input type="date" id="date" name="date" value="{{ request('date') }}" style="
                    width: 100%;
                    padding: 12px 16px;
                    border: 1px solid #e8e8e8;
                    border-radius: 12px;
                    font-size: 14px;
                    font-family: inherit;
                    color: #2A3132;
                    background: #fafafa;
                    transition: all 0.2s ease;
                    box-sizing: border-box;
                " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
            </div>

            <div style="margin-bottom: 0;">
                <label for="store_id" style="
                    display: block;
                    margin-bottom: 8px;
                    font-size: 13px;
                    font-weight: 700;
                    color: #5D535E;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">店舗</label>
                <select id="store_id" name="store_id" style="
                    width: 100%;
                    padding: 12px 16px;
                    border: 1px solid #e8e8e8;
                    border-radius: 12px;
                    font-size: 14px;
                    font-family: inherit;
                    color: #2A3132;
                    background: #fafafa;
                    transition: all 0.2s ease;
                    box-sizing: border-box;
                " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
                    <option value="">すべて</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 0;">
                <label for="status" style="
                    display: block;
                    margin-bottom: 8px;
                    font-size: 13px;
                    font-weight: 700;
                    color: #5D535E;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">ステータス</label>
                <select id="status" name="status" style="
                    width: 100%;
                    padding: 12px 16px;
                    border: 1px solid #e8e8e8;
                    border-radius: 12px;
                    font-size: 14px;
                    font-family: inherit;
                    color: #2A3132;
                    background: #fafafa;
                    transition: all 0.2s ease;
                    box-sizing: border-box;
                " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
                    <option value="">すべて</option>
                    <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>予約確定</option>
                    <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>来店済</option>
                    <option value="3" {{ request('status') == 3 ? 'selected' : '' }}>店舗キャンセル</option>
                    <option value="4" {{ request('status') == 4 ? 'selected' : '' }}>顧客キャンセル</option>
                    <option value="9" {{ request('status') == 9 ? 'selected' : '' }}>無断キャンセル</option>
                </select>
            </div>

            <button type="submit" style="
                padding: 12px 24px;
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
                white-space: nowrap;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                検索
            </button>
        </div>
    </form>
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
    overflow-x: auto;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">予約一覧</h3>
    </div>
    @if($reservations->isEmpty())
        <div style="padding: 40px 24px; text-align: center; color: #999999;">
            <p style="margin: 0; font-size: 14px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">予約がありません。</p>
        </div>
    @else
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #fafafa; border-bottom: 1px solid #e8e8e8;">
                    <th style="
                        padding: 12px 16px;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">予約日時</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">店舗</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">顧客名</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">スタッフ</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">メニュー</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">金額</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">ステータス</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $reservation)
                <tr style="border-bottom: 1px solid #f5f5f5;" onmouseover="this.style.background='#fafafa';" onmouseout="this.style.background='#ffffff';">
                    <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $reservation->reservation_date->format('Y-m-d') }}<br>{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}</td>
                    <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $reservation->store->name }}</td>
                    <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $reservation->user->name }}</td>
                    <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $reservation->staff ? $reservation->staff->name : '指名なし' }}</td>
                    <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">
                        @foreach($reservation->reservationMenus as $menu)
                            {{ $menu->menu_name }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ number_format($reservation->total_price) }}円</td>
                    <td style="padding: 12px 16px;">
                        @if($reservation->status === 1)
                            <span style="
                                display: inline-block;
                                padding: 4px 12px;
                                background: #5D535E;
                                color: #ffffff;
                                border-radius: 12px;
                                font-size: 12px;
                                font-weight: 500;
                            ">予約確定</span>
                        @elseif($reservation->status === 2)
                            <span style="
                                display: inline-block;
                                padding: 4px 12px;
                                background: #336B87;
                                color: #ffffff;
                                border-radius: 12px;
                                font-size: 12px;
                                font-weight: 500;
                            ">来店済</span>
                        @elseif($reservation->status === 3)
                            <span style="
                                display: inline-block;
                                padding: 4px 12px;
                                background: #999999;
                                color: #ffffff;
                                border-radius: 12px;
                                font-size: 12px;
                                font-weight: 500;
                            ">店舗キャンセル</span>
                        @elseif($reservation->status === 4)
                            <span style="
                                display: inline-block;
                                padding: 4px 12px;
                                background: #999999;
                                color: #ffffff;
                                border-radius: 12px;
                                font-size: 12px;
                                font-weight: 500;
                            ">顧客キャンセル</span>
                        @elseif($reservation->status === 9)
                            <span style="
                                display: inline-block;
                                padding: 4px 12px;
                                background: #763626;
                                color: #ffffff;
                                border-radius: 12px;
                                font-size: 12px;
                                font-weight: 500;
                            ">無断キャンセル</span>
                        @endif
                    </td>
                    <td style="padding: 12px 16px;">
                        <a href="{{ route('company.reservations.show', $reservation) }}" style="
                            padding: 6px 16px;
                            background: transparent;
                            color: #5D535E;
                            border: 1px solid #5D535E;
                            border-radius: 16px;
                            font-size: 12px;
                            font-weight: 700;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                            text-decoration: none;
                            transition: all 0.2s ease;
                            position: relative;
                        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                            詳細
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="padding: 24px; border-top: 1px solid #e8e8e8;">
            {{ $reservations->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

