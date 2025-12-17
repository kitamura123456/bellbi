@extends('layouts.company')

@section('title', 'スカウト候補者検索')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">スカウト候補者検索</h1>
    <a href="{{ route('company.scouts.sent') }}" style="
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
        送信済みスカウト
    </a>
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
    margin-bottom: 24px;
">
    <form action="{{ route('company.scouts.search') }}" method="GET" style="padding: 24px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 12px; align-items: end;">
            <div style="margin-bottom: 0;">
                <label for="industry_type" style="
                    display: block;
                    margin-bottom: 8px;
                    font-size: 13px;
                    font-weight: 700;
                    color: #5D535E;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">業種</label>
                <select id="industry_type" name="industry_type" style="
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
                    <option value="1" {{ request('industry_type') == 1 ? 'selected' : '' }}>美容</option>
                    <option value="2" {{ request('industry_type') == 2 ? 'selected' : '' }}>医療</option>
                    <option value="3" {{ request('industry_type') == 3 ? 'selected' : '' }}>歯科</option>
                </select>
            </div>

            <div style="margin-bottom: 0;">
                <label for="desired_job_category" style="
                    display: block;
                    margin-bottom: 8px;
                    font-size: 13px;
                    font-weight: 700;
                    color: #5D535E;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">職種</label>
                <select id="desired_job_category" name="desired_job_category" style="
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
                    <option value="1" {{ request('desired_job_category') == 1 ? 'selected' : '' }}>スタイリスト</option>
                    <option value="2" {{ request('desired_job_category') == 2 ? 'selected' : '' }}>アシスタント</option>
                    <option value="3" {{ request('desired_job_category') == 3 ? 'selected' : '' }}>エステティシャン</option>
                    <option value="4" {{ request('desired_job_category') == 4 ? 'selected' : '' }}>看護師</option>
                    <option value="5" {{ request('desired_job_category') == 5 ? 'selected' : '' }}>歯科衛生士</option>
                    <option value="99" {{ request('desired_job_category') == 99 ? 'selected' : '' }}>その他</option>
                </select>
            </div>

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
                white-space: nowrap;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                検索
            </button>
        </div>
    </form>
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">候補者一覧（{{ $profiles->total() }}人）</h3>
    </div>
    <div style="padding: 24px;">
        @forelse($profiles as $profile)
        <div style="
            padding: 20px;
            border-bottom: 1px solid #f5f5f5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        " onmouseover="this.style.background='#fafafa';" onmouseout="this.style.background='#ffffff';">
            <div>
                <h4 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">{{ $profile->user->name }}</h4>
                <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                    <span style="
                        display: inline-block;
                        padding: 4px 12px;
                        background: #5D535E;
                        color: #ffffff;
                        border-radius: 12px;
                        font-size: 12px;
                        font-weight: 500;
                    ">
                        @if($profile->industry_type === 1) 美容
                        @elseif($profile->industry_type === 2) 医療
                        @elseif($profile->industry_type === 3) 歯科
                        @endif
                    </span>
                    <span style="color: #666666; font-size: 13px;">
                        希望職種：
                        @if($profile->desired_job_category === 1) スタイリスト
                        @elseif($profile->desired_job_category === 2) アシスタント
                        @elseif($profile->desired_job_category === 3) エステティシャン
                        @elseif($profile->desired_job_category === 4) 看護師
                        @elseif($profile->desired_job_category === 5) 歯科衛生士
                        @else その他
                        @endif
                    </span>
                    @if($profile->experience_years)
                    <span style="color: #666666; font-size: 13px;">経験：{{ $profile->experience_years }}年</span>
                    @endif
                </div>
            </div>
            <div>
                <a href="{{ route('company.scouts.create', $profile) }}" style="
                    padding: 10px 24px;
                    background: #5D535E;
                    color: #ffffff;
                    border: none;
                    border-radius: 20px;
                    font-size: 13px;
                    font-weight: 700;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    text-decoration: none;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    position: relative;
                    display: inline-block;
                " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                    スカウトを送る
                </a>
            </div>
        </div>
        @empty
        <div style="padding: 40px; text-align: center; color: #999999;">
            <p style="margin: 0; font-size: 14px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">該当する候補者が見つかりませんでした。</p>
        </div>
        @endforelse

        <div style="margin-top: 24px;">
            {{ $profiles->links() }}
        </div>
    </div>
</div>
@endsection


