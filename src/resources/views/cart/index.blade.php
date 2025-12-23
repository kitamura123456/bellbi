@extends('layouts.app')

@section('title', 'カート | Bellbi')

@section('sidebar')
    {{-- サイドバーなし --}}
@endsection

@section('content')
    <style>
        /* カートページのみ：サイドバーを非表示にしてコンテンツを中央寄せ */
        @media (min-width: 769px) {
            .cart-page-wrapper .main-inner {
                justify-content: center !important;
            }
            .cart-page-wrapper .sidebar {
                display: none !important;
            }
            .cart-page-wrapper .content {
                max-width: 1200px !important;
                width: 100% !important;
                margin: 0 auto !important;
            }
        }
    </style>
    <script>
        // ページ読み込み時にbodyにクラスを追加
        (function() {
            document.body.classList.add('cart-page-wrapper');
        })();
    </script>
    @if (session('status'))
        <div style="
            background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
            border: 1px solid #1a1a1a;
            border-radius: 0;
            color: #ffffff;
            padding: 16px 24px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.3s ease-out;
        ">
            <span style="
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 24px;
                height: 24px;
                background: #ffffff;
                color: #1a1a1a;
                border-radius: 50%;
                font-size: 14px;
                font-weight: 700;
                flex-shrink: 0;
            ">✓</span>
            <span style="flex: 1;">{{ session('status') }}</span>
        </div>
        <style>
            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    @endif

    @if (session('error'))
        <div style="
            background: #fff5f5;
            border: 1px solid #feb2b2;
            border-radius: 0;
            color: #c53030;
            padding: 16px 24px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            box-shadow: 0 2px 8px rgba(197, 48, 48, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.3s ease-out;
        ">
            <span style="
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 24px;
                height: 24px;
                background: #c53030;
                color: #ffffff;
                border-radius: 50%;
                font-size: 14px;
                font-weight: 700;
                flex-shrink: 0;
            ">×</span>
            <span style="flex: 1;">{{ session('error') }}</span>
        </div>
    @endif

    <header class="page-header" style="margin-bottom: 48px; padding-bottom: 32px; border-bottom: 1px solid #f0f0f0;">
        <h1 class="page-title" style="font-size: 32px; font-weight: 400; color: #1a1a1a; margin: 0 0 12px 0; letter-spacing: -0.02em; line-height: 1.3; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">カート</h1>
    </header>

    @if(empty($items))
        <div style="text-align: center; padding: 64px 0;">
            <p style="font-size: 14px; color: #999; margin: 0 0 24px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">カートに商品がありません。</p>
            <a href="{{ route('shops.index') }}" style="
                padding: 10px 24px;
                background: #1a1a1a;
                color: #ffffff;
                border: none;
                border-radius: 0;
                font-size: 13px;
                font-weight: 500;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.3s ease;
                display: inline-block;
                letter-spacing: 0.05em;
                text-transform: uppercase;
            " onmouseover="this.style.backgroundColor='#000000';" onmouseout="this.style.backgroundColor='#1a1a1a';">
                ショップへ戻る
            </a>
        </div>
    @else
        <div style="max-width: 1200px; margin: 0 auto;">
            <div style="margin-bottom: 48px;">
                @foreach($items as $item)
                    @php
                        $product = $item['product'];
                    @endphp
                    <div style="
                        padding: 24px;
                        border-bottom: 1px solid #f0f0f0;
                        display: flex;
                        gap: 24px;
                    ">
                        <div style="width: 120px; height: 120px; background: #fafafa; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 12px;">
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
                                <a href="{{ route('shops.show', $product) }}" style="text-decoration: none; color: inherit;">{{ $product->name }}</a>
                            </h3>
                            <p style="
                                margin: 0 0 12px 0;
                                font-size: 13px;
                                color: #666;
                                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            ">
                                @if($product->shop && $product->shop->company)
                                    {{ $product->shop->company->name }}
                                @endif
                            </p>
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <form method="POST" action="{{ route('cart.update', $product) }}" style="display: flex; align-items: center; gap: 8px;">
                                    @csrf
                                    @method('PUT')
                                    <label for="quantity-{{ $product->id }}" style="font-size: 13px; color: #666;">数量:</label>
                                    <input type="number" id="quantity-{{ $product->id }}" name="quantity" value="{{ $item['quantity'] }}" min="0" max="{{ $product->stock }}" style="
                                        width: 80px;
                                        padding: 8px 12px;
                                        border: 1px solid #e0e0e0;
                                        border-radius: 0;
                                        font-size: 14px;
                                        background: #ffffff;
                                        color: #1a1a1a;
                                    " onchange="this.form.submit();">
                                </form>
                                <span style="
                                    font-size: 16px;
                                    font-weight: 400;
                                    color: #1a1a1a;
                                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                                ">
                                    ¥{{ number_format($item['subtotal']) }}
                                </span>
                                <form method="POST" action="{{ route('cart.remove', $product) }}" style="margin-left: auto;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="
                                        padding: 6px 12px;
                                        background: transparent;
                                        color: #999;
                                        border: 1px solid #e0e0e0;
                                        border-radius: 0;
                                        font-size: 12px;
                                        font-weight: 400;
                                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                                        cursor: pointer;
                                        transition: all 0.3s ease;
                                    " onmouseover="this.style.borderColor='#1a1a1a'; this.style.color='#1a1a1a';" onmouseout="this.style.borderColor='#e0e0e0'; this.style.color='#999';">
                                        削除
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="
                padding: 32px;
                background: #fafafa;
                border: 1px solid #e0e0e0;
                margin-bottom: 32px;
            ">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <span style="
                        font-size: 18px;
                        font-weight: 400;
                        color: #1a1a1a;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    ">合計</span>
                    <span style="
                        font-size: 24px;
                        font-weight: 400;
                        color: #1a1a1a;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    ">¥{{ number_format($total) }}</span>
                </div>
                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('shops.index') }}" style="
                        flex: 1;
                        padding: 14px 24px;
                        background: transparent;
                        color: #1a1a1a;
                        border: 1px solid #1a1a1a;
                        border-radius: 0;
                        font-size: 13px;
                        font-weight: 500;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        text-decoration: none;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        text-align: center;
                        letter-spacing: 0.05em;
                        text-transform: uppercase;
                    " onmouseover="this.style.backgroundColor='#f5f5f5';" onmouseout="this.style.backgroundColor='transparent';">
                        買い物を続ける
                    </a>
                    @auth
                        <a href="{{ route('orders.checkout') }}" style="
                            flex: 1;
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
                            text-align: center;
                            letter-spacing: 0.05em;
                            text-transform: uppercase;
                        " onmouseover="this.style.backgroundColor='#000000'; this.style.borderColor='#000000';" onmouseout="this.style.backgroundColor='#1a1a1a'; this.style.borderColor='#1a1a1a';">
                            注文に進む
                        </a>
                    @else
                        <a href="{{ route('orders.checkout') }}" style="
                            flex: 1;
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
                            text-align: center;
                            letter-spacing: 0.05em;
                            text-transform: uppercase;
                        " onmouseover="this.style.backgroundColor='#000000'; this.style.borderColor='#000000';" onmouseout="this.style.backgroundColor='#1a1a1a'; this.style.borderColor='#1a1a1a';">
                            ログインして注文
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    @endif
@endsection

