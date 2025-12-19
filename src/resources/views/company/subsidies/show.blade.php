@extends('layouts.company')

@section('title', $subsidy->title)

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <a href="{{ route('company.subsidies.index') }}" style="
            font-size: 14px;
            color: #5D535E;
            text-decoration: none;
            margin-bottom: 8px;
            display: inline-block;
        " onmouseover="this.style.color='#90AFC5';" onmouseout="this.style.color='#5D535E';">
            ← 補助金一覧に戻る
        </a>
        <h1 style="margin: 8px 0 0 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">{{ $subsidy->title }}</h1>
    </div>
    <span style="
        display: inline-block;
        padding: 6px 16px;
        background: {{ $subsidy->status == 1 ? '#10b981' : ($subsidy->status == 2 ? '#6b7280' : '#f59e0b') }};
        color: #ffffff;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    ">
        {{ $subsidy->status_name }}
    </span>
</div>

<div style="
    padding: 24px;
    background: #ffffff;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    margin-bottom: 24px;
">
    <h2 style="
        margin: 0 0 16px 0;
        font-size: 18px;
        font-weight: 700;
        color: #5D535E;
        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
        padding-bottom: 12px;
        border-bottom: 2px solid #e8e8e8;
    ">基本情報</h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 24px;">
        @if($subsidy->category)
        <div>
            <p style="margin: 0 0 4px 0; font-size: 13px; color: #6b7280; font-weight: 600;">カテゴリ</p>
            <p style="margin: 0; font-size: 15px; color: #2A3132; font-weight: 500;">{{ $subsidy->category_name }}</p>
        </div>
        @endif

        <div>
            <p style="margin: 0 0 4px 0; font-size: 13px; color: #6b7280; font-weight: 600;">対象地域</p>
            <p style="margin: 0; font-size: 15px; color: #2A3132; font-weight: 500;">
                @if($subsidy->target_region)
                    @php
                        $region = \App\Enums\Todofuken::tryFrom($subsidy->target_region);
                    @endphp
                    @if($region)
                        {{ $region->label() }}
                    @else
                        全国
                    @endif
                @else
                    全国
                @endif
            </p>
        </div>

        @if($subsidy->applicable_industry_type)
        <div>
            <p style="margin: 0 0 4px 0; font-size: 13px; color: #6b7280; font-weight: 600;">対象業種</p>
            <p style="margin: 0; font-size: 15px; color: #2A3132; font-weight: 500;">
                @php
                    $industryTypes = \App\Services\BusinessCategoryService::getIndustryTypes();
                @endphp
                {{ $industryTypes[$subsidy->applicable_industry_type] ?? '全業種' }}
            </p>
        </div>
        @endif
    </div>

    @if($subsidy->application_start_at || $subsidy->application_end_at)
    <div style="
        padding: 16px;
        background: #f0fdf4;
        border: 1px solid #86efac;
        border-radius: 12px;
        margin-bottom: 24px;
    ">
        <p style="margin: 0 0 8px 0; font-size: 14px; color: #166534; font-weight: 600;">申請期間</p>
        <p style="margin: 0; font-size: 16px; color: #166534; font-weight: 500;">
            @if($subsidy->application_start_at && $subsidy->application_end_at)
                {{ $subsidy->application_start_at->format('Y年m月d日') }} ～ {{ $subsidy->application_end_at->format('Y年m月d日') }}
            @elseif($subsidy->application_start_at)
                {{ $subsidy->application_start_at->format('Y年m月d日') }} 以降
            @elseif($subsidy->application_end_at)
                {{ $subsidy->application_end_at->format('Y年m月d日') }} まで
            @endif
        </p>
    </div>
    @endif
</div>

@if($subsidy->summary)
<div style="
    padding: 24px;
    background: #ffffff;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    margin-bottom: 24px;
">
    <h2 style="
        margin: 0 0 16px 0;
        font-size: 18px;
        font-weight: 700;
        color: #5D535E;
        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
        padding-bottom: 12px;
        border-bottom: 2px solid #e8e8e8;
    ">概要</h2>
    <div style="
        font-size: 15px;
        color: #2A3132;
        line-height: 1.8;
        white-space: pre-wrap;
    ">{{ $subsidy->summary }}</div>
</div>
@endif

@if($subsidy->detail_url)
<div style="
    padding: 24px;
    background: #ffffff;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    margin-bottom: 24px;
    text-align: center;
">
    <a href="{{ $subsidy->detail_url }}" target="_blank" rel="noopener noreferrer" style="
        padding: 14px 32px;
        background: #5D535E;
        color: #ffffff;
        border: none;
        border-radius: 24px;
        font-size: 15px;
        font-weight: 700;
        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-block;
    " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
        公式サイトで詳細を確認する →
    </a>
    <p style="margin: 12px 0 0 0; font-size: 13px; color: #6b7280;">外部サイトに移動します</p>
</div>
@endif

@endsection

