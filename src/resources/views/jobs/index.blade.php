-- Active: 1765333928509@@127.0.0.1@3306@bellbi
@php
use App\Enums\Todofuken;
@endphp
@extends('layouts.app')

@section('title', '求人一覧 | Bellbi')

@section('sidebar')
    <div class="sidebar-card">
        <h3 class="sidebar-title">条件でさがす</h3>
        <form class="search-form">
            <div class="form-group">
                <label for="keyword">キーワード</label>
                <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}" placeholder="エリア・サロン名・職種など">
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
            <div class="form-group">
                <label for="area">エリア</label>
                <div class="checkbox-list" style="
                    max-height: 300px;
                    overflow-y: auto;
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    padding: 6px;
                    background-color: #fff;
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
                                padding: 6px 8px;
                                margin-bottom: 2px;
                                cursor: pointer;
                                border-radius: 6px;
                                transition: background-color 0.15s ease;
                                background-color: {{ $hasSelectedInRegion ? '#eff6ff' : 'transparent' }};
                            " class="region-label-{{ $regionKey }}" onmouseover="if(!document.getElementById('region-{{ $regionKey }}') || !document.getElementById('region-{{ $regionKey }}').checked) this.style.backgroundColor='#f3f4f6';" onmouseout="if(!document.getElementById('region-{{ $regionKey }}') || !document.getElementById('region-{{ $regionKey }}').checked) this.style.backgroundColor='{{ $hasSelectedInRegion ? '#eff6ff' : 'transparent' }}';">
                                <input type="checkbox" 
                                    id="region-{{ $regionKey }}"
                                    class="region-checkbox"
                                    data-region="{{ $regionKey }}"
                                    {{ $hasSelectedInRegion ? 'checked' : ''}}
                                    onchange="toggleRegion('{{ $regionKey }}')"
                                    style="
                                        margin-right: 8px;
                                        cursor: pointer;
                                        width: 16px;
                                        height: 16px;
                                        accent-color: #5D535E;
                                    ">
                                <span style="
                                    flex: 1;
                                    font-size: 13px;
                                    color: #374151;
                                    font-weight: 500;
                                ">{{ $region['name'] }}</span>
                                @if($regionCount > 0)
                                <span style="
                                    display: inline-block;
                                    padding: 2px 7px;
                                    background-color: #e5e7eb;
                                    color: #6b7280;
                                    border-radius: 12px;
                                    font-size: 11px;
                                    font-weight: 500;
                                    min-width: 26px;
                                    text-align: center;
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
                                        $bgColor = $isChecked ? '#eff6ff' : 'transparent';
                                        $count = isset($areaCounts[$pref->value]) ? $areaCounts[$pref->value] : 0;
                                        $badgeBgColor = $isChecked ? '#dbeafe' : '#e5e7eb';
                                        $badgeTextColor = $isChecked ? '#1e40af' : '#6b7280';
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
                                    " onmouseover="if(!this.querySelector('input[type=checkbox]').checked) this.style.backgroundColor='#f3f4f6';" onmouseout="if(!this.querySelector('input[type=checkbox]').checked) this.style.backgroundColor='{{ $bgColor }}';">
                                        <input type="checkbox" 
                                            name="area[]" 
                                            value="{{ $pref->value }}" 
                                            {{ $isChecked ? 'checked' : ''}}
                                            onchange="this.parentElement.style.backgroundColor = this.checked ? '#eff6ff' : 'transparent'; var badge = this.parentElement.querySelector('span:last-child'); if(badge && badge.style) { badge.style.backgroundColor = this.checked ? '#dbeafe' : '#e5e7eb'; badge.style.color = this.checked ? '#1e40af' : '#6b7280'; } updateRegionCheckbox('{{ $regionKey }}');"
                                            style="
                                                margin-right: 8px;
                                                cursor: pointer;
                                                width: 14px;
                                                height: 14px;
                                                accent-color: #5D535E;
                                            ">
                                        <span style="
                                            flex: 1;
                                            font-size: 12px;
                                            color: #374151;
                                        ">{{ $pref->label() }}</span>
                                        @if($count > 0)
                                        <span style="
                                            display: inline-block;
                                            padding: 1px 6px;
                                            background-color: {{ $badgeBgColor }};
                                            color: {{ $badgeTextColor }};
                                            border-radius: 10px;
                                            font-size: 10px;
                                            font-weight: 500;
                                            min-width: 22px;
                                            text-align: center;
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
                        if (label) label.style.backgroundColor = '#eff6ff';
                    } else {
                        prefectureDiv.style.display = 'none';
                        if (label) label.style.backgroundColor = 'transparent';
                        // 地域のチェックを外したら、その地域の都道府県もすべて外す
                        const prefectureCheckboxes = prefectureDiv.querySelectorAll('input[type="checkbox"]');
                        prefectureCheckboxes.forEach(cb => {
                            cb.checked = false;
                            const badge = cb.parentElement.querySelector('span:last-child');
                            if(badge && badge.style) {
                                badge.style.backgroundColor = '#e5e7eb';
                                badge.style.color = '#6b7280';
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
                            if (label) label.style.backgroundColor = '#eff6ff';
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
                                label.style.backgroundColor = '#eff6ff';
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
            <div class="form-group">
                <label for="employment_type">雇用形態</label>
                <div class="checkbox-list" style="
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    padding: 6px;
                    background-color: #fff;
                ">
                    @foreach($employmentTypes as $typeValue => $typeLabel)
                        @php
                            $isChecked = is_array(request('employment_type')) && in_array($typeValue, request('employment_type', []));
                            $bgColor = $isChecked ? '#eff6ff' : 'transparent';
                            $count = isset($employmentTypeCounts[$typeValue]) ? $employmentTypeCounts[$typeValue] : 0;
                            $badgeBgColor = $isChecked ? '#dbeafe' : '#e5e7eb';
                            $badgeTextColor = $isChecked ? '#1e40af' : '#6b7280';
                        @endphp
                        <label style="
                            display: flex;
                            align-items: center;
                            padding: 6px 8px;
                            margin-bottom: 2px;
                            cursor: pointer;
                            border-radius: 6px;
                            transition: background-color 0.15s ease;
                            background-color: {{ $bgColor }};
                        " onmouseover="if(!this.querySelector('input[type=checkbox]').checked) this.style.backgroundColor='#f3f4f6';" onmouseout="if(!this.querySelector('input[type=checkbox]').checked) this.style.backgroundColor='{{ $bgColor }}';">
                            <input type="checkbox" 
                                name="employment_type[]" 
                                value="{{ $typeValue }}" 
                                {{ $isChecked ? 'checked' : ''}}
                                onchange="this.parentElement.style.backgroundColor = this.checked ? '#eff6ff' : 'transparent'; var badge = this.parentElement.querySelector('span:last-child'); if(badge && badge.style) { badge.style.backgroundColor = this.checked ? '#dbeafe' : '#e5e7eb'; badge.style.color = this.checked ? '#1e40af' : '#6b7280'; }"
                                style="
                                    margin-right: 8px;
                                    cursor: pointer;
                                    width: 16px;
                                    height: 16px;
                                    accent-color: #5D535E;
                                ">
                            <span style="
                                flex: 1;
                                font-size: 13px;
                                color: #374151;
                            ">{{ $typeLabel }}</span>
                            @if($count > 0)
                            <span style="
                                display: inline-block;
                                padding: 2px 7px;
                                background-color: {{ $badgeBgColor }};
                                color: {{ $badgeTextColor }};
                                border-radius: 12px;
                                font-size: 11px;
                                font-weight: 500;
                                min-width: 26px;
                                text-align: center;
                            ">{{ $count }}</span>
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>
            @if(isset($tags) && $tags->isNotEmpty())
            <div class="form-group">
                <label for="tags">タグ</label>
                <div class="tag-checkbox-list" style="
                    max-height: 220px;
                    overflow-y: auto;
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    padding: 8px;
                    background-color: #fff;
                ">
                    @foreach($tags as $tag)
                        @php
                            $isChecked = is_array(request('tags')) && in_array($tag->id, request('tags', []));
                            $bgColor = $isChecked ? '#eff6ff' : 'transparent';
                            $badgeBgColor = $isChecked ? '#dbeafe' : '#e5e7eb';
                            $badgeTextColor = $isChecked ? '#1e40af' : '#6b7280';
                        @endphp
                        <label style="
                            display: flex;
                            align-items: center;
                            padding: 8px 10px;
                            margin-bottom: 2px;
                            cursor: pointer;
                            border-radius: 6px;
                            transition: background-color 0.15s ease;
                            background-color: {{ $bgColor }};
                        " onmouseover="if(!this.querySelector('input[type=checkbox]').checked) this.style.backgroundColor='#f3f4f6';" onmouseout="if(!this.querySelector('input[type=checkbox]').checked) this.style.backgroundColor='{{ $bgColor }}';">
                            <input type="checkbox" 
                                name="tags[]" 
                                value="{{ $tag->id }}" 
                                {{ $isChecked ? 'checked' : ''}}
                                onchange="this.parentElement.style.backgroundColor = this.checked ? '#eff6ff' : 'transparent'; var badge = this.parentElement.querySelector('span:last-child'); if(badge && badge.style) { badge.style.backgroundColor = this.checked ? '#dbeafe' : '#e5e7eb'; badge.style.color = this.checked ? '#1e40af' : '#6b7280'; }"
                                style="
                                    margin-right: 10px;
                                    cursor: pointer;
                                    width: 16px;
                                    height: 16px;
                                    accent-color: #5D535E;
                                ">
                            <span style="
                                flex: 1;
                                font-size: 13px;
                                color: #374151;
                            ">{{ $tag->name }}</span>
                            @if($tag->job_posts_count > 0)
                            <span style="
                                display: inline-block;
                                padding: 2px 7px;
                                background-color: {{ $badgeBgColor }};
                                color: {{ $badgeTextColor }};
                                border-radius: 12px;
                                font-size: 11px;
                                font-weight: 500;
                                min-width: 26px;
                                text-align: center;
                            ">{{ $tag->job_posts_count }}</span>
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>
            @endif
            <button type="submit" style="
                width: 100%;
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
                この条件で検索
            </button>
        </form>
    </div>
@endsection

@section('content')
    <header class="page-header">
        <p class="page-label">Beauty / Medical / Dental</p>
        <h2 class="page-title">美容・医療・歯科の求人一覧</h2>
        <p class="page-lead">
            サロンで働きたい美容師さん、クリニックで働きたい看護師さんに向けた、
            働きやすい職場を集めました。
        </p>
    </header>

    @if ($jobs->isEmpty())
        <p class="empty-message">現在公開中の求人はありません。</p>
    @else
        <div class="job-grid">
            @foreach ($jobs as $job)
                @php
                    $applicationStatus = $userApplications[$job->id] ?? null;
                    $isRejected = $applicationStatus === 5;
                @endphp
                <article class="job-card" style="{{ $isRejected ? 'opacity: 0.6; position: relative;' : '' }}">
                    @if($isRejected)
                    <div style="
                        position: absolute;
                        top: 12px;
                        right: 12px;
                        background-color: #ef4444;
                        color: #ffffff;
                        padding: 4px 12px;
                        border-radius: 12px;
                        font-size: 11px;
                        font-weight: 700;
                        z-index: 10;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">不採用</div>
                    @endif
                    <div class="job-card-body">
                        <p class="job-card-tag">募集</p>
                            <div style="flex-shrink: 0;">
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
                                        <img src="{{ asset('images/' . $displayImage->path) }}" alt="{{ $job->title }}" style="width: 180px; height: 180px; object-fit: cover; border-radius: 12px;">
                                    @elseif(file_exists(public_path('storage/' . $displayImage->path)))
                                        <img src="{{ asset('storage/' . $displayImage->path) }}" alt="{{ $job->title }}" style="width: 180px; height: 180px; object-fit: cover; border-radius: 12px;">
                                    @else
                                        <div style="width: 180px; height: 180px; background: #f3f4f6; border: 2px dashed #d1d5db; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 14px;">
                                            No Image
                                        </div>
                                    @endif
                                @else
                                    <div style="width: 180px; height: 180px; background: #f3f4f6; border: 2px dashed #d1d5db; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 14px;">
                                        No Image
                                    </div>
                                @endif
                            </div>
                        <h3 class="job-card-title">
                            <a href="{{ route('jobs.show', $job) }}">
                                {{ $job->title }}
                            </a>
                        </h3>
                        <p class="job-card-salon">
                            @if ($job->company)
                                {{ $job->company->name }}
                            @endif
                            @if ($job->store)
                                <span class="job-card-separator">/</span>{{ $job->store->name }}
                            @endif
                        </p>
                        @if ($job->work_location)
                            <p class="job-card-location">勤務地: {{ $job->work_location }}</p>
                        @endif
                        @if (!is_null($job->min_salary) || !is_null($job->max_salary))
                            <p class="job-card-salary">
                                @if (!is_null($job->min_salary))
                                    {{ number_format($job->min_salary) }}円
                                @endif
                                〜
                                @if (!is_null($job->max_salary))
                                    {{ number_format($job->max_salary) }}円
                                @endif
                            </p>
                        @endif
                    </div>
                    <div class="job-card-footer">
                        <a href="{{ route('jobs.show', $job) }}" style="
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
                            display: inline-block;
                        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                            詳細を見る
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="pagination-wrapper">
            {{ $jobs->links() }}
        </div>
    @endif
@endsection


