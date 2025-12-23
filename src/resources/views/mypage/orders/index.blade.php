@extends('layouts.app')

@section('title', '注文履歴 | Bellbi')

@section('sidebar')
<div class="sidebar-card">
    <h3 class="sidebar-title">メニュー</h3>
    <ul class="sidebar-menu">
        <li><a href="{{ route('mypage') }}" class="sidebar-menu-link">応募履歴</a></li>
        <li><a href="{{ route('mypage.scouts.index') }}" class="sidebar-menu-link">スカウト受信</a></li>
        <li><a href="{{ route('mypage.messages.index') }}" class="sidebar-menu-link">メッセージ</a></li>
        <li><a href="{{ route('mypage.scout-profile.edit') }}" class="sidebar-menu-link">スカウト用プロフィール</a></li>
        <li><a href="{{ route('mypage.reservations.index') }}" class="sidebar-menu-link">予約履歴</a></li>
        <li><a href="{{ route('mypage.orders.index') }}" class="sidebar-menu-link active">注文履歴</a></li>
    </ul>
</div>
@endsection

@section('content')
    <div class="page-header">
        <p class="page-label">My Page</p>
        <h1 class="page-title">注文履歴</h1>
        <p class="page-lead">あなたが注文した商品の一覧です。</p>
    </div>

    <div style="background: #ffffff; border-radius: 0; padding: 0; box-shadow: none; border: none; border-bottom: 1px solid #f0f0f0;">
        @forelse($orders as $order)
        <div style="
            padding: 20px 24px;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.15s ease;
        " onmouseover="this.style.backgroundColor='#fafafa';" onmouseout="this.style.backgroundColor='transparent';">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px;">
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
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
                            @elseif($order->status === 2) 入金確認済
                            @elseif($order->status === 3) 発送済
                            @elseif($order->status === 4) 完了
                            @elseif($order->status === 9) キャンセル
                            @endif
                        </span>
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
                </div>
                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px; min-width: 100px;">
                    <a href="{{ route('mypage.orders.show', $order) }}" style="
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

