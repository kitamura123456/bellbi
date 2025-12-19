-- Active: 1765333928509@@127.0.0.1@3306@bellbi
@php
use App\Enums\Todofuken;
@endphp
@extends('layouts.app')

@section('title', '求人一覧 | Bellbi')

@section('sidebar')
    <div class="sidebar-card" style="background: #ffffff; border-radius: 0; padding: 32px 24px; box-shadow: none; border: none; border-bottom: 1px solid #f0f0f0;">
        <h3 class="sidebar-title" style="font-size: 14px; font-weight: 600; color: #1a1a1a; letter-spacing: 0.05em; text-transform: uppercase; margin: 0 0 24px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">条件でさがす</h3>
        <form class="search-form">
            <div class="form-group" style="margin-bottom: 24px;">
                <label for="keyword" style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.02em;">キーワード</label>
                <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}" placeholder="エリア・サロン名・職種など" style="width: 100%; padding: 12px 16px; border: 1px solid #e0e0e0; border-radius: 0; font-size: 14px; background: #ffffff; color: #1a1a1a; transition: all 0.3s ease; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;" onfocus="this.style.borderColor='#1a1a1a'; this.style.outline='none';" onblur="this.style.borderColor='#e0e0e0';">
            </div>
            @php
                $regions = [
                    'hokkaido' => [
                        'name' => '北海道',
                        'prefectures' => [1]
                    ],
                    'tohoku' => [
                        'name' => '東北',
                        'prefectures' => [2, 3, 4, 5, 6, 7]
                    ],
                    'kanto' => [
                        'name' => '関東',
                        'prefectures' => [8, 9, 10, 11, 12, 13, 14]
                    ],
                    'chubu' => [
                        'name' => '中部',
                        'prefectures' => [15, 16, 17, 18, 19, 20, 21, 22, 23, 24]
                    ],
                    'kansai' => [
                        'name' => '関西',
                        'prefectures' => [25, 26, 27, 28, 29, 30]
                    ],
                    'chugoku_shikoku' => [
                        'name' => '中国・四国',
                        'prefectures' => [31, 32, 33, 34, 35, 36, 37, 38, 39]
                    ],
                    'kyushu_okinawa' => [
                        'name' => '九州・沖縄',
                        'prefectures' => [40, 41, 42, 43, 44, 45, 46, 47]
                    ],
                ];
                $prefMap = [];
                foreach(App\Enums\Todofuken::cases() as $pref) {
                    $prefMap[$pref->value] = $pref;
                }
            @endphp
            <div class="form-group" style="margin-bottom: 24px;">
                <label for="area" style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.02em;">エリア</label>
                <div class="checkbox-list" style="
                    max-height: 300px;
                    overflow-y: auto;
                    border: 1px solid #e0e0e0;
                    border-radius: 0;
                    padding: 8px;
                    background-color: #ffffff;
                ">
                    @foreach($regions as $regionKey => $region)
                        @php
                            $regionCount = 0;
                            foreach($region['prefectures'] as $prefCode) {
                                $regionCount += isset($areaCounts[$prefCode]) ? $areaCounts[$prefCode] : 0;
                            }
                            $selectedAreas = is_array(request('area')) ? request('area', []) : [];
                            $hasSelectedInRegion = false;
                            foreach($region['prefectures'] as $prefCode) {
                                if(in_array($prefCode, $selectedAreas)) {
                                    $hasSelectedInRegion = true;
                                    break;
                                }
                            }
                        @endphp
                        <div>
                            <label style="
                                display: flex;
                                align-items: center;
                                padding: 8px 10px;
                                margin-bottom: 2px;
                                cursor: pointer;
                                border-radius: 0;
                                transition: background-color 0.3s ease;
                                background-color: {{ $hasSelectedInRegion ? '#f5f5f5' : 'transparent' }};
                            " class="region-label-{{ $regionKey }}" onmouseover="if(!document.getElementById('region-{{ $regionKey }}') || !document.getElementById('region-{{ $regionKey }}').checked) this.style.backgroundColor='#fafafa';" onmouseout="if(!document.getElementById('region-{{ $regionKey }}') || !document.getElementById('region-{{ $regionKey }}').checked) this.style.backgroundColor='{{ $hasSelectedInRegion ? '#f5f5f5' : 'transparent' }}';">
                                <input type="checkbox" 
                                    id="region-{{ $regionKey }}"
                                    class="region-checkbox"
                                    data-region="{{ $regionKey }}"
                                    {{ $hasSelectedInRegion ? 'checked' : ''}}
                                    onchange="toggleRegion('{{ $regionKey }}')"
                                    style="
                                        margin-right: 10px;
                                        cursor: pointer;
                                        width: 16px;
                                        height: 16px;
                                        accent-color: #1a1a1a;
                                    ">
                                <span style="
                                    flex: 1;
                                    font-size: 13px;
                                    color: #1a1a1a;
                                    font-weight: 400;
                                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                                ">{{ $region['name'] }}</span>
                                @if($regionCount > 0)
                                <span style="
                                    display: inline-block;
                                    padding: 2px 8px;
                                    background-color: #e0e0e0;
                                    color: #666;
                                    border-radius: 0;
                                    font-size: 10px;
                                    font-weight: 500;
                                    min-width: 24px;
                                    text-align: center;
                                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                                ">{{ $regionCount }}</span>
                                @endif
                            </label>
                            <div id="prefectures-{{ $regionKey }}" style="
                                display: {{ $hasSelectedInRegion ? 'block' : 'none' }};
                                margin-left: 24px;
                                margin-top: 2px;
                                margin-bottom: 4px;
                            ">
                                @foreach($region['prefectures'] as $prefCode)
                                    @php
                                        $pref = $prefMap[$prefCode] ?? null;
                                        if(!$pref) continue;
                                        $isChecked = in_array($pref->value, $selectedAreas);
                                        $bgColor = $isChecked ? '#f5f5f5' : 'transparent';
                                        $count = isset($areaCounts[$pref->value]) ? $areaCounts[$pref->value] : 0;
                                        $badgeBgColor = $isChecked ? '#1a1a1a' : '#e0e0e0';
                                        $badgeTextColor = $isChecked ? '#ffffff' : '#666';
                                    @endphp
                                    <label style="
                                        display: flex;
                                        align-items: center;
                                        padding: 4px 8px;
                                        margin-bottom: 1px;
                                        cursor: pointer;
                                        border-radius: 6px;
                                        transition: background-color 0.15s ease;
                                        background-color: {{ $bgColor }};
                                    " onmouseover="if(!this.querySelector('input[type=checkbox]').checked) this.style.backgroundColor='#fafafa';" onmouseout="if(!this.querySelector('input[type=checkbox]').checked) this.style.backgroundColor='{{ $bgColor }}';">
                                        <input type="checkbox" 
                                            name="area[]" 
                                            value="{{ $pref->value }}" 
                                            {{ $isChecked ? 'checked' : ''}}
                                            onchange="this.parentElement.style.backgroundColor = this.checked ? '#f5f5f5' : 'transparent'; var badge = this.parentElement.querySelector('span:last-child'); if(badge && badge.style) { badge.style.backgroundColor = this.checked ? '#1a1a1a' : '#e0e0e0'; badge.style.color = this.checked ? '#ffffff' : '#666'; } updateRegionCheckbox('{{ $regionKey }}');"
                                            style="
                                                margin-right: 8px;
                                                cursor: pointer;
                                                width: 14px;
                                                height: 14px;
                                                accent-color: #1a1a1a;
                                            ">
                                        <span style="
                                            flex: 1;
                                            font-size: 12px;
                                            color: #1a1a1a;
                                            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                                        ">{{ $pref->label() }}</span>
                                        @if($count > 0)
                                        <span style="
                                            display: inline-block;
                                            padding: 1px 6px;
                                            background-color: {{ $badgeBgColor }};
                                            color: {{ $badgeTextColor }};
                                            border-radius: 0;
                                            font-size: 10px;
                                            font-weight: 500;
                                            min-width: 20px;
                                            text-align: center;
                                            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                                        ">{{ $count }}</span>
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <script>
                function toggleRegion(regionKey) {
                    const checkbox = document.getElementById('region-' + regionKey);
                    const prefectureDiv = document.getElementById('prefectures-' + regionKey);
                    const label = document.querySelector('.region-label-' + regionKey);
                    
                    if (checkbox.checked) {
                        prefectureDiv.style.display = 'block';
                        if (label) label.style.backgroundColor = '#f5f5f5';
                    } else {
                        prefectureDiv.style.display = 'none';
                        if (label) label.style.backgroundColor = 'transparent';
                        // 地域のチェックを外したら、その地域の都道府県もすべて外す
                        const prefectureCheckboxes = prefectureDiv.querySelectorAll('input[type="checkbox"]');
                        prefectureCheckboxes.forEach(cb => {
                            cb.checked = false;
                            const badge = cb.parentElement.querySelector('span:last-child');
                            if(badge && badge.style) {
                                badge.style.backgroundColor = '#e0e0e0';
                                badge.style.color = '#666';
                            }
                            cb.parentElement.style.backgroundColor = 'transparent';
                        });
                    }
                }
                
                function updateRegionCheckbox(regionKey) {
                    const prefectureDiv = document.getElementById('prefectures-' + regionKey);
                    const regionCheckbox = document.getElementById('region-' + regionKey);
                    const label = document.querySelector('.region-label-' + regionKey);
                    
                    if (prefectureDiv && regionCheckbox) {
                        const checkedCount = prefectureDiv.querySelectorAll('input[type="checkbox"]:checked').length;
                        if (checkedCount > 0) {
                            regionCheckbox.checked = true;
                            if (label) label.style.backgroundColor = '#f5f5f5';
                        } else {
                            regionCheckbox.checked = false;
                            if (label) label.style.backgroundColor = 'transparent';
                        }
                    }
                }
                
                // ページ読み込み時に選択されている地域を展開
                document.addEventListener('DOMContentLoaded', function() {
                    @foreach($regions as $regionKey => $region)
                        @php
                            $selectedAreasInit = is_array(request('area')) ? request('area', []) : [];
                            $hasSelectedInit = false;
                            foreach($region['prefectures'] as $prefCode) {
                                if(in_array($prefCode, $selectedAreasInit)) {
                                    $hasSelectedInit = true;
                                    break;
                                }
                            }
                        @endphp
                        @if($hasSelectedInit)
                            const prefectureDiv = document.getElementById('prefectures-{{ $regionKey }}');
                            const label = document.querySelector('.region-label-{{ $regionKey }}');
                            if (prefectureDiv) {
                                prefectureDiv.style.display = 'block';
                            }
                            if (label) {
                                label.style.backgroundColor = '#f5f5f5';
                            }
                        @endif
                    @endforeach
                });
            </script>
            @php
                $employmentTypes = [
                    1 => '正社員',
                    2 => 'パート・アルバイト',
                    3 => '業務委託',
                    4 => '契約社員',
                ];
            @endphp
            <div class="form-group" style="margin-bottom: 24px;">
                <label for="employment_type" style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.02em;">雇用形態</label>
                <div class="checkbox-list" style="
                    border: 1px solid #e0e0e0;
                    border-radius: 0;
                    padding: 8px;
                    background-color: #ffffff;
                ">
                    @foreach($employmentTypes as $typeValue => $typeLabel)
                        @php
                            $isChecked = is_array(request('employment_type')) && in_array($typeValue, request('employment_type', []));
                            $bgColor = $isChecked ? '#f5f5f5' : 'transparent';
                            $count = isset($employmentTypeCounts[$typeValue]) ? $employmentTypeCounts[$typeValue] : 0;
                            $badgeBgColor = $isChecked ? '#1a1a1a' : '#e0e0e0';
                            $badgeTextColor = $isChecked ? '#ffffff' : '#666';
                        @endphp
                        <label style="
                            display: flex;
                            align-items: center;
                            padding: 8px 10px;
                            margin-bottom: 2px;
                            cursor: pointer;
                            border-radius: 0;
                            transition: background-color 0.3s ease;
                            background-color: {{ $bgColor }};
                        " onmouseover="if(!this.querySelector('input[type=checkbox]').checked) this.style.backgroundColor='#fafafa';" onmouseout="if(!this.querySelector('input[type=checkbox]').checked) this.style.backgroundColor='{{ $bgColor }}';">
                            <input type="checkbox" 
                                name="employment_type[]" 
                                value="{{ $typeValue }}" 
                                {{ $isChecked ? 'checked' : ''}}
                                onchange="this.parentElement.style.backgroundColor = this.checked ? '#f5f5f5' : 'transparent'; var badge = this.parentElement.querySelector('span:last-child'); if(badge && badge.style) { badge.style.backgroundColor = this.checked ? '#1a1a1a' : '#e0e0e0'; badge.style.color = this.checked ? '#ffffff' : '#666'; }"
                                style="
                                    margin-right: 10px;
                                    cursor: pointer;
                                    width: 16px;
                                    height: 16px;
                                    accent-color: #1a1a1a;
                                ">
                            <span style="
                                flex: 1;
                                font-size: 13px;
                                color: #1a1a1a;
                                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            ">{{ $typeLabel }}</span>
                            @if($count > 0)
                            <span style="
                                display: inline-block;
                                padding: 2px 8px;
                                background-color: {{ $badgeBgColor }};
                                color: {{ $badgeTextColor }};
                                border-radius: 0;
                                font-size: 10px;
                                font-weight: 500;
                                min-width: 24px;
                                text-align: center;
                                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            ">{{ $count }}</span>
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>
            @if(isset($tags) && $tags->isNotEmpty())
            <div class="form-group" style="margin-bottom: 24px;">
                <label for="tags" style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.02em;">タグ</label>
                <div class="tag-checkbox-list" style="
                    max-height: 220px;
                    overflow-y: auto;
                    border: 1px solid #e0e0e0;
                    border-radius: 0;
                    padding: 8px;
                    background-color: #ffffff;
                ">
                    @foreach($tags as $tag)
                        @php
                            $isChecked = is_array(request('tags')) && in_array($tag->id, request('tags', []));
                            $bgColor = $isChecked ? '#f5f5f5' : 'transparent';
                            $badgeBgColor = $isChecked ? '#1a1a1a' : '#e0e0e0';
                            $badgeTextColor = $isChecked ? '#ffffff' : '#666';
                        @endphp
                        <label style="
                            display: flex;
                            align-items: center;
                            padding: 8px 10px;
                            margin-bottom: 2px;
                            cursor: pointer;
                            border-radius: 0;
                            transition: background-color 0.3s ease;
                            background-color: {{ $bgColor }};
                        " onmouseover="if(!this.querySelector('input[type=checkbox]').checked) this.style.backgroundColor='#fafafa';" onmouseout="if(!this.querySelector('input[type=checkbox]').checked) this.style.backgroundColor='{{ $bgColor }}';">
                            <input type="checkbox" 
                                name="tags[]" 
                                value="{{ $tag->id }}" 
                                {{ $isChecked ? 'checked' : ''}}
                                onchange="this.parentElement.style.backgroundColor = this.checked ? '#f5f5f5' : 'transparent'; var badge = this.parentElement.querySelector('span:last-child'); if(badge && badge.style) { badge.style.backgroundColor = this.checked ? '#1a1a1a' : '#e0e0e0'; badge.style.color = this.checked ? '#ffffff' : '#666'; }"
                                style="
                                    margin-right: 10px;
                                    cursor: pointer;
                                    width: 16px;
                                    height: 16px;
                                    accent-color: #1a1a1a;
                                ">
                            <span style="
                                flex: 1;
                                font-size: 13px;
                                color: #1a1a1a;
                                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            ">{{ $tag->name }}</span>
                            @if($tag->job_posts_count > 0)
                            <span style="
                                display: inline-block;
                                padding: 2px 8px;
                                background-color: {{ $badgeBgColor }};
                                color: {{ $badgeTextColor }};
                                border-radius: 0;
                                font-size: 10px;
                                font-weight: 500;
                                min-width: 24px;
                                text-align: center;
                                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            ">{{ $tag->job_posts_count }}</span>
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>
            @endif
            <button type="submit" style="
                width: 100%;
                padding: 14px 24px;
                background: #1a1a1a;
                color: #ffffff;
                border: 1px solid #1a1a1a;
                border-radius: 0;
                font-size: 13px;
                font-weight: 500;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                cursor: pointer;
                transition: all 0.3s ease;
                position: relative;
                letter-spacing: 0.05em;
                text-transform: uppercase;
            " onmouseover="this.style.background='#000000'; this.style.borderColor='#000000';" onmouseout="this.style.background='#1a1a1a'; this.style.borderColor='#1a1a1a';">
                この条件で検索
            </button>
        </form>
    </div>
@endsection

@section('content')
    <header class="page-header" style="margin-bottom: 48px; padding-bottom: 32px; border-bottom: 1px solid #f0f0f0;">
        <p class="page-label" style="font-size: 11px; color: #999; letter-spacing: 0.15em; text-transform: uppercase; margin: 0 0 12px 0; font-weight: 500; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">Beauty / Medical / Dental</p>
        <h2 class="page-title" style="font-size: 32px; font-weight: 400; color: #1a1a1a; margin: 0 0 16px 0; letter-spacing: -0.02em; line-height: 1.3; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">美容・医療・歯科の求人一覧</h2>
        <p class="page-lead" style="font-size: 14px; color: #666; line-height: 1.7; margin: 0; font-weight: 400; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
            サロンで働きたい美容師さん、クリニックで働きたい看護師さんに向けた、
            働きやすい職場を集めました。
        </p>
    </header>

    @if ($jobs->isEmpty())
        <p class="empty-message" style="font-size: 14px; color: #999; text-align: center; padding: 64px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">現在公開中の求人はありません。</p>
    @else
        <div class="job-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 32px 24px;">
            @foreach ($jobs as $job)
                @php
                    $applicationStatus = $userApplications[$job->id] ?? null;
                    $isRejected = $applicationStatus === 5;
                @endphp
                <article class="job-card" style="background: #ffffff; border: 1px solid transparent; padding: 0; transition: border-color 0.3s ease; position: relative; {{ $isRejected ? 'opacity: 0.6;' : '' }}" onmouseover="this.style.borderColor='#1a1a1a';" onmouseout="this.style.borderColor='transparent';">
                    @if($isRejected)
                    <div style="
                        position: absolute;
                        top: 12px;
                        right: 12px;
                        background-color: rgba(0, 0, 0, 0.7);
                        color: #ffffff;
                        padding: 4px 8px;
                        font-size: 9px;
                        font-weight: 400;
                        z-index: 10;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        letter-spacing: 0.1em;
                    ">不採用</div>
                    @endif
                    <a href="{{ route('jobs.show', $job) }}" style="text-decoration: none; color: inherit; display: block;">
                        <div class="job-card-body" style="padding: 0;">
                            <div style="width: 100%; height: 280px; overflow: hidden; background: #fafafa; position: relative;">
                                @php
                                    // 最初の画像を取得（imagesがあればそれを使用、なければthumbnail_imageを使用）
                                    $displayImage = null;
                                    if ($job->images && $job->images->count() > 0) {
                                        $displayImage = $job->images->first();
                                    } elseif ($job->thumbnail_image) {
                                        $displayImage = (object)['path' => $job->thumbnail_image, 'is_template' => strpos($job->thumbnail_image, 'templates/') === 0];
                                    }
                                @endphp
                                @if($displayImage)
                                    @if($displayImage->is_template ?? false)
                                        <img src="{{ asset('images/' . $displayImage->path) }}" alt="{{ $job->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: opacity 0.3s ease;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                                    @elseif(file_exists(public_path('storage/' . $displayImage->path)))
                                        <img src="{{ asset('storage/' . $displayImage->path) }}" alt="{{ $job->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: opacity 0.3s ease;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                                    @else
                                        <div style="width: 100%; height: 100%; background: #fafafa; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 12px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                                            No Image
                                        </div>
                                    @endif
                                @else
                                    <div style="width: 100%; height: 100%; background: #fafafa; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 12px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                                        No Image
                                    </div>
                                @endif
                            </div>
                            <div style="padding: 20px 12px 0 12px;">
                                <h3 class="job-card-title" style="margin: 0 0 6px 0; font-size: 15px; font-weight: 400; color: #1a1a1a; line-height: 1.5; letter-spacing: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                                    {{ $job->title }}
                                </h3>
                                <p class="job-card-salon" style="margin: 0 0 8px 0; font-size: 12px; color: #999; font-weight: 400; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                                    @if ($job->company)
                                        {{ $job->company->name }}
                                    @endif
                                    @if ($job->store)
                                        <span class="job-card-separator" style="margin: 0 4px; color: #ddd;">/</span>{{ $job->store->name }}
                                    @endif
                                </p>
                                @if ($job->work_location)
                                    <p class="job-card-location" style="margin: 0 0 6px 0; font-size: 11px; color: #999; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{{ $job->work_location }}</p>
                                @endif
                                @if (!is_null($job->min_salary) || !is_null($job->max_salary))
                                    <p class="job-card-salary" style="margin: 0; font-size: 13px; color: #1a1a1a; font-weight: 400; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                                        @if (!is_null($job->min_salary))
                                            {{ number_format($job->min_salary) }}円
                                        @endif
                                        @if (!is_null($job->min_salary) && !is_null($job->max_salary))
                                            〜
                                        @endif
                                        @if (!is_null($job->max_salary))
                                            {{ number_format($job->max_salary) }}円
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>

        <div class="pagination-wrapper" style="margin-top: 64px; padding-top: 32px; border-top: 1px solid #f0f0f0;">
            {{ $jobs->links() }}
        </div>
    @endif
@endsection


