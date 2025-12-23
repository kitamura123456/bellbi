@extends('layouts.app')

@section('title', $product->name . ' | Bellbi')

@section('content')
    <header class="page-header" style="margin-bottom: 48px; padding-bottom: 32px; border-bottom: 1px solid #f0f0f0;">
        <h1 class="page-title" style="font-size: 32px; font-weight: 400; color: #1a1a1a; margin: 0 0 12px 0; letter-spacing: -0.02em; line-height: 1.3; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{{ $product->name }}</h1>
        <p class="page-lead" style="font-size: 14px; color: #666; line-height: 1.7; margin: 0; font-weight: 400; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
            @if($product->shop && $product->shop->company)
                販売元: {{ $product->shop->company->name }}
            @endif
        </p>
    </header>

    <div style="max-width: 1200px; margin: 0 auto;">
        <div class="product-detail-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 48px; margin-bottom: 48px;">
            <div class="product-image-wrapper">
                <div class="product-image" style="width: 100%; height: 500px; background: #fafafa; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 14px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                    商品画像
                </div>
            </div>
            <div class="product-info">
                <p class="product-price" style="
                    margin: 0 0 32px 0;
                    font-size: 28px;
                    font-weight: 400;
                    color: #1a1a1a;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                ">
                    ¥{{ number_format($product->price) }}
                </p>
                @if($product->status === \App\Models\Product::STATUS_OUT_OF_STOCK || $product->stock <= 0)
                    <div style="
                        margin: 0 0 32px 0;
                        padding: 20px;
                        background: #fafafa;
                        border: 1px solid #e0e0e0;
                        text-align: center;
                    ">
                        <p style="
                            margin: 0;
                            font-size: 16px;
                            color: #1a1a1a;
                            font-weight: 400;
                            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        ">在庫切れ</p>
                        <p style="
                            margin: 8px 0 0 0;
                            font-size: 13px;
                            color: #666;
                            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        ">現在この商品は在庫がありません</p>
                    </div>
                @elseif($product->stock < 10)
                    <p style="
                        margin: 0 0 32px 0;
                        padding: 12px 16px;
                        background: #fafafa;
                        color: #666;
                        font-size: 13px;
                        font-weight: 400;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    ">残り{{ $product->stock }}点</p>
                @else
                    <p style="
                        margin: 0 0 32px 0;
                        padding: 12px 16px;
                        background: #fafafa;
                        color: #666;
                        font-size: 13px;
                        font-weight: 400;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    ">在庫あり</p>
                @endif
                @if($product->status !== \App\Models\Product::STATUS_OUT_OF_STOCK && $product->stock > 0)
                    <form method="POST" action="{{ route('cart.add') }}" style="margin-bottom: 0;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div style="margin-bottom: 24px;">
                            <label for="quantity" style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.02em;">数量</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" style="
                                width: 120px;
                                padding: 12px 16px;
                                border: 1px solid #e0e0e0;
                                border-radius: 0;
                                font-size: 14px;
                                background: #ffffff;
                                color: #1a1a1a;
                                transition: all 0.3s ease;
                                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            " onfocus="this.style.borderColor='#1a1a1a'; this.style.outline='none';" onblur="this.style.borderColor='#e0e0e0';">
                        </div>
                        <button type="submit" style="
                            width: 100%;
                            padding: 14px 32px;
                            background: #1a1a1a;
                            color: #ffffff;
                            border: 1px solid #1a1a1a;
                            border-radius: 0;
                            font-size: 13px;
                            font-weight: 500;
                            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            cursor: pointer;
                            transition: all 0.3s ease;
                            letter-spacing: 0.05em;
                            text-transform: uppercase;
                        " onmouseover="this.style.background='#000000'; this.style.borderColor='#000000';" onmouseout="this.style.background='#1a1a1a'; this.style.borderColor='#1a1a1a';">
                            カートに追加
                        </button>
                    </form>
                @endif
            </div>
        </div>
        <div class="product-description" style="margin-top: 48px; padding-top: 32px; border-top: 1px solid #f0f0f0;">
            <h2 style="
                margin: 0 0 24px 0;
                font-size: 18px;
                font-weight: 400;
                color: #1a1a1a;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            ">商品説明</h2>
            <div style="
                padding: 0;
                background: #ffffff;
                border: none;
                line-height: 1.8;
                color: #666;
                font-size: 14px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            ">
                {!! nl2br(e($product->description ?? '商品説明はありません。')) !!}
            </div>
        </div>
    </div>

    <style>
        /* スマホ版の最適化 */
        @media (max-width: 768px) {
            .page-header {
                margin-bottom: 24px !important;
                padding-bottom: 20px !important;
            }
            .page-title {
                font-size: 24px !important;
                margin-bottom: 8px !important;
            }
            .page-lead {
                font-size: 12px !important;
            }
            .product-detail-grid {
                grid-template-columns: 1fr !important;
                gap: 24px !important;
                margin-bottom: 32px !important;
            }
            .product-image {
                height: 300px !important;
            }
            .product-price {
                font-size: 24px !important;
                margin-bottom: 20px !important;
            }
            .product-info > div[style*="margin: 0 0 32px"] {
                margin-bottom: 20px !important;
            }
            .product-info > div[style*="padding: 20px"] {
                padding: 16px !important;
            }
            .product-info > div[style*="padding: 20px"] p[style*="font-size: 16px"] {
                font-size: 14px !important;
            }
            .product-info > div[style*="padding: 20px"] p[style*="font-size: 13px"] {
                font-size: 12px !important;
            }
            .product-info > p[style*="padding: 12px 16px"] {
                padding: 10px 12px !important;
                font-size: 12px !important;
                margin-bottom: 20px !important;
            }
            .product-info form {
                margin-bottom: 0 !important;
            }
            .product-info form > div[style*="margin-bottom: 24px"] {
                margin-bottom: 16px !important;
            }
            .product-info form label {
                font-size: 11px !important;
                margin-bottom: 6px !important;
            }
            .product-info form input[type="number"] {
                width: 100px !important;
                padding: 10px 12px !important;
                font-size: 13px !important;
            }
            .product-info form button[type="submit"] {
                padding: 12px 24px !important;
                font-size: 12px !important;
            }
            .product-description {
                margin-top: 32px !important;
                padding-top: 24px !important;
            }
            .product-description h2 {
                font-size: 16px !important;
                margin-bottom: 16px !important;
            }
            .product-description > div {
                font-size: 13px !important;
                line-height: 1.7 !important;
            }
        }
        @media (max-width: 480px) {
            .page-header {
                margin-bottom: 20px !important;
                padding-bottom: 16px !important;
            }
            .page-title {
                font-size: 20px !important;
                margin-bottom: 6px !important;
            }
            .page-lead {
                font-size: 11px !important;
            }
            .product-detail-grid {
                gap: 16px !important;
                margin-bottom: 24px !important;
            }
            .product-image {
                height: 250px !important;
            }
            .product-price {
                font-size: 22px !important;
                margin-bottom: 16px !important;
            }
            .product-info > div[style*="margin: 0 0 32px"] {
                margin-bottom: 16px !important;
                padding: 12px !important;
            }
            .product-info > div[style*="padding: 20px"] p[style*="font-size: 16px"] {
                font-size: 13px !important;
            }
            .product-info > div[style*="padding: 20px"] p[style*="font-size: 13px"] {
                font-size: 11px !important;
                margin-top: 6px !important;
            }
            .product-info > p[style*="padding: 12px 16px"] {
                padding: 8px 10px !important;
                font-size: 11px !important;
                margin-bottom: 16px !important;
            }
            .product-info form > div[style*="margin-bottom: 24px"] {
                margin-bottom: 12px !important;
            }
            .product-info form label {
                font-size: 10px !important;
                margin-bottom: 4px !important;
            }
            .product-info form input[type="number"] {
                width: 90px !important;
                padding: 8px 10px !important;
                font-size: 12px !important;
            }
            .product-info form button[type="submit"] {
                padding: 10px 20px !important;
                font-size: 11px !important;
            }
            .product-description {
                margin-top: 24px !important;
                padding-top: 20px !important;
            }
            .product-description h2 {
                font-size: 15px !important;
                margin-bottom: 12px !important;
            }
            .product-description > div {
                font-size: 12px !important;
                line-height: 1.6 !important;
            }
        }
    </style>
@endsection

