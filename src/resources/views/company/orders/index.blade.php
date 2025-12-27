@extends('layouts.company')

@section('title', '受注管理')

@section('content')
<style>
    @media (max-width: 768px) {
        .orders-header h1 {
            font-size: 20px !important;
            margin-bottom: 12px !important;
        }
        
        .orders-filters {
            flex-direction: column !important;
            gap: 8px !important;
        }
        
        .orders-filters select {
            width: 100% !important;
            font-size: 14px !important;
            padding: 10px 12px !important;
        }
        
        .orders-table-header {
            flex-direction: column !important;
            gap: 12px !important;
            padding: 16px !important;
        }
        
        .orders-table-header h3 {
            font-size: 14px !important;
        }
        
        .orders-table-actions {
            flex-direction: column !important;
            width: 100% !important;
            gap: 8px !important;
        }
        
        .orders-table-actions button {
            width: 100% !important;
            font-size: 12px !important;
            padding: 10px 16px !important;
        }
        
        .orders-table {
            display: none;
        }
        
        .orders-cards {
            display: block;
        }
        
        .order-card {
            background: #ffffff;
            border: 1px solid #e8e8e8;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
        }
        
        .order-card.unread {
            background: #fef3c7;
            border-color: #fbbf24;
        }
        
        .order-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e8e8e8;
        }
        
        .order-card-id {
            font-size: 16px;
            font-weight: 700;
            color: #5D535E;
        }
        
        .order-card-status {
            font-size: 11px;
        }
        
        .order-card-body {
            display: grid;
            gap: 8px;
        }
        
        .order-card-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
        }
        
        .order-card-label {
            color: #6b7280;
            font-weight: 500;
        }
        
        .order-card-value {
            color: #2A3132;
            font-weight: 600;
        }
        
        .order-card-actions {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e8e8e8;
            display: flex;
            gap: 8px;
        }
        
        .order-card-actions a {
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
        .orders-cards {
            display: none;
        }
    }
</style>

<div class="orders-header" style="margin-bottom: 24px; margin-top: 48px;">
    <h1 style="margin: 0 0 16px 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">受注管理</h1>
    @if($shops->isNotEmpty())
    <form method="GET" action="{{ route('company.orders.index') }}" class="orders-filters" style="display: flex; gap: 12px; align-items: center; margin-bottom: 16px;">
        <select name="shop_id" style="
            padding: 8px 16px;
            border: 1px solid #e8e8e8;
            border-radius: 12px;
            font-size: 14px;
            background: #ffffff;
        " onchange="this.form.submit();">
            <option value="">すべてのショップ</option>
            @foreach($shops as $shop)
                <option value="{{ $shop->id }}" {{ $shopId == $shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
            @endforeach
        </select>
        <select name="status" style="
            padding: 8px 16px;
            border: 1px solid #e8e8e8;
            border-radius: 12px;
            font-size: 14px;
            background: #ffffff;
        " onchange="this.form.submit();">
            <option value="">すべてのステータス</option>
            <option value="1" {{ $status == 1 ? 'selected' : '' }}>注文完了</option>
            <option value="2" {{ $status == 2 ? 'selected' : '' }}>入金確認済</option>
            <option value="3" {{ $status == 3 ? 'selected' : '' }}>発送済</option>
            <option value="4" {{ $status == 4 ? 'selected' : '' }}>完了</option>
            <option value="9" {{ $status == 9 ? 'selected' : '' }}>キャンセル</option>
        </select>
    </form>
    @endif
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
    overflow-x: auto;
">
    <div class="orders-table-header" style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">注文一覧</h3>
        @if($orders->isNotEmpty())
        <div class="orders-table-actions" style="display: flex; gap: 12px; align-items: center;">
            <button type="button" onclick="markSelectedOrdersAsViewed()" style="
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
                未読: <strong>{{ $orders->where('viewed_at', null)->count() }}</strong>件
            </span>
        </div>
        @endif
    </div>
    <table class="orders-table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #fafafa; border-bottom: 1px solid #e8e8e8;">
                <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif; width: 40px;">
                    <input type="checkbox" id="select-all-orders" onchange="toggleAllOrders(this)">
                </th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">注文ID</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">ショップ</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">購入者</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">合計金額</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">ステータス</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">注文日</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr class="order-row {{ $order->isUnread() ? 'unread' : '' }}" data-order-id="{{ $order->id }}" style="border-bottom: 1px solid #f5f5f5; {{ $order->isUnread() ? 'background: #fef3c7;' : '' }}" onmouseover="this.style.background='{{ $order->isUnread() ? '#fef3c7' : '#fafafa' }}';" onmouseout="this.style.background='{{ $order->isUnread() ? '#fef3c7' : '#ffffff' }}';">
                <td style="padding: 12px 16px;">
                    <input type="checkbox" class="order-checkbox" value="{{ $order->id }}" data-order-id="{{ $order->id }}">
                </td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">
                    #{{ $order->id }}
                    @if($order->isUnread())
                        <span style="display: inline-block; margin-left: 8px; padding: 2px 8px; background: #d63638; color: #ffffff; border-radius: 10px; font-size: 10px; font-weight: 600;">NEW</span>
                    @endif
                </td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $order->shop->name ?? '-' }}</td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">
                    @if($order->user)
                        {{ $order->user->name }}
                    @else
                        ゲスト
                    @endif
                </td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">¥{{ number_format($order->total_amount) }}</td>
                <td style="padding: 12px 16px;">
                    @if($order->status === \App\Models\Order::STATUS_NEW)
                        <span style="padding: 4px 12px; background: #dbeafe; color: #1e40af; border-radius: 12px; font-size: 12px; font-weight: 600;">注文完了</span>
                    @elseif($order->status === \App\Models\Order::STATUS_PAID)
                        <span style="padding: 4px 12px; background: #d1fae5; color: #059669; border-radius: 12px; font-size: 12px; font-weight: 600;">入金確認済</span>
                    @elseif($order->status === \App\Models\Order::STATUS_SHIPPED)
                        <span style="padding: 4px 12px; background: #fef3c7; color: #d97706; border-radius: 12px; font-size: 12px; font-weight: 600;">発送済</span>
                    @elseif($order->status === \App\Models\Order::STATUS_COMPLETED)
                        <span style="padding: 4px 12px; background: #f3f4f6; color: #6b7280; border-radius: 12px; font-size: 12px; font-weight: 600;">完了</span>
                    @elseif($order->status === \App\Models\Order::STATUS_CANCELLED)
                        <span style="padding: 4px 12px; background: #fee2e2; color: #dc2626; border-radius: 12px; font-size: 12px; font-weight: 600;">キャンセル</span>
                    @endif
                </td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $order->created_at->format('Y/m/d H:i') }}</td>
                <td style="padding: 12px 16px;">
                    <a href="{{ route('company.orders.show', $order) }}" style="
                        padding: 6px 16px;
                        background: #5D535E;
                        color: #ffffff;
                        border: none;
                        border-radius: 16px;
                        font-size: 12px;
                        font-weight: 700;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                        text-decoration: none;
                        transition: all 0.2s ease;
                    " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                        詳細
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="padding: 40px 16px; text-align: center; color: #999999; font-size: 14px;">注文がありません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- スマホ用カードレイアウト -->
    <div class="orders-cards">
        @forelse($orders as $order)
        <div class="order-card {{ $order->isUnread() ? 'unread' : '' }}" data-order-id="{{ $order->id }}">
            <div class="order-card-header">
                <div>
                    <div class="order-card-id">
                        #{{ $order->id }}
                        @if($order->isUnread())
                            <span style="display: inline-block; margin-left: 8px; padding: 2px 8px; background: #d63638; color: #ffffff; border-radius: 10px; font-size: 10px; font-weight: 600;">NEW</span>
                        @endif
                    </div>
                </div>
                <div class="order-card-status">
                    @if($order->status === \App\Models\Order::STATUS_NEW)
                        <span style="padding: 4px 12px; background: #dbeafe; color: #1e40af; border-radius: 12px; font-size: 11px; font-weight: 600;">注文完了</span>
                    @elseif($order->status === \App\Models\Order::STATUS_PAID)
                        <span style="padding: 4px 12px; background: #d1fae5; color: #059669; border-radius: 12px; font-size: 11px; font-weight: 600;">入金確認済</span>
                    @elseif($order->status === \App\Models\Order::STATUS_SHIPPED)
                        <span style="padding: 4px 12px; background: #fef3c7; color: #d97706; border-radius: 12px; font-size: 11px; font-weight: 600;">発送済</span>
                    @elseif($order->status === \App\Models\Order::STATUS_COMPLETED)
                        <span style="padding: 4px 12px; background: #f3f4f6; color: #6b7280; border-radius: 12px; font-size: 11px; font-weight: 600;">完了</span>
                    @elseif($order->status === \App\Models\Order::STATUS_CANCELLED)
                        <span style="padding: 4px 12px; background: #fee2e2; color: #dc2626; border-radius: 12px; font-size: 11px; font-weight: 600;">キャンセル</span>
                    @endif
                </div>
            </div>
            <div class="order-card-body">
                <div class="order-card-row">
                    <span class="order-card-label">ショップ</span>
                    <span class="order-card-value">{{ $order->shop->name ?? '-' }}</span>
                </div>
                <div class="order-card-row">
                    <span class="order-card-label">購入者</span>
                    <span class="order-card-value">
                        @if($order->user)
                            {{ $order->user->name }}
                        @else
                            ゲスト
                        @endif
                    </span>
                </div>
                <div class="order-card-row">
                    <span class="order-card-label">合計金額</span>
                    <span class="order-card-value">¥{{ number_format($order->total_amount) }}</span>
                </div>
                <div class="order-card-row">
                    <span class="order-card-label">注文日</span>
                    <span class="order-card-value">{{ $order->created_at->format('Y/m/d H:i') }}</span>
                </div>
            </div>
            <div class="order-card-actions">
                <input type="checkbox" class="order-checkbox" value="{{ $order->id }}" data-order-id="{{ $order->id }}" style="width: 20px; height: 20px; margin-right: 8px;">
                <a href="{{ route('company.orders.show', $order) }}">詳細</a>
            </div>
        </div>
        @empty
        <div style="padding: 40px 16px; text-align: center; color: #999999; font-size: 14px;">注文がありません。</div>
        @endforelse
    </div>
</div>

@if($orders->hasPages())
    <div style="margin-top: 24px;">
        {{ $orders->links() }}
    </div>
@endif

<script>
function toggleAllOrders(checkbox) {
    const checkboxes = document.querySelectorAll('.order-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
}

function markSelectedOrdersAsViewed() {
    const checked = document.querySelectorAll('.order-checkbox:checked');
    if (checked.length === 0) {
        alert('既読にする項目を選択してください。');
        return;
    }
    
    const orderIds = Array.from(checked).map(cb => parseInt(cb.value));
    
    fetch('{{ route("company.orders.mark-multiple-viewed") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ order_ids: orderIds })
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
            const selectAll = document.getElementById('select-all-orders');
            if (selectAll) {
                selectAll.checked = false;
            }
            updateUnreadCount();
            updateSidebarBadge('orders');
        }
    })
    .catch(error => {
        console.error('Error details:', error);
        alert('既読の更新に失敗しました: ' + (error.message || error.error || 'Unknown error'));
    });
}

function updateUnreadCount() {
    const unreadRows = document.querySelectorAll('.order-row.unread');
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

