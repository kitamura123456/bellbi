@extends('layouts.company')

@section('title', 'プラン詳細: ' . $plan->name)

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">プラン詳細</h1>
    <a href="{{ route('company.plans.index') }}" style="
        padding: 12px 24px;
        background: #f3f4f6;
        color: #5D535E;
        border: 1px solid #e5e7eb;
        border-radius: 24px;
        font-size: 14px;
        font-weight: 700;
        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    " onmouseover="this.style.background='#e5e7eb';" onmouseout="this.style.background='#f3f4f6';">
        一覧に戻る
    </a>
</div>

@if (session('error'))
    <div style="
        background-color: #fee;
        border: 1px solid #fcc;
        border-radius: 8px;
        color: #c33;
        padding: 12px 16px;
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
    ">
        {{ session('error') }}
    </div>
@endif

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
    margin-bottom: 24px;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">プラン情報</h3>
    </div>
    <div style="padding: 24px;">
        <div style="margin-bottom: 24px;">
            <h4 style="margin: 0 0 12px 0; font-size: 20px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                {{ $plan->name }}
            </h4>
            @if($plan->description)
            <div style="
                padding: 16px;
                background: #f9fafb;
                border-left: 3px solid #5D535E;
                margin-bottom: 16px;
                border-radius: 0;
            ">
                <p style="margin: 0; font-size: 14px; color: #2A3132; line-height: 1.7; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif; white-space: pre-wrap;">
                    {{ $plan->description }}
                </p>
            </div>
            @endif
            <div style="margin-bottom: 16px;">
                <span style="font-size: 32px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                    ¥{{ number_format($plan->price_monthly) }}
                </span>
                <span style="font-size: 16px; color: #666666; margin-left: 4px;">/月</span>
            </div>
        </div>

        @if($activeSubscription && $activeSubscription->plan_id === $plan->id)
        <div style="
            padding: 16px;
            background: #f0f9ff;
            border: 1px solid #5D535E;
            border-radius: 0;
            margin-bottom: 24px;
        ">
            <p style="margin: 0; font-size: 14px; color: #5D535E; font-weight: 600; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                このプランは現在契約中です。
            </p>
        </div>
        @elseif($activeSubscription && $activeSubscription->plan_id !== $plan->id)
        <div style="
            padding: 16px;
            background: #fffbf0;
            border: 1px solid #fef3c7;
            border-radius: 0;
            margin-bottom: 24px;
        ">
            <p style="margin: 0 0 12px 0; font-size: 14px; color: #92400e; font-weight: 600; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                現在「{{ $activeSubscription->plan->name }}」プランを契約中です。このプランに変更しますか？
            </p>
            <a href="{{ route('company.plans.change', $plan) }}" style="
                display: inline-block;
                padding: 10px 20px;
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
                プランを変更する
            </a>
        </div>
        @else
        <div style="margin-bottom: 24px;">
            <h4 style="margin: 0 0 16px 0; font-size: 16px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                お支払い方法を選択
            </h4>
            
            <form method="POST" action="{{ route('company.plans.stripe.session', $plan) }}" style="margin-bottom: 16px;">
                @csrf
                <button type="submit" style="
                    width: 100%;
                    padding: 14px 32px;
                    background: #635BFF;
                    color: #ffffff;
                    border: none;
                    border-radius: 24px;
                    font-size: 16px;
                    font-weight: 700;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    cursor: pointer;
                    transition: all 0.2s ease;
                " onmouseover="this.style.backgroundColor='#5851EA';" onmouseout="this.style.backgroundColor='#635BFF';">
                    オンライン決済で契約する
                </button>
            </form>

        </div>
        @endif
    </div>
</div>
@endsection

