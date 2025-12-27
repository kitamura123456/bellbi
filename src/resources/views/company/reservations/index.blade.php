@extends('layouts.company')

@section('title', '予約管理')

@section('content')
<style>
    @media (max-width: 768px) {
        .reservations-header h1 {
            font-size: 20px !important;
            margin-bottom: 12px !important;
        }
        
        .reservations-filters {
            grid-template-columns: 1fr !important;
            gap: 12px !important;
            padding: 16px !important;
        }
        
        .reservations-filters button {
            width: 100% !important;
        }
        
        .reservations-table-header {
            flex-direction: column !important;
            gap: 12px !important;
            padding: 16px !important;
        }
        
        .reservations-table-actions {
            flex-direction: column !important;
            width: 100% !important;
            gap: 8px !important;
        }
        
        .reservations-table-actions button {
            width: 100% !important;
            font-size: 12px !important;
            padding: 10px 16px !important;
        }
        
        .reservations-table {
            display: none;
        }
        
        .reservations-cards {
            display: block;
            padding: 16px;
        }
        
        .reservation-card {
            background: #ffffff;
            border: 1px solid #e8e8e8;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
        }
        
        .reservation-card.unread {
            background: #fef3c7;
            border-color: #fbbf24;
        }
        
        .reservation-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e8e8e8;
        }
        
        .reservation-card-body {
            display: grid;
            gap: 8px;
            font-size: 13px;
        }
        
        .reservation-card-row {
            display: flex;
            justify-content: space-between;
        }
        
        .reservation-card-label {
            color: #6b7280;
            font-weight: 500;
        }
        
        .reservation-card-value {
            color: #2A3132;
            font-weight: 600;
        }
        
        .reservation-card-actions {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e8e8e8;
            display: flex;
            gap: 8px;
            align-items: center;
        }
        
        .reservation-card-actions a {
            flex: 1;
            text-align: center;
            padding: 8px 16px;
            background: #5D535E;
            color: #ffffff;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }
    }
    
    @media (min-width: 769px) {
        .reservations-cards {
            display: none;
        }
    }
</style>

<div class="reservations-header" style="margin-bottom: 24px; margin-top: 48px;">
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
    <form action="{{ route('company.reservations.index') }}" method="GET" class="reservations-filters" style="padding: 24px;">
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
    <div class="reservations-table-header" style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">予約一覧</h3>
        @if($reservations->isNotEmpty())
        <div class="reservations-table-actions" style="display: flex; gap: 12px; align-items: center;">
            <button type="button" onclick="markSelectedReservationsAsViewed()" style="
                padding: 8px 16px;
                background: #2271b1;
                color: #ffffff;
                border: none;
                border-radius: 4px;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;
            ">選択した項目を既読にする</button>
            <span id="unread-count" style="font-size: 13px; color: #666;">
                未読: <strong>{{ $reservations->where('viewed_at', null)->count() }}</strong>件
            </span>
        </div>
        @endif
    </div>
    @if($reservations->isEmpty())
        <div style="padding: 40px 24px; text-align: center; color: #999999;">
            <p style="margin: 0; font-size: 14px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">予約がありません。</p>
        </div>
    @else
        <table class="reservations-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #fafafa; border-bottom: 1px solid #e8e8e8;">
                    <th style="
                        padding: 12px 16px;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                        width: 40px;
                    ">
                        <input type="checkbox" id="select-all-reservations" onchange="toggleAllReservations(this)">
                    </th>
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
                <tr class="reservation-row {{ $reservation->isUnread() ? 'unread' : '' }}" style="border-bottom: 1px solid #f5f5f5; {{ $reservation->isUnread() ? 'background: #fef3c7;' : '' }}" onmouseover="this.style.background='{{ $reservation->isUnread() ? '#fef3c7' : '#fafafa' }}';" onmouseout="this.style.background='{{ $reservation->isUnread() ? '#fef3c7' : '#ffffff' }}';">
                    <td style="padding: 12px 16px;">
                        <input type="checkbox" class="reservation-checkbox" value="{{ $reservation->id }}" data-reservation-id="{{ $reservation->id }}">
                    </td>
                    <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">
                        {{ $reservation->reservation_date->format('Y-m-d') }}<br>{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}
                        @if($reservation->isUnread())
                            <span style="display: inline-block; margin-left: 8px; padding: 2px 8px; background: #d63638; color: #ffffff; border-radius: 10px; font-size: 10px; font-weight: 600;">NEW</span>
                        @endif
                    </td>
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
        
        <!-- スマホ用カードレイアウト -->
        <div class="reservations-cards">
            @foreach($reservations as $reservation)
            <div class="reservation-card {{ $reservation->isUnread() ? 'unread' : '' }}" data-reservation-id="{{ $reservation->id }}">
                <div class="reservation-card-header">
                    <div>
                        <div style="font-size: 16px; font-weight: 700; color: #5D535E;">
                            {{ $reservation->reservation_date->format('Y年m月d日') }} {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}〜
                            @if($reservation->isUnread())
                                <span style="display: inline-block; margin-left: 8px; padding: 2px 8px; background: #d63638; color: #ffffff; border-radius: 10px; font-size: 10px; font-weight: 600;">NEW</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="reservation-card-body">
                    <div class="reservation-card-row">
                        <span class="reservation-card-label">店舗</span>
                        <span class="reservation-card-value">{{ $reservation->store->name }}</span>
                    </div>
                    <div class="reservation-card-row">
                        <span class="reservation-card-label">顧客名</span>
                        <span class="reservation-card-value">{{ $reservation->user->name }}</span>
                    </div>
                    <div class="reservation-card-row">
                        <span class="reservation-card-label">合計金額</span>
                        <span class="reservation-card-value">¥{{ number_format($reservation->total_price) }}</span>
                    </div>
                    <div class="reservation-card-row">
                        <span class="reservation-card-label">ステータス</span>
                        <span class="reservation-card-value">
                            @if($reservation->status === 1)
                                <span style="display: inline-block; padding: 4px 12px; background: #5D535E; color: #ffffff; border-radius: 12px; font-size: 11px; font-weight: 500;">予約確定</span>
                            @elseif($reservation->status === 2)
                                <span style="display: inline-block; padding: 4px 12px; background: #d1fae5; color: #059669; border-radius: 12px; font-size: 11px; font-weight: 500;">来店済</span>
                            @elseif($reservation->status === 3)
                                <span style="display: inline-block; padding: 4px 12px; background: #fee2e2; color: #dc2626; border-radius: 12px; font-size: 11px; font-weight: 500;">店舗キャンセル</span>
                            @elseif($reservation->status === 4)
                                <span style="display: inline-block; padding: 4px 12px; background: #999999; color: #ffffff; border-radius: 12px; font-size: 11px; font-weight: 500;">顧客キャンセル</span>
                            @elseif($reservation->status === 9)
                                <span style="display: inline-block; padding: 4px 12px; background: #763626; color: #ffffff; border-radius: 12px; font-size: 11px; font-weight: 500;">無断キャンセル</span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="reservation-card-actions">
                    <input type="checkbox" class="reservation-checkbox" value="{{ $reservation->id }}" data-reservation-id="{{ $reservation->id }}" style="width: 20px; height: 20px;">
                    <a href="{{ route('company.reservations.show', $reservation) }}">詳細</a>
                </div>
            </div>
            @endforeach
        </div>

        <div style="padding: 24px; border-top: 1px solid #e8e8e8;">
            {{ $reservations->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<script>
function toggleAllReservations(checkbox) {
    const checkboxes = document.querySelectorAll('.reservation-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
}

function markSelectedReservationsAsViewed() {
    const checked = document.querySelectorAll('.reservation-checkbox:checked');
    if (checked.length === 0) {
        alert('既読にする項目を選択してください。');
        return;
    }
    
    const reservationIds = Array.from(checked).map(cb => parseInt(cb.value));
    
    fetch('{{ route("company.reservations.mark-multiple-viewed") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ reservation_ids: reservationIds })
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Error response:', text);
                try {
                    const err = JSON.parse(text);
                    throw err;
                } catch (e) {
                    throw { message: text || 'Unknown error', status: response.status };
                }
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            checked.forEach(cb => {
                const row = cb.closest('tr');
                if (row) {
                    row.classList.remove('unread');
                    row.style.background = '#ffffff';
                    row.setAttribute('onmouseover', "this.style.background='#fafafa';");
                    row.setAttribute('onmouseout', "this.style.background='#ffffff';");
                    const newBadge = row.querySelector('span[style*="background: #d63638"]');
                    if (newBadge) {
                        newBadge.remove();
                    }
                }
                cb.checked = false;
            });
            const selectAll = document.getElementById('select-all-reservations');
            if (selectAll) {
                selectAll.checked = false;
            }
            updateUnreadCount();
            updateSidebarBadge('reservations');
        }
    })
    .catch(error => {
        console.error('Error details:', error);
        alert('既読の更新に失敗しました: ' + (error.message || error.error || 'Unknown error'));
    });
}

function updateUnreadCount() {
    const unreadRows = document.querySelectorAll('.reservation-row.unread');
    const countElement = document.getElementById('unread-count');
    if (countElement) {
        countElement.innerHTML = `未読: <strong>${unreadRows.length}</strong>件`;
    }
}

function updateSidebarBadge(type) {
    // サーバーから最新の統計情報を取得
    fetch('{{ route("company.sidebar-stats") }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // 受注管理のバッジを更新
        const ordersBadge = document.getElementById('sidebar-orders-badge');
        if (ordersBadge) {
            if (data.newOrdersCount > 0) {
                ordersBadge.textContent = data.newOrdersCount;
                ordersBadge.style.display = 'inline-block';
            } else {
                ordersBadge.style.display = 'none';
            }
        }
        
        // 応募管理のバッジを更新
        const applicationsBadge = document.getElementById('sidebar-applications-badge');
        if (applicationsBadge) {
            if (data.newApplicationsCount > 0) {
                applicationsBadge.textContent = data.newApplicationsCount;
                applicationsBadge.style.display = 'inline-block';
            } else {
                applicationsBadge.style.display = 'none';
            }
        }
        
        // 予約管理のバッジを更新
        const reservationsBadge = document.getElementById('sidebar-reservations-badge');
        if (reservationsBadge) {
            if (data.upcomingReservationsCount > 0) {
                reservationsBadge.textContent = data.upcomingReservationsCount;
                reservationsBadge.style.display = 'inline-block';
            } else {
                reservationsBadge.style.display = 'none';
            }
        }
    })
    .catch(error => {
        console.error('Error updating sidebar badges:', error);
    });
}
</script>
@endsection

