@extends('layouts.app')

@section('title', $product->name . ' | Bellbi')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-bottom: 32px;">
            <div>
                <div style="width: 100%; height: 400px; background: #f3f4f6; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 16px; border: 1px solid #e5e7eb;">
                    商品画像
                </div>
            </div>
            <div>
                <h1 style="
                    margin: 0 0 16px 0;
                    font-size: 24px;
                    font-weight: 700;
                    color: #2A3132;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">{{ $product->name }}</h1>
                <p style="
                    margin: 0 0 16px 0;
                    font-size: 14px;
                    color: #6b7280;
                ">
                    @if($product->shop && $product->shop->company)
                        販売元: {{ $product->shop->company->name }}
                    @endif
                </p>
                <p style="
                    margin: 0 0 24px 0;
                    font-size: 32px;
                    font-weight: 700;
                    color: #5D535E;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">
                    ¥{{ number_format($product->price) }}
                </p>
                @if($product->status === \App\Models\Product::STATUS_OUT_OF_STOCK || $product->stock <= 0)
                    <div style="
                        margin: 0 0 24px 0;
                        padding: 16px;
                        background: #fee2e2;
                        border: 3px solid #dc2626;
                        border-radius: 12px;
                        text-align: center;
                    ">
                        <p style="
                            margin: 0;
                            font-size: 18px;
                            color: #dc2626;
                            font-weight: 700;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                        ">在庫切れ</p>
                        <p style="
                            margin: 8px 0 0 0;
                            font-size: 13px;
                            color: #991b1b;
                        ">現在この商品は在庫がありません</p>
                    </div>
                @elseif($product->stock < 10)
                    <p style="
                        margin: 0 0 24px 0;
                        padding: 12px;
                        background: #fef3c7;
                        color: #d97706;
                        border-radius: 8px;
                        font-weight: 600;
                    ">残り{{ $product->stock }}点</p>
                @else
                    <p style="
                        margin: 0 0 24px 0;
                        padding: 12px;
                        background: #d1fae5;
                        color: #059669;
                        border-radius: 8px;
                        font-weight: 600;
                    ">在庫あり</p>
                @endif
                @if($product->status !== \App\Models\Product::STATUS_OUT_OF_STOCK && $product->stock > 0)
                    <form method="POST" action="#" style="margin-bottom: 24px;">
                        @csrf
                        <div style="margin-bottom: 16px;">
                            <label for="quantity" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">数量</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" style="
                                width: 100px;
                                padding: 8px 12px;
                                border: 1px solid #e5e7eb;
                                border-radius: 8px;
                                font-size: 14px;
                            ">
                        </div>
                        <button type="submit" style="
                            width: 100%;
                            padding: 16px 24px;
                            background: #5D535E;
                            color: #ffffff;
                            border: none;
                            border-radius: 24px;
                            font-size: 16px;
                            font-weight: 700;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                            cursor: pointer;
                            transition: all 0.2s ease;
                        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                            カートに追加
                        </button>
                    </form>
                @endif
            </div>
        </div>
        <div style="margin-top: 32px;">
            <h2 style="
                margin: 0 0 16px 0;
                font-size: 20px;
                font-weight: 700;
                color: #2A3132;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">商品説明</h2>
            <div style="
                padding: 24px;
                background: #ffffff;
                border: 1px solid #e5e7eb;
                border-radius: 12px;
                line-height: 1.8;
                color: #374151;
            ">
                {!! nl2br(e($product->description ?? '商品説明はありません。')) !!}
            </div>
        </div>
    </div>
@endsection

