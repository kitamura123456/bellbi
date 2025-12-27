@extends('layouts.company')

@section('title', '商品管理')

@section('content')
<div style="margin-bottom: 24px; margin-top: 48px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">商品管理</h1>
    <a href="{{ route('company.products.create') }}" style="
        padding: 12px 24px;
        background: #5D535E;
        color: #ffffff;
        border: none;
        border-radius: 24px;
        font-size: 14px;
        font-weight: 700;
        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
        新規商品登録
    </a>
</div>

@if($shops->isNotEmpty())
<div style="margin-bottom: 16px;">
    <form method="GET" action="{{ route('company.products.index') }}" style="display: flex; gap: 12px; align-items: center;">
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
    </form>
</div>
@endif

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
    overflow-x: auto;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">商品一覧</h3>
    </div>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #fafafa; border-bottom: 1px solid #e8e8e8;">
                <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">商品名</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">ショップ</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">価格</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">在庫</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">ステータス</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr style="border-bottom: 1px solid #f5f5f5;" onmouseover="this.style.background='#fafafa';" onmouseout="this.style.background='#ffffff';">
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $product->name }}</td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $product->shop->name ?? '-' }}</td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">¥{{ number_format($product->price) }}</td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $product->stock }}点</td>
                <td style="padding: 12px 16px;">
                    @if($product->status === \App\Models\Product::STATUS_ON_SALE)
                        <span style="padding: 4px 12px; background: #d1fae5; color: #059669; border-radius: 12px; font-size: 12px; font-weight: 600;">販売中</span>
                    @elseif($product->status === \App\Models\Product::STATUS_OUT_OF_STOCK)
                        <span style="padding: 4px 12px; background: #fee2e2; color: #dc2626; border-radius: 12px; font-size: 12px; font-weight: 600;">在庫切れ</span>
                    @elseif($product->status === \App\Models\Product::STATUS_PRIVATE)
                        <span style="padding: 4px 12px; background: #f3f4f6; color: #6b7280; border-radius: 12px; font-size: 12px; font-weight: 600;">非公開</span>
                    @endif
                </td>
                <td style="padding: 12px 16px;">
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('company.products.edit', $product) }}" style="
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
                        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                            編集
                        </a>
                        <form action="{{ route('company.products.destroy', $product) }}" method="POST" style="display:inline;" onsubmit="return confirm('この商品を削除してもよろしいですか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="
                                padding: 6px 16px;
                                background: #763626;
                                color: #ffffff;
                                border: none;
                                border-radius: 16px;
                                font-size: 12px;
                                font-weight: 700;
                                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                                cursor: pointer;
                                transition: all 0.2s ease;
                            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                                削除
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 40px 16px; text-align: center; color: #999999; font-size: 14px;">商品が登録されていません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- スマホ用カードレイアウト -->
<div class="products-cards">
    @forelse($products as $product)
    <div class="product-card">
        <div class="product-card-header">
            <div class="product-card-name">{{ $product->name }}</div>
            <div>
                @if($product->status === \App\Models\Product::STATUS_ON_SALE)
                    <span class="product-badge product-badge-on-sale">販売中</span>
                @elseif($product->status === \App\Models\Product::STATUS_OUT_OF_STOCK)
                    <span class="product-badge product-badge-out-of-stock">在庫切れ</span>
                @elseif($product->status === \App\Models\Product::STATUS_PRIVATE)
                    <span class="product-badge product-badge-private">非公開</span>
                @endif
            </div>
        </div>
        <div class="product-card-body">
            <div class="product-card-row">
                <span class="product-card-label">ショップ</span>
                <span class="product-card-value">{{ $product->shop->name ?? '-' }}</span>
            </div>
            <div class="product-card-row">
                <span class="product-card-label">価格</span>
                <span class="product-card-value">¥{{ number_format($product->price) }}</span>
            </div>
            <div class="product-card-row">
                <span class="product-card-label">在庫</span>
                <span class="product-card-value">{{ $product->stock }}点</span>
            </div>
        </div>
        <div class="product-card-actions">
            <a href="{{ route('company.products.edit', $product) }}" class="product-card-btn product-card-btn-edit">
                編集
            </a>
            <form action="{{ route('company.products.destroy', $product) }}" method="POST" class="product-card-form" onsubmit="return confirm('この商品を削除してもよろしいですか？');">
                @csrf
                @method('DELETE')
                <button type="submit" class="product-card-btn product-card-btn-delete">
                    削除
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="product-card-empty">
        <p>商品が登録されていません。</p>
    </div>
    @endforelse
</div>

@if($products->hasPages())
    <div style="margin-top: 24px;">
        {{ $products->links() }}
    </div>
@endif

<style>
/* スマホ用カードレイアウト（デフォルトは非表示） */
.products-cards {
    display: none;
}

.product-card {
    background: #ffffff;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
}

.product-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e8e8e8;
    gap: 12px;
}

.product-card-name {
    font-size: 18px;
    font-weight: 700;
    color: #5D535E;
    flex: 1;
}

.product-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.product-badge-on-sale {
    background: #d1fae5;
    color: #059669;
}

.product-badge-out-of-stock {
    background: #fee2e2;
    color: #dc2626;
}

.product-badge-private {
    background: #f3f4f6;
    color: #6b7280;
}

.product-card-body {
    display: grid;
    gap: 10px;
    margin-bottom: 12px;
}

.product-card-row {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    padding: 4px 0;
}

.product-card-label {
    color: #6b7280;
    font-weight: 500;
}

.product-card-value {
    color: #111827;
    font-weight: 600;
    text-align: right;
    flex: 1;
    margin-left: 12px;
}

.product-card-actions {
    display: flex;
    gap: 8px;
    padding-top: 12px;
    border-top: 1px solid #e8e8e8;
}

.product-card-btn {
    flex: 1;
    padding: 10px 16px;
    border-radius: 16px;
    font-size: 13px;
    font-weight: 700;
    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
    text-decoration: none;
    text-align: center;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.product-card-btn-edit {
    background: transparent;
    color: #5D535E;
    border: 1px solid #5D535E;
}

.product-card-btn-delete {
    background: #763626;
    color: #ffffff;
}

.product-card-form {
    flex: 1;
    margin: 0;
}

.product-card-empty {
    background: #ffffff;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    padding: 40px 16px;
    text-align: center;
    color: #999999;
    font-size: 14px;
}

/* スマホ用レスポンシブデザイン */
@media (max-width: 768px) {
    div[style*="margin-top: 48px"] {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 12px !important;
    }

    div[style*="margin-top: 48px"] h1 {
        font-size: 20px !important;
        margin-bottom: 0 !important;
    }

    div[style*="margin-top: 48px"] > a {
        width: 100%;
        text-align: center;
        font-size: 13px;
        padding: 10px 16px;
    }

    div[style*="margin-bottom: 16px"] form {
        width: 100%;
    }

    div[style*="margin-bottom: 16px"] select {
        width: 100%;
        font-size: 16px;
        padding: 10px 12px;
    }

    div[style*="overflow-x: auto"] {
        display: none;
    }

    .products-cards {
        display: block;
    }

    .product-card {
        margin-bottom: 16px;
    }

    .product-card-name {
        font-size: 20px;
    }

    .product-card-row {
        font-size: 15px;
    }

    .product-card-btn {
        font-size: 14px;
        padding: 12px 16px;
    }
}

@media (max-width: 480px) {
    .product-card {
        padding: 12px;
    }

    .product-card-name {
        font-size: 18px;
    }

    .product-card-row {
        font-size: 14px;
    }

    .product-card-btn {
        font-size: 13px;
        padding: 10px 12px;
    }
}
</style>
@endsection

