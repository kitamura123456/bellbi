@extends('layouts.company')

@section('title', 'プラン・課金管理')

@section('content')
<div style="margin-bottom: 24px; margin-top: 32px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">プラン・課金管理</h1>
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

@if($activeSubscription)
<div style="
    padding: 24px;
    background: #ffffff;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    margin-bottom: 24px;
">
    <h2 style="margin: 0 0 16px 0; font-size: 20px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">現在の契約プラン</h2>
    <div style="
        padding: 20px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 0;
        margin-bottom: 16px;
    ">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                {{ $activeSubscription->plan->name }}
            </h3>
            <span style="
                padding: 4px 12px;
                background: {{ $activeSubscription->status === \App\Models\Subscription::STATUS_TRIAL ? '#fef3c7' : '#d1fae5' }};
                color: {{ $activeSubscription->status === \App\Models\Subscription::STATUS_TRIAL ? '#92400e' : '#065f46' }};
                border-radius: 999px;
                font-size: 12px;
                font-weight: 700;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                @if($activeSubscription->status === \App\Models\Subscription::STATUS_TRIAL)
                    トライアル中
                @else
                    有効
                @endif
            </span>
        </div>
        <div style="margin-bottom: 8px;">
            <span style="font-size: 24px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                ¥{{ number_format($activeSubscription->plan->price_monthly) }}
            </span>
            <span style="font-size: 14px; color: #666666; margin-left: 4px;">/月</span>
        </div>
        @if($activeSubscription->started_at)
        <div style="font-size: 13px; color: #666666; margin-bottom: 4px;">
            契約開始日: {{ $activeSubscription->started_at->format('Y年m月d日') }}
        </div>
        @endif
        @if($activeSubscription->next_billing_at)
        <div style="font-size: 13px; color: #666666;">
            次回請求日: {{ $activeSubscription->next_billing_at->format('Y年m月d日') }}
        </div>
        @endif
    </div>
    <div style="display: flex; gap: 12px;">
        <a href="#plans" style="
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
            プランを変更
        </a>
        <form method="POST" action="{{ route('company.plans.cancel') }}" style="display: inline-block;" onsubmit="return confirm('本当にプランを解約しますか？');">
            @csrf
            <button type="submit" style="
                padding: 10px 20px;
                background: #fee;
                color: #c33;
                border: 1px solid #fcc;
                border-radius: 24px;
                font-size: 14px;
                font-weight: 700;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                cursor: pointer;
                transition: all 0.2s ease;
            " onmouseover="this.style.background='#fdd';" onmouseout="this.style.background='#fee';">
                プランを解約
            </button>
        </form>
    </div>
</div>
@else
<div style="
    padding: 24px;
    background: #fffbf0;
    border: 1px solid #fef3c7;
    border-radius: 0;
    margin-bottom: 24px;
">
    <p style="margin: 0; font-size: 14px; color: #92400e; font-weight: 600; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
        現在有効な契約プランがありません。プランを選択して契約してください。
    </p>
</div>
@endif

<div id="plans" style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">利用可能なプラン</h3>
    </div>
    <div style="padding: 24px;">
        @if($plans->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
            @foreach($plans as $plan)
            <div style="
                padding: 24px;
                background: {{ $activeSubscription && $activeSubscription->plan_id === $plan->id ? '#f0f9ff' : '#ffffff' }};
                border: 2px solid {{ $activeSubscription && $activeSubscription->plan_id === $plan->id ? '#5D535E' : '#e5e7eb' }};
                border-radius: 0;
                transition: all 0.2s ease;
            " onmouseover="this.style.boxShadow='0 2px 8px rgba(93, 83, 94, 0.15)';" onmouseout="this.style.boxShadow='none';">
                <h4 style="margin: 0 0 12px 0; font-size: 18px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                    {{ $plan->name }}
                </h4>
                @if($plan->description)
                <p style="margin: 0 0 12px 0; font-size: 13px; color: #666666; line-height: 1.6; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                    {{ $plan->description }}
                </p>
                @endif
                <div style="margin-bottom: 16px;">
                    <span style="font-size: 28px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                        ¥{{ number_format($plan->price_monthly) }}
                    </span>
                    <span style="font-size: 14px; color: #666666; margin-left: 4px;">/月</span>
                </div>
                @if($activeSubscription && $activeSubscription->plan_id === $plan->id)
                <div style="
                    padding: 8px 12px;
                    background: #d1fae5;
                    color: #065f46;
                    border-radius: 4px;
                    font-size: 12px;
                    font-weight: 700;
                    text-align: center;
                    margin-bottom: 16px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">
                    現在のプラン
                </div>
                @endif
                <a href="{{ route('company.plans.show', $plan) }}" style="
                    display: block;
                    padding: 12px 20px;
                    background: {{ $activeSubscription && $activeSubscription->plan_id === $plan->id ? '#e5e7eb' : '#5D535E' }};
                    color: {{ $activeSubscription && $activeSubscription->plan_id === $plan->id ? '#666666' : '#ffffff' }};
                    border: none;
                    border-radius: 24px;
                    font-size: 14px;
                    font-weight: 700;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    text-decoration: none;
                    text-align: center;
                    cursor: {{ $activeSubscription && $activeSubscription->plan_id === $plan->id ? 'default' : 'pointer' }};
                    transition: all 0.2s ease;
                " @if($activeSubscription && $activeSubscription->plan_id === $plan->id) onclick="return false;" @endif>
                    @if($activeSubscription && $activeSubscription->plan_id === $plan->id)
                        現在のプラン
                    @else
                        詳細を見る
                    @endif
                </a>
            </div>
            @endforeach
        </div>
        @else
        <p style="margin: 0; color: #666666; font-size: 14px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
            利用可能なプランがありません。
        </p>
        @endif
    </div>
</div>

<style>
/* スマホ用レスポンシブデザイン */
@media (max-width: 768px) {
    div[style*="margin-top: 32px"] {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 12px !important;
    }

    div[style*="margin-top: 32px"] h1 {
        font-size: 20px !important;
        margin-bottom: 0 !important;
    }

    div[style*="padding: 24px"] {
        padding: 16px !important;
    }

    div[style*="display: flex; gap: 12px"] {
        flex-direction: column !important;
        gap: 8px !important;
    }

    div[style*="display: flex; gap: 12px"] a,
    div[style*="display: flex; gap: 12px"] button {
        width: 100%;
        text-align: center;
        font-size: 13px;
        padding: 10px 16px;
    }

    div[style*="grid-template-columns: repeat"] {
        grid-template-columns: 1fr !important;
        gap: 16px !important;
    }

    div[style*="padding: 24px; background"] {
        padding: 16px !important;
    }

    div[style*="padding: 24px; background"] h4 {
        font-size: 16px !important;
    }

    div[style*="font-size: 28px"] {
        font-size: 24px !important;
    }

    div[style*="display: block"] {
        font-size: 13px !important;
        padding: 10px 16px !important;
    }
}

@media (max-width: 480px) {
    div[style*="padding: 24px"] {
        padding: 12px !important;
    }

    div[style*="font-size: 28px"] {
        font-size: 20px !important;
    }
}
</style>
@endsection

