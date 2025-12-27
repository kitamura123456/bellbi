@extends('layouts.app')

@section('title', '注文完了 | Bellbi')

@section('sidebar')
    {{-- サイドバーなし --}}
@endsection

@section('content')
    <style>
        /* 注文完了ページのみ：サイドバーを非表示にしてコンテンツを中央寄せ */
        @media (min-width: 769px) {
            .order-complete-page-wrapper .main-inner {
                justify-content: center !important;
            }
            .order-complete-page-wrapper .sidebar {
                display: none !important;
            }
            .order-complete-page-wrapper .content {
                max-width: 1200px !important;
                width: 100% !important;
                margin: 0 auto !important;
            }
        }
    </style>
    <script>
        // ページ読み込み時にbodyにクラスを追加
        (function() {
            document.body.classList.add('order-complete-page-wrapper');
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

    <div style="max-width: 800px; margin: 0 auto; text-align: center; padding: 64px 24px;">
        <div style="margin-bottom: 32px;">
            <h1 style="
                margin: 0 0 16px 0;
                font-size: 32px;
                font-weight: 400;
                color: #1a1a1a;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            ">
                注文が確定しました
            </h1>
            <p style="
                margin: 0;
                font-size: 14px;
                color: #666;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            ">
                注文番号: {{ $order->id }}
            </p>
        </div>

        <div style="
            padding: 32px;
            background: #fafafa;
            border: 1px solid #e0e0e0;
            margin-bottom: 32px;
            text-align: left;
        ">
            <h2 style="
                margin: 0 0 24px 0;
                font-size: 18px;
                font-weight: 400;
                color: #1a1a1a;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            ">注文内容</h2>
            @foreach($order->orderItems as $orderItem)
                <div style="
                    padding: 16px 0;
                    border-bottom: 1px solid #e0e0e0;
                ">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <p style="
                                margin: 0 0 4px 0;
                                font-size: 14px;
                                font-weight: 400;
                                color: #1a1a1a;
                                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            ">
                                {{ $orderItem->product->name }}
                            </p>
                            <p style="
                                margin: 0;
                                font-size: 12px;
                                color: #666;
                                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            ">
                                数量: {{ $orderItem->quantity }} × ¥{{ number_format($orderItem->unit_price) }}
                            </p>
                        </div>
                        <span style="
                            font-size: 16px;
                            font-weight: 400;
                            color: #1a1a1a;
                            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        ">
                            ¥{{ number_format($orderItem->quantity * $orderItem->unit_price) }}
                        </span>
                    </div>
                </div>
            @endforeach
            <div style="
                padding-top: 16px;
                margin-top: 16px;
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
                ">合計</span>
                <span style="
                    font-size: 24px;
                    font-weight: 400;
                    color: #1a1a1a;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                ">¥{{ number_format($order->total_amount) }}</span>
            </div>
        </div>

        <div style="
            padding: 24px;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            margin-bottom: 32px;
            text-align: left;
        ">
            <p style="
                margin: 0 0 12px 0;
                font-size: 13px;
                font-weight: 500;
                color: #1a1a1a;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            ">
                次のステップ
            </p>
            <p style="
                margin: 0;
                font-size: 13px;
                color: #666;
                line-height: 1.7;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            ">
                店頭でお支払いの際は、注文番号「<strong>{{ $order->id }}</strong>」とお名前「<strong>{{ $order->user->name }}</strong>」をお伝えください。
            </p>
        </div>

        <div style="display: flex; gap: 12px; justify-content: center;">
            <a href="{{ route('shops.index') }}" style="
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
                letter-spacing: 0.05em;
                text-transform: uppercase;
            " onmouseover="this.style.backgroundColor='#f5f5f5';" onmouseout="this.style.backgroundColor='transparent';">
                ショップへ戻る
            </a>
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
            " onmouseover="this.style.backgroundColor='#000000'; this.style.borderColor='#000000';" onmouseout="this.style.backgroundColor='#1a1a1a'; this.style.borderColor='#1a1a1a';">
                注文履歴を見る
            </a>
        </div>
    </div>
@endsection

