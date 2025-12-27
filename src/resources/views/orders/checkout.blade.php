@extends('layouts.app')

@section('title', '注文確認 | Bellbi')

@section('sidebar')
    {{-- サイドバーなし --}}
@endsection

@section('content')
    <style>
        /* 注文確認ページのみ：サイドバーを非表示にしてコンテンツを中央寄せ */
        @media (min-width: 769px) {
            .checkout-page-wrapper .main-inner {
                justify-content: center !important;
            }
            .checkout-page-wrapper .sidebar {
                display: none !important;
            }
            .checkout-page-wrapper .content {
                max-width: 1200px !important;
                width: 100% !important;
                margin: 0 auto !important;
            }
        }
    </style>
    <script>
        // ページ読み込み時にbodyにクラスを追加
        (function() {
            document.body.classList.add('checkout-page-wrapper');
        })();
    </script>
    <header class="page-header" style="margin-bottom: 48px; padding-bottom: 32px; border-bottom: 1px solid #f0f0f0;">
        <h1 class="page-title" style="font-size: 32px; font-weight: 400; color: #1a1a1a; margin: 0 0 12px 0; letter-spacing: -0.02em; line-height: 1.3; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">注文確認</h1>
    </header>

    @if (session('error'))
        <div style="
            max-width: 1200px;
            margin: 0 auto 24px auto;
            padding: 16px 20px;
            background-color: #fee;
            border: 1px solid #fcc;
            border-radius: 4px;
            color: #c33;
            font-size: 14px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
        ">
            {{ session('error') }}
        </div>
    @endif

    @if (session('status'))
        <div style="
            max-width: 1200px;
            margin: 0 auto 24px auto;
            padding: 16px 20px;
            background-color: #efe;
            border: 1px solid #cfc;
            border-radius: 4px;
            color: #3c3;
            font-size: 14px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
        ">
            {{ session('status') }}
        </div>
    @endif

    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="margin-bottom: 48px;">
            <h2 style="
                margin: 0 0 24px 0;
                font-size: 18px;
                font-weight: 400;
                color: #1a1a1a;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            ">注文内容</h2>
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
                            {{ $product->name }}
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
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="
                                font-size: 13px;
                                color: #666;
                                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            ">
                                数量: {{ $item['quantity'] }} × ¥{{ number_format($product->price) }}
                            </span>
                            <span style="
                                font-size: 16px;
                                font-weight: 400;
                                color: #1a1a1a;
                                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            ">
                                ¥{{ number_format($item['subtotal']) }}
                            </span>
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
            <h2 style="
                margin: 0 0 24px 0;
                font-size: 18px;
                font-weight: 400;
                color: #1a1a1a;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            ">お届け先情報</h2>
            <div style="
                padding: 20px;
                background: #ffffff;
                border: 1px solid #e0e0e0;
                margin-bottom: 24px;
            ">
                <p style="
                    margin: 0 0 8px 0;
                    font-size: 14px;
                    color: #1a1a1a;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                ">
                    {{ $user->name }}
                </p>
                <p style="
                    margin: 0;
                    font-size: 13px;
                    color: #666;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                ">
                    {{ $user->email }}
                </p>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-top: 24px; border-top: 1px solid #e0e0e0;">
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
                ">¥{{ number_format($total) }}</span>
            </div>

            <div style="
                padding: 20px;
                background: #ffffff;
                border: 1px solid #e0e0e0;
                margin-bottom: 24px;
            ">
                <p style="
                    margin: 0 0 8px 0;
                    font-size: 13px;
                    font-weight: 500;
                    color: #1a1a1a;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                ">
                    お支払い方法
                </p>
                <p style="
                    margin: 0 0 8px 0;
                    font-size: 13px;
                    color: #666;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                ">
                    Stripe決済（クレジットカード・コンビニ決済・銀行振込）またはオフライン決済（店頭支払い等）から選択できます。
                </p>
                <p style="
                    margin: 0;
                    font-size: 12px;
                    color: #999;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                ">
                    ※コンビニ決済の場合、注文確定後3日以内にコンビニでお支払いください。<br>
                    ※銀行振込の場合、Stripe Checkoutで振込先情報が表示されます。入金確認まで数日かかる場合があります。
                </p>
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                
                <form method="POST" action="{{ route('orders.stripe.session') }}" id="stripe-payment-form">
                    @csrf
                    @if(!$hasValidEmail)
                    <input type="hidden" name="customer_email" value="" id="customer_email_hidden">
                    <input type="hidden" name="save_email_to_account" value="0" id="save_email_input">
                    @endif
                    <button type="submit" id="stripe-payment-button" style="
                        width: 100%;
                        padding: 14px 24px;
                        background: #635BFF;
                        color: #ffffff;
                        border: 1px solid #635BFF;
                        border-radius: 4px;
                        font-size: 14px;
                        font-weight: 500;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        letter-spacing: 0.05em;
                    " onmouseover="this.style.backgroundColor='#5851EA'; this.style.borderColor='#5851EA';" onmouseout="this.style.backgroundColor='#635BFF'; this.style.borderColor='#635BFF';">
                        Stripeで決済する
                    </button>
                </form>
                <script>
                    @if(!$hasValidEmail)
                    // チェックボックスの状態をhidden inputに反映
                    document.getElementById('save_email_checkbox').addEventListener('change', function() {
                        document.getElementById('save_email_input').value = this.checked ? '1' : '0';
                    });
                    @endif
                    (function() {
                        var form = document.getElementById('stripe-payment-form');
                        var button = document.getElementById('stripe-payment-button');
                        
                        if (!form || !button) {
                            console.error('Stripe payment form or button not found');
                            return;
                        }
                        
                        form.addEventListener('submit', function(e) {
                            @if(!$hasValidEmail)
                            // メールアドレスの検証とhidden inputへの設定
                            var emailInput = document.getElementById('customer_email_input');
                            var emailHidden = document.getElementById('customer_email_hidden');
                            if (!emailInput || !emailInput.value || !emailInput.validity.valid) {
                                e.preventDefault();
                                alert('有効なメールアドレスを入力してください。');
                                emailInput.focus();
                                return false;
                            }
                            // メールアドレスをhidden inputに設定
                            if (emailHidden) {
                                emailHidden.value = emailInput.value;
                            }
                            @endif
                            
                            button.disabled = true;
                            button.textContent = '処理中...';
                            button.style.opacity = '0.6';
                            button.style.cursor = 'not-allowed';
                        });
                    })();
                </script>

                <form method="POST" action="{{ route('orders.confirm') }}">
                    @csrf
                    <button type="submit" style="
                        width: 100%;
                        padding: 14px 24px;
                        background: transparent;
                        color: #1a1a1a;
                        border: 1px solid #1a1a1a;
                        border-radius: 4px;
                        font-size: 14px;
                        font-weight: 500;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        letter-spacing: 0.05em;
                    " onmouseover="this.style.backgroundColor='#f5f5f5';" onmouseout="this.style.backgroundColor='transparent';">
                        オフライン決済で注文を確定する
                    </button>
                </form>

                <a href="{{ route('cart.index') }}" style="
                    display: block;
                    width: 100%;
                    padding: 14px 24px;
                    background: transparent;
                    color: #1a1a1a;
                    border: 1px solid #e0e0e0;
                    border-radius: 4px;
                    font-size: 14px;
                    font-weight: 500;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    text-decoration: none;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    text-align: center;
                    letter-spacing: 0.05em;
                " onmouseover="this.style.backgroundColor='#f5f5f5';" onmouseout="this.style.backgroundColor='transparent';">
                    カートに戻る
                </a>
            </div>
        </div>
    </div>
@endsection

