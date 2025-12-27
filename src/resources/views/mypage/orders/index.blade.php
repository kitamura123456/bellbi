@extends('layouts.app')

@section('title', '注文履歴 | Bellbi')

@section('sidebar')
<div class="sidebar-card">
    <div class="mypage-menu-header" style="display: flex; align-items: center; justify-content: space-between; cursor: pointer;" onclick="if(window.innerWidth <= 768) toggleMypageMenu()">
        <h3 class="sidebar-title" style="margin: 0;">メニュー</h3>
        <span class="mypage-toggle-icon" style="
            display: none;
            font-size: 16px;
            color: #1a1a1a;
            transition: transform 0.3s ease;
            user-select: none;
            flex-shrink: 0;
            margin-left: 8px;
        ">▼</span>
    </div>
    <ul class="sidebar-menu mypage-menu-list" id="mypageMenuList">
        <li><a href="{{ route('mypage') }}" class="sidebar-menu-link">応募履歴</a></li>
        <li><a href="{{ route('mypage.scouts.index') }}" class="sidebar-menu-link">スカウト受信</a></li>
        <li><a href="{{ route('mypage.messages.index') }}" class="sidebar-menu-link">メッセージ</a></li>
        <li><a href="{{ route('mypage.scout-profile.edit') }}" class="sidebar-menu-link">スカウト用プロフィール</a></li>
        <li><a href="{{ route('mypage.reservations.index') }}" class="sidebar-menu-link">予約履歴</a></li>
        <li><a href="{{ route('mypage.orders.index') }}" class="sidebar-menu-link active">注文履歴</a></li>
    </ul>
</div>
<style>
    /* デスクトップ版の固定メニュー */
    .sidebar {
        position: sticky !important;
        top: 0 !important;
        align-self: flex-start !important;
        z-index: 40 !important;
        max-height: 100vh !important;
        overflow-y: auto !important;
    }
    .sidebar-card {
        position: sticky !important;
        top: 0 !important;
    }
    .sidebar-menu,
    .mypage-menu-list {
        position: relative !important;
    }
    
    @media (max-width: 768px) {
        .sidebar {
            position: sticky !important;
            top: 0 !important;
            z-index: 50 !important;
            background: #ffffff !important;
            margin-bottom: 0 !important;
        }
        .sidebar-card {
            position: sticky !important;
            top: 0 !important;
            z-index: 50 !important;
            background: #ffffff !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05) !important;
            margin-bottom: 0 !important;
            padding: 8px 12px !important;
        }
        .sidebar-menu,
        .mypage-menu-list {
            position: relative !important;
        }
        .mypage-menu-header {
            padding: 4px 0 !important;
            margin-bottom: 0 !important;
        }
        .sidebar-title {
            font-size: 11px !important;
            margin-bottom: 0 !important;
        }
        .mypage-toggle-icon {
            display: block !important;
            font-size: 14px !important;
        }
        .mypage-menu-list {
            display: none;
            margin-top: 8px;
        }
        .mypage-menu-list.active {
            display: block !important;
        }
        .mypage-toggle-icon.active {
            transform: rotate(180deg);
        }
        .container.main-inner {
            flex-direction: column !important;
        }
        .sidebar {
            order: -1 !important;
        }
        .page-header {
            margin-top: 24px !important;
        }
        .order-item {
            position: relative !important;
            padding: 16px 12px 60px 12px !important;
        }
        .order-item-inner {
            flex-direction: column !important;
            gap: 12px !important;
            align-items: center !important;
        }
        .order-status-badge {
            display: block !important;
            left: 12px !important;
            right: auto !important;
        }
        .order-status-badge-desktop {
            display: none !important;
        }
        .order-content {
            text-align: left !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 auto !important;
            padding-left: 80px !important;
            box-sizing: border-box !important;
        }
        .order-content > div[style*="display: flex"] {
            justify-content: flex-start !important;
        }
        .order-content > div[style*="display: flex"] > span[style*="font-size: 12px"]:not(.order-date-mobile) {
            display: none !important;
        }
        .order-date-mobile {
            display: inline-block !important;
        }
        .order-content > h3,
        .order-content > p {
            text-align: left !important;
            width: 100% !important;
        }
        .order-button-wrapper {
            position: absolute !important;
            bottom: 20px !important;
            right: 12px !important;
            min-width: auto !important;
        }
        .order-detail-button {
            padding: 8px 16px !important;
            font-size: 12px !important;
        }
        .order-status-text {
            display: block;
            line-height: 1.2;
        }
    }
</style>
<script>
    function toggleMypageMenu() {
        const menu = document.getElementById('mypageMenuList');
        const icon = document.querySelector('.mypage-toggle-icon');
        
        if (menu && icon) {
            menu.classList.toggle('active');
            icon.classList.toggle('active');
        }
    }
</script>
@endsection

@section('content')
    <div class="page-header">
        <p class="page-label">My Page</p>
        <h1 class="page-title">注文履歴</h1>
        <p class="page-lead">あなたが注文した商品の一覧です。</p>
    </div>

    @if(session('status'))
    <div style="
        padding: 16px;
        margin-bottom: 24px;
        background: #d1fae5;
        border: 1px solid #10b981;
        border-radius: 4px;
        color: #065f46;
        font-size: 14px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
    ">
        {{ session('status') }}
    </div>
    @endif

    @if(session('error'))
    <div style="
        padding: 16px;
        margin-bottom: 24px;
        background: #fee2e2;
        border: 1px solid #ef4444;
        border-radius: 4px;
        color: #991b1b;
        font-size: 14px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
    ">
        {{ session('error') }}
    </div>
    @endif

    <div style="background: #ffffff; border-radius: 0; padding: 0; box-shadow: none; border: none; border-bottom: 1px solid #f0f0f0;">
        @forelse($orders as $order)
        <div class="order-item" style="
            padding: 20px 24px;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.15s ease;
        " onmouseover="this.style.backgroundColor='#fafafa';" onmouseout="this.style.backgroundColor='transparent';">
            <div class="order-status-badge" style="
                position: absolute;
                top: 16px;
                left: 12px;
                display: none;
            ">
                <span style="
                    display: inline-block;
                    padding: 5px 12px;
                    border-radius: 4px;
                    font-size: 12px;
                    font-weight: 600;
                    background-color: {{ $order->status === 1 ? '#f5f5f5' : ($order->status === 2 ? '#3b82f6' : ($order->status === 3 ? '#10b981' : ($order->status === 4 ? '#10b981' : '#6b7280'))) }};
                    color: {{ $order->status === 1 ? '#1a1a1a' : '#ffffff' }};
                    border: {{ $order->status === 1 ? '1px solid #e0e0e0' : 'none' }};
                ">
                    @if($order->status === 1) 新規
                    @elseif($order->status === 2) <span class="order-status-text">入金確認</span><span class="order-status-text">済</span>
                    @elseif($order->status === 3) 発送済
                    @elseif($order->status === 4) 完了
                    @elseif($order->status === 9) キャンセル
                    @endif
                </span>
            </div>
            <div class="order-item-inner" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px;">
                <div class="order-content" style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <span class="order-status-badge-desktop" style="
                            display: inline-block;
                            padding: 5px 12px;
                            border-radius: 4px;
                            font-size: 12px;
                            font-weight: 600;
                            background-color: {{ $order->status === 1 ? '#f5f5f5' : ($order->status === 2 ? '#3b82f6' : ($order->status === 3 ? '#10b981' : ($order->status === 4 ? '#10b981' : '#6b7280'))) }};
                            color: {{ $order->status === 1 ? '#1a1a1a' : '#ffffff' }};
                            border: {{ $order->status === 1 ? '1px solid #e0e0e0' : 'none' }};
                        ">
                            @if($order->status === 1) 新規
                            @elseif($order->status === 2) <span class="order-status-text">入金確認</span><span class="order-status-text">済</span>
                            @elseif($order->status === 3) 発送済
                            @elseif($order->status === 4) 完了
                            @elseif($order->status === 9) キャンセル
                            @endif
                        </span>
                        <span class="order-date-mobile" style="font-size: 12px; color: #999; display: none;">注文日：{{ $order->created_at->format('Y年m月d日') }}</span>
                        <span style="font-size: 12px; color: #999;">注文日：{{ $order->created_at->format('Y年m月d日') }}</span>
                        <span style="font-size: 12px; color: #999;">注文番号：{{ $order->id }}</span>
                    </div>
                    <h3 style="
                        margin: 0 0 8px 0;
                        font-size: 18px;
                        font-weight: 600;
                        color: #1a1a1a;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    ">
                        @if($order->shop && $order->shop->company)
                            {{ $order->shop->company->name }}
                        @endif
                    </h3>
                    <p style="
                        margin: 0 0 12px 0;
                        font-size: 14px;
                        color: #666;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    ">
                        @if($order->orderItems->count() > 0)
                            {{ $order->orderItems->first()->product->name }}
                            @if($order->orderItems->count() > 1)
                                他{{ $order->orderItems->count() - 1 }}点
                            @endif
                        @endif
                    </p>
                    <p style="
                        margin: 0;
                        font-size: 16px;
                        font-weight: 400;
                        color: #1a1a1a;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    ">
                        合計: ¥{{ number_format($order->total_amount) }}
                    </p>
                    @if(isset($paymentUrls[$order->id]) && ($order->status === 1 || $order->status === 2))
                    <div style="margin-top: 12px;">
                        <a href="{{ $paymentUrls[$order->id]['url'] }}" target="_blank" rel="noopener noreferrer" style="
                            display: inline-block;
                            padding: 6px 12px;
                            background: #fffbf0;
                            color: #92400e;
                            border: 1px solid #fef3c7;
                            border-radius: 4px;
                            font-size: 12px;
                            font-weight: 500;
                            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            text-decoration: none;
                            cursor: pointer;
                            transition: all 0.15s ease;
                        " onmouseover="this.style.backgroundColor='#fef3c7';" onmouseout="this.style.backgroundColor='#fffbf0';">
                            @if($paymentUrls[$order->id]['type'] === 'konbini')
                                コンビニ決済情報
                            @elseif($paymentUrls[$order->id]['type'] === 'bank_transfer')
                                銀行振込情報
                            @else
                                支払い情報
                            @endif
                        </a>
                    </div>
                    @endif
                </div>
                <div class="order-button-wrapper" style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px; min-width: 100px;">
                    <a href="{{ route('mypage.orders.show', $order) }}" class="order-detail-button" style="
                        padding: 8px 16px;
                        background: #1a1a1a;
                        color: #ffffff;
                        border: none;
                        border-radius: 4px;
                        font-size: 13px;
                        font-weight: 500;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        text-decoration: none;
                        cursor: pointer;
                        transition: all 0.15s ease;
                        white-space: nowrap;
                        text-align: center;
                    " onmouseover="this.style.backgroundColor='#333333';" onmouseout="this.style.backgroundColor='#1a1a1a';">
                        詳細を見る
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div style="padding: 40px 24px; text-align: center;">
            <p style="margin: 0 0 16px 0; font-size: 14px; color: #666;">まだ注文した商品はありません。</p>
            <a href="{{ route('shops.index') }}" style="
                padding: 10px 24px;
                background: #1a1a1a;
                color: #ffffff;
                border: none;
                border-radius: 4px;
                font-size: 13px;
                font-weight: 500;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.15s ease;
                display: inline-block;
            " onmouseover="this.style.backgroundColor='#333333';" onmouseout="this.style.backgroundColor='#1a1a1a';">
                ショップを見る
            </a>
        </div>
        @endforelse
    </div>

    @if($orders->hasPages())
        <div class="pagination-wrapper" style="margin-top: 32px; padding-top: 32px; border-top: 1px solid #f0f0f0;">
            {{ $orders->links('vendor.pagination.tailwind') }}
        </div>
    @endif
@endsection

