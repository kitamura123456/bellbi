@extends('layouts.company')

@section('title', '商品管理')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
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

@if($products->hasPages())
    <div style="margin-top: 24px;">
        {{ $products->links() }}
    </div>
@endif
@endsection

