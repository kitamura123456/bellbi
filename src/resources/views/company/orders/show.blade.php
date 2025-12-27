@extends('layouts.company')

@section('title', '注文詳細')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">注文詳細 #{{ $order->id }}</h1>
    <a href="{{ route('company.orders.index') }}" style="
        padding: 12px 24px;
        background: transparent;
        color: #5D535E;
        border: 1px solid #5D535E;
        border-radius: 24px;
        font-size: 14px;
        font-weight: 700;
        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
        一覧に戻る
    </a>
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
    margin-bottom: 24px;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">注文情報</h3>
    </div>
    <div style="padding: 24px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
            <div>
                <p style="margin: 0 0 8px 0; font-size: 13px; font-weight: 700; color: #6b7280;">ショップ</p>
                <p style="margin: 0; font-size: 16px; color: #2A3132;">{{ $order->shop->name ?? '-' }}</p>
            </div>
            <div>
                <p style="margin: 0 0 8px 0; font-size: 13px; font-weight: 700; color: #6b7280;">購入者</p>
                <p style="margin: 0; font-size: 16px; color: #2A3132;">
                    @if($order->user)
                        {{ $order->user->name }} ({{ $order->user->email }})
                    @else
                        ゲスト
                    @endif
                </p>
            </div>
            <div>
                <p style="margin: 0 0 8px 0; font-size: 13px; font-weight: 700; color: #6b7280;">注文日時</p>
                <p style="margin: 0; font-size: 16px; color: #2A3132;">{{ $order->created_at->format('Y年m月d日 H:i') }}</p>
            </div>
            <div>
                <p style="margin: 0 0 8px 0; font-size: 13px; font-weight: 700; color: #6b7280;">ステータス</p>
                <p style="margin: 0;">
                    @if($order->status === \App\Models\Order::STATUS_NEW)
                        <span style="padding: 4px 12px; background: #dbeafe; color: #1e40af; border-radius: 12px; font-size: 14px; font-weight: 600;">注文完了</span>
                    @elseif($order->status === \App\Models\Order::STATUS_PAID)
                        <span style="padding: 4px 12px; background: #d1fae5; color: #059669; border-radius: 12px; font-size: 14px; font-weight: 600;">入金確認済</span>
                    @elseif($order->status === \App\Models\Order::STATUS_SHIPPED)
                        <span style="padding: 4px 12px; background: #fef3c7; color: #d97706; border-radius: 12px; font-size: 14px; font-weight: 600;">発送済</span>
                    @elseif($order->status === \App\Models\Order::STATUS_COMPLETED)
                        <span style="padding: 4px 12px; background: #f3f4f6; color: #6b7280; border-radius: 12px; font-size: 14px; font-weight: 600;">完了</span>
                    @elseif($order->status === \App\Models\Order::STATUS_CANCELLED)
                        <span style="padding: 4px 12px; background: #fee2e2; color: #dc2626; border-radius: 12px; font-size: 14px; font-weight: 600;">キャンセル</span>
                    @endif
                </p>
            </div>
        </div>

        <div style="margin-bottom: 24px;">
            <h4 style="margin: 0 0 16px 0; font-size: 16px; font-weight: 700; color: #5D535E;">注文商品</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #fafafa; border-bottom: 1px solid #e8e8e8;">
                        <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px;">商品名</th>
                        <th style="padding: 12px 16px; text-align: right; font-weight: 700; color: #5D535E; font-size: 13px;">単価</th>
                        <th style="padding: 12px 16px; text-align: right; font-weight: 700; color: #5D535E; font-size: 13px;">数量</th>
                        <th style="padding: 12px 16px; text-align: right; font-weight: 700; color: #5D535E; font-size: 13px;">小計</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr style="border-bottom: 1px solid #f5f5f5;">
                        <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $item->product->name ?? '削除された商品' }}</td>
                        <td style="padding: 12px 16px; color: #2A3132; font-size: 14px; text-align: right;">¥{{ number_format($item->unit_price) }}</td>
                        <td style="padding: 12px 16px; color: #2A3132; font-size: 14px; text-align: right;">{{ $item->quantity }}</td>
                        <td style="padding: 12px 16px; color: #2A3132; font-size: 14px; text-align: right;">¥{{ number_format($item->unit_price * $item->quantity) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="padding: 16px; text-align: right; font-weight: 700; color: #5D535E; font-size: 16px;">合計</td>
                        <td style="padding: 16px; text-align: right; font-weight: 700; color: #5D535E; font-size: 18px;">¥{{ number_format($order->total_amount) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div>
            <h4 style="margin: 0 0 16px 0; font-size: 16px; font-weight: 700; color: #5D535E;">ステータス変更</h4>
            <form action="{{ route('company.orders.update-status', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <div style="display: flex; gap: 12px; align-items: center;">
                    <select name="status" required style="
                        padding: 12px 16px;
                        border: 1px solid #e8e8e8;
                        border-radius: 12px;
                        font-size: 14px;
                        background: #ffffff;
                        flex: 1;
                    ">
                        <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>注文完了</option>
                        <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>入金確認済</option>
                        <option value="3" {{ $order->status == 3 ? 'selected' : '' }}>発送済</option>
                        <option value="4" {{ $order->status == 4 ? 'selected' : '' }}>完了</option>
                        <option value="9" {{ $order->status == 9 ? 'selected' : '' }}>キャンセル</option>
                    </select>
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
                    " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                        更新
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* スマホ用レスポンシブデザイン */
@media (max-width: 768px) {
    div[style*="margin-bottom: 24px; display: flex"] {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 12px !important;
    }

    div[style*="margin-bottom: 24px; display: flex"] h1 {
        font-size: 20px !important;
        margin-bottom: 0 !important;
    }

    div[style*="margin-bottom: 24px; display: flex"] > a {
        width: 100%;
        text-align: center;
        font-size: 13px;
        padding: 10px 16px;
    }

    div[style*="padding: 20px 24px"] {
        padding: 16px !important;
    }

    div[style*="padding: 24px"] {
        padding: 16px !important;
    }

    div[style*="display: grid; grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
        gap: 16px !important;
    }

    div[style*="display: grid; grid-template-columns: 1fr 1fr"] > div {
        padding-bottom: 16px;
        border-bottom: 1px solid #e8e8e8;
    }

    div[style*="display: grid; grid-template-columns: 1fr 1fr"] > div:last-child {
        border-bottom: none;
    }

    table[style*="width: 100%"] {
        display: none;
    }

    .order-items-cards {
        display: block;
    }

    .order-item-card {
        background: #f9fafb;
        border: 1px solid #e8e8e8;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
    }

    .order-item-card-name {
        font-size: 16px;
        font-weight: 700;
        color: #5D535E;
        margin-bottom: 12px;
    }

    .order-item-card-row {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        padding: 4px 0;
    }

    .order-item-card-label {
        color: #6b7280;
        font-weight: 500;
    }

    .order-item-card-value {
        color: #111827;
        font-weight: 600;
    }

    div[style*="display: flex; gap: 12px"] {
        flex-direction: column !important;
        gap: 8px !important;
    }

    div[style*="display: flex; gap: 12px"] button,
    div[style*="display: flex; gap: 12px"] a {
        width: 100%;
        text-align: center;
        font-size: 13px;
        padding: 12px 16px;
    }
}

.order-items-cards {
    display: none;
}

@media (max-width: 480px) {
    div[style*="padding: 24px"] {
        padding: 12px !important;
    }

    .order-item-card {
        padding: 12px;
    }
}
</style>

<!-- スマホ用カードレイアウト -->
<div class="order-items-cards">
    @foreach($order->orderItems as $item)
    <div class="order-item-card">
        <div class="order-item-card-name">{{ $item->product->name ?? '削除された商品' }}</div>
        <div class="order-item-card-row">
            <span class="order-item-card-label">単価</span>
            <span class="order-item-card-value">¥{{ number_format($item->price) }}</span>
        </div>
        <div class="order-item-card-row">
            <span class="order-item-card-label">数量</span>
            <span class="order-item-card-value">{{ $item->quantity }}</span>
        </div>
        <div class="order-item-card-row" style="padding-top: 8px; border-top: 1px solid #e8e8e8; margin-top: 8px;">
            <span class="order-item-card-label">小計</span>
            <span class="order-item-card-value">¥{{ number_format($item->price * $item->quantity) }}</span>
        </div>
    </div>
    @endforeach
</div>
@endsection

