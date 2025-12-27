@extends('layouts.company')

@section('title', '補助金情報')

@section('content')
<div style="margin-bottom: 24px; margin-top: 32px;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">補助金情報</h1>
    <p style="margin: 16px 0 0 0; font-size: 14px; color: #666666; line-height: 1.5;">事業者向けの補助金情報を検索・閲覧できます。</p>
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
    margin-bottom: 24px;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">検索条件</h3>
    </div>
    <form action="{{ route('company.subsidies.index') }}" method="GET" style="padding: 24px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 16px;">
            <div>
                <label for="keyword" style="
                    display: block;
                    margin-bottom: 8px;
                    font-size: 13px;
                    font-weight: 700;
                    color: #5D535E;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">キーワード</label>
                <input type="text" name="keyword" id="keyword" value="{{ $keyword }}" placeholder="補助金名や内容で検索" style="
                    width: 100%;
                    padding: 12px 16px;
                    border: 1px solid #e8e8e8;
                    border-radius: 12px;
                    font-size: 14px;
                    font-family: inherit;
                    color: #2A3132;
                    background: #fafafa;
                    transition: all 0.2s ease;
                    box-sizing: border-box;
                " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
            </div>

            <div>
                <label for="category" style="
                    display: block;
                    margin-bottom: 8px;
                    font-size: 13px;
                    font-weight: 700;
                    color: #5D535E;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">カテゴリ</label>
                <select name="category" id="category" style="
                    width: 100%;
                    padding: 12px 16px;
                    border: 1px solid #e8e8e8;
                    border-radius: 12px;
                    font-size: 14px;
                    font-family: inherit;
                    color: #2A3132;
                    background: #fafafa;
                    transition: all 0.2s ease;
                    box-sizing: border-box;
                " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
                    <option value="">すべて</option>
                    <option value="1" {{ $category == 1 ? 'selected' : '' }}>設備投資</option>
                    <option value="2" {{ $category == 2 ? 'selected' : '' }}>人材確保</option>
                    <option value="3" {{ $category == 3 ? 'selected' : '' }}>事業継続</option>
                    <option value="4" {{ $category == 4 ? 'selected' : '' }}>その他</option>
                </select>
            </div>

            <div>
                <label for="target_region" style="
                    display: block;
                    margin-bottom: 8px;
                    font-size: 13px;
                    font-weight: 700;
                    color: #5D535E;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">対象地域</label>
                <select name="target_region" id="target_region" style="
                    width: 100%;
                    padding: 12px 16px;
                    border: 1px solid #e8e8e8;
                    border-radius: 12px;
                    font-size: 14px;
                    font-family: inherit;
                    color: #2A3132;
                    background: #fafafa;
                    transition: all 0.2s ease;
                    box-sizing: border-box;
                " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
                    <option value="">すべて</option>
                    <option value="0" {{ $targetRegion == 0 ? 'selected' : '' }}>全国</option>
                    @foreach ($prefectures as $pref)
                        <option value="{{ $pref->value }}" {{ $targetRegion == $pref->value ? 'selected' : '' }}>
                            {{ $pref->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" style="
                    display: block;
                    margin-bottom: 8px;
                    font-size: 13px;
                    font-weight: 700;
                    color: #5D535E;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">ステータス</label>
                <select name="status" id="status" style="
                    width: 100%;
                    padding: 12px 16px;
                    border: 1px solid #e8e8e8;
                    border-radius: 12px;
                    font-size: 14px;
                    font-family: inherit;
                    color: #2A3132;
                    background: #fafafa;
                    transition: all 0.2s ease;
                    box-sizing: border-box;
                " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
                    <option value="">すべて</option>
                    <option value="1" {{ $status == 1 ? 'selected' : '' }}>募集中</option>
                    <option value="2" {{ $status == 2 ? 'selected' : '' }}>締切</option>
                    <option value="3" {{ $status == 3 ? 'selected' : '' }}>未開始</option>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit" style="
                padding: 12px 24px;
                background: #5D535E;
                color: #ffffff;
                border: none;
                border-radius: 24px;
                font-size: 14px;
                font-weight: 700;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                cursor: pointer;
                transition: all 0.2s ease;
                position: relative;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                検索
            </button>
            <a href="{{ route('company.subsidies.index') }}" style="
                padding: 12px 24px;
                background: transparent;
                color: #5D535E;
                border: 1px solid #5D535E;
                border-radius: 24px;
                font-size: 14px;
                font-weight: 700;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.2s ease;
                position: relative;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                クリア
            </a>
        </div>
    </form>
</div>

@if ($subsidies->count() > 0)
    @foreach ($subsidies as $subsidy)
    <div style="
        padding: 24px;
        background: #ffffff;
        border: none;
        box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
        border-radius: 0;
        margin-bottom: 16px;
        transition: all 0.2s ease;
    " onmouseover="this.style.boxShadow='0 2px 4px rgba(93, 83, 94, 0.15)';" onmouseout="this.style.boxShadow='0 1px 2px rgba(93, 83, 94, 0.1)';">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
            <h3 style="
                margin: 0;
                font-size: 18px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                <a href="{{ route('company.subsidies.show', $subsidy) }}" style="
                    color: #5D535E;
                    text-decoration: none;
                    transition: color 0.2s ease;
                " onmouseover="this.style.color='#90AFC5';" onmouseout="this.style.color='#5D535E';">
                    {{ $subsidy->title }}
                </a>
            </h3>
            <span style="
                display: inline-block;
                padding: 4px 12px;
                background: {{ $subsidy->status == 1 ? '#10b981' : ($subsidy->status == 2 ? '#6b7280' : '#f59e0b') }};
                color: #ffffff;
                border-radius: 12px;
                font-size: 12px;
                font-weight: 500;
                white-space: nowrap;
            ">
                {{ $subsidy->status_name }}
            </span>
        </div>

        @if($subsidy->summary)
        <p style="
            margin: 0 0 12px 0;
            font-size: 14px;
            color: #666666;
            line-height: 1.6;
        ">{{ Str::limit($subsidy->summary, 150) }}</p>
        @endif

        <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 16px;">
            @if($subsidy->category)
            <span style="
                display: inline-block;
                padding: 4px 12px;
                background: #f3f4f6;
                color: #5D535E;
                border-radius: 12px;
                font-size: 12px;
                font-weight: 500;
            ">{{ $subsidy->category_name }}</span>
            @endif

            @if($subsidy->target_region)
            <span style="
                display: inline-block;
                padding: 4px 12px;
                background: #f3f4f6;
                color: #5D535E;
                border-radius: 12px;
                font-size: 12px;
                font-weight: 500;
            ">
                @php
                    $region = Todofuken::tryFrom($subsidy->target_region);
                @endphp
                @if($region)
                    {{ $region->label() }}
                @else
                    全国
                @endif
            </span>
            @else
            <span style="
                display: inline-block;
                padding: 4px 12px;
                background: #f3f4f6;
                color: #5D535E;
                border-radius: 12px;
                font-size: 12px;
                font-weight: 500;
            ">全国</span>
            @endif

            @if($subsidy->applicable_industry_type)
            <span style="
                display: inline-block;
                padding: 4px 12px;
                background: #f3f4f6;
                color: #5D535E;
                border-radius: 12px;
                font-size: 12px;
                font-weight: 500;
            ">
                @php
                    $industryTypes = \App\Services\BusinessCategoryService::getIndustryTypes();
                @endphp
                {{ $industryTypes[$subsidy->applicable_industry_type] ?? '全業種' }}
            </span>
            @endif
        </div>

        @if($subsidy->application_start_at || $subsidy->application_end_at)
        <div style="
            margin-bottom: 12px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
        ">
            <p style="margin: 0; font-size: 13px; color: #666666;">
                <strong style="color: #5D535E;">申請期間：</strong>
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

        <div style="display: flex; gap: 12px;">
            <a href="{{ route('company.subsidies.show', $subsidy) }}" style="
                padding: 8px 16px;
                background: #5D535E;
                color: #ffffff;
                border: none;
                border-radius: 16px;
                font-size: 13px;
                font-weight: 700;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.2s ease;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                詳細を見る
            </a>
            @if($subsidy->detail_url)
            <a href="{{ $subsidy->detail_url }}" target="_blank" rel="noopener noreferrer" style="
                padding: 8px 16px;
                background: transparent;
                color: #5D535E;
                border: 1px solid #5D535E;
                border-radius: 16px;
                font-size: 13px;
                font-weight: 700;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.2s ease;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                公式サイトへ →
            </a>
            @endif
        </div>
    </div>
    @endforeach

    <div style="margin-top: 24px;">
        {{ $subsidies->appends(request()->query())->links() }}
    </div>
@else
    <div style="
        padding: 60px 24px;
        text-align: center;
        background: #ffffff;
        border: none;
        box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
        border-radius: 0;
    ">
        <p style="margin: 0 0 20px 0; color: #999999; font-size: 16px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">該当する補助金情報がありません。</p>
        <p style="margin: 0; color: #666666; font-size: 14px;">検索条件を変更して再度検索してください。</p>
    </div>
@endif

<style>
/* スマホ用レスポンシブデザイン */
@media (max-width: 768px) {
    div[style*="margin-top: 32px"] h1 {
        font-size: 20px !important;
    }

    div[style*="margin-top: 32px"] p {
        font-size: 13px !important;
    }

    div[style*="grid-template-columns: repeat"] {
        grid-template-columns: 1fr !important;
        gap: 12px !important;
    }

    div[style*="grid-template-columns: repeat"] input,
    div[style*="grid-template-columns: repeat"] select {
        font-size: 16px !important;
        padding: 10px 12px !important;
    }

    div[style*="display: flex; gap: 12px"] {
        flex-direction: column !important;
        gap: 8px !important;
    }

    div[style*="display: flex; gap: 12px"] button,
    div[style*="display: flex; gap: 12px"] a {
        width: 100%;
        text-align: center;
        font-size: 14px;
        padding: 12px 16px;
    }

    div[style*="padding: 24px"] {
        padding: 16px !important;
    }

    div[style*="display: flex; justify-content: space-between"] {
        flex-direction: column !important;
        gap: 12px !important;
    }

    div[style*="display: flex; justify-content: space-between"] h3 {
        font-size: 16px !important;
    }

    div[style*="display: flex; flex-wrap: wrap; gap: 12px"] {
        flex-direction: column !important;
        gap: 8px !important;
    }

    div[style*="display: flex; gap: 12px; margin-top: 20px"] {
        flex-direction: column !important;
        gap: 8px !important;
    }

    div[style*="display: flex; gap: 12px; margin-top: 20px"] a {
        width: 100%;
        text-align: center;
        font-size: 13px;
        padding: 10px 16px;
    }

    div[style*="display: flex; gap: 12px; justify-content: center"] {
        flex-direction: column !important;
        gap: 8px !important;
    }

    div[style*="display: flex; gap: 12px; justify-content: center"] a {
        width: 100%;
        text-align: center;
        font-size: 13px;
        padding: 10px 16px;
    }
}

@media (max-width: 480px) {
    div[style*="padding: 24px"] {
        padding: 12px !important;
    }

    div[style*="display: flex; justify-content: space-between"] h3 {
        font-size: 15px !important;
    }
}
</style>
@endsection

