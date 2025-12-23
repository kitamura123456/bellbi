@extends('layouts.app')

@section('title', '注文詳細 | Bellbi')

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
        <h1 class="page-title">注文詳細</h1>
        <p class="page-lead">注文番号: {{ $order->id }}</p>
    </div>

    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="
            padding: 32px;
            background: #fafafa;
            border: 1px solid #e0e0e0;
            margin-bottom: 32px;
        ">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                <span style="
                    display: inline-block;
                    padding: 8px 16px;
                    border-radius: 4px;
                    font-size: 14px;
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
                <span style="font-size: 13px; color: #666;">注文日：{{ $order->created_at->format('Y年m月d日 H:i') }}</span>
            </div>

            <h2 style="
                margin: 0 0 24px 0;
                font-size: 18px;
                font-weight: 400;
                color: #1a1a1a;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            ">注文内容</h2>
            @foreach($order->orderItems as $orderItem)
                <div style="
                    padding: 20px;
                    background: #ffffff;
                    border: 1px solid #e0e0e0;
                    margin-bottom: 16px;
                    display: flex;
                    gap: 20px;
                ">
                    <div style="width: 100px; height: 100px; background: #fafafa; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 12px;">
                        商品画像
                    </div>
                    <div style="flex: 1;">
                        <h3 style="
                            margin: 0 0 8px 0;
                            font-size: 16px;
                            font-weight: 400;
                            color: #1a1a1a;
                            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        ">
                            {{ $orderItem->product->name }}
                        </h3>
                        <p style="
                            margin: 0 0 12px 0;
                            font-size: 13px;
                            color: #666;
                            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        ">
                            数量: {{ $orderItem->quantity }} × ¥{{ number_format($orderItem->unit_price) }}
                        </p>
                        <p style="
                            margin: 0;
                            font-size: 16px;
                            font-weight: 400;
                            color: #1a1a1a;
                            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        ">
                            小計: ¥{{ number_format($orderItem->quantity * $orderItem->unit_price) }}
                        </p>
                    </div>
                </div>
            @endforeach

            <div style="
                padding-top: 20px;
                margin-top: 20px;
                border-top: 2px solid #1a1a1a;
                display: flex;
                justify-content: space-between;
                align-items: center;
            ">
                <span style="
                    font-size: 18px;
                    font-weight: 400;
                    color: #1a1a1a;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                ">合計金額</span>
                <span style="
                    font-size: 24px;
                    font-weight: 400;
                    color: #1a1a1a;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                ">¥{{ number_format($order->total_amount) }}</span>
            </div>
        </div>

        <div style="
            padding: 32px;
            background: #fafafa;
            border: 1px solid #e0e0e0;
            margin-bottom: 32px;
        ">
            <h2 style="
                margin: 0 0 24px 0;
                font-size: 18px;
                font-weight: 400;
                color: #1a1a1a;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            ">ショップ情報</h2>
            <div style="
                padding: 20px;
                background: #ffffff;
                border: 1px solid #e0e0e0;
            ">
                <p style="
                    margin: 0 0 8px 0;
                    font-size: 16px;
                    font-weight: 400;
                    color: #1a1a1a;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                ">
                    @if($order->shop && $order->shop->company)
                        {{ $order->shop->company->name }}
                    @endif
                </p>
                @if($order->shop && $order->shop->name)
                    <p style="
                        margin: 0;
                        font-size: 14px;
                        color: #666;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    ">
                        {{ $order->shop->name }}
                    </p>
                @endif
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('mypage.orders.index') }}" style="
                padding: 14px 24px;
                background: #1a1a1a;
                color: #ffffff;
                border: 1px solid #1a1a1a;
                border-radius: 0;
                font-size: 13px;
                font-weight: 500;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.3s ease;
                letter-spacing: 0.05em;
                text-transform: uppercase;
                display: inline-block;
            " onmouseover="this.style.backgroundColor='#000000'; this.style.borderColor='#000000';" onmouseout="this.style.backgroundColor='#1a1a1a'; this.style.borderColor='#1a1a1a';">
                注文履歴に戻る
            </a>
        </div>
    </div>
@endsection

