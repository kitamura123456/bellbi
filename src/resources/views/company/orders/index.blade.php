@extends('layouts.company')

@section('title', '受注管理')

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="margin: 0 0 16px 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">受注管理</h1>
    @if($shops->isNotEmpty())
    <form method="GET" action="{{ route('company.orders.index') }}" style="display: flex; gap: 12px; align-items: center; margin-bottom: 16px;">
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
            <option value="1" {{ $status == 1 ? 'selected' : '' }}>新規</option>
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
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">注文一覧</h3>
    </div>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #fafafa; border-bottom: 1px solid #e8e8e8;">
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
            <tr style="border-bottom: 1px solid #f5f5f5;" onmouseover="this.style.background='#fafafa';" onmouseout="this.style.background='#ffffff';">
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">#{{ $order->id }}</td>
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
                        <span style="padding: 4px 12px; background: #dbeafe; color: #1e40af; border-radius: 12px; font-size: 12px; font-weight: 600;">新規</span>
                    @elseif($order->status === \App\Models\Order::STATUS_PAID)
                        <span style="padding: 4px 12px; background: #d1fae5; color: #059669; border-radius: 12px; font-size: 12px; font-weight: 600;">入金確認済</span>
                    @elseif($order->status === \App\Models\Order::STATUS_SHIPPED)
                        <span style="padding: 4px 12px; background: #fef3c7; color: #d97706; border-radius: 12px; font-size: 12px; font-weight: 600;">発送済</span>
                    @elseif($order->status === \App\Models\Order::STATUS_COMPLETED)
                        <span style="padding: 4px 12px; background: #d1fae5; color: #059669; border-radius: 12px; font-size: 12px; font-weight: 600;">完了</span>
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
                <td colspan="7" style="padding: 40px 16px; text-align: center; color: #999999; font-size: 14px;">注文がありません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($orders->hasPages())
    <div style="margin-top: 24px;">
        {{ $orders->links() }}
    </div>
@endif
@endsection

