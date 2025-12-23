@php
use App\Enums\Todofuken;
@endphp
@extends('layouts.app')

@section('title', '求人一覧 | Bellbi')

@section('sidebar')
    <div class="sidebar-card" style="background: #ffffff; border-radius: 0; padding: 32px 24px; box-shadow: none; border: none; border-bottom: 1px solid #f0f0f0;">
        <div class="sidebar-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;" onclick="if(window.innerWidth <= 768) toggleSearchForm()">
            <div style="flex: 1; min-width: 0;">
                <h3 class="sidebar-title" style="font-size: 14px; font-weight: 600; color: #1a1a1a; letter-spacing: 0.05em; text-transform: uppercase; margin: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">条件でさがす</h3>
                <div class="selected-conditions" id="selectedConditions" style="
                    display: none;
                    margin-top: 4px;
                    font-size: 10px;
                    color: #666;
                    font-weight: 400;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                "></div>
            </div>
            <span class="toggle-icon" style="
                display: none;
                font-size: 16px;
                color: #1a1a1a;
                transition: transform 0.3s ease;
                user-select: none;
                cursor: pointer;
                flex-shrink: 0;
                margin-left: 8px;
            ">▼</span>
        </div>
        <style>
            /* スマホでサイドバーを固定 */
            @media (max-width: 768px) {
                .sidebar {
                    position: sticky !important;
                    top: 0 !important;
                    z-index: 50 !important;
                    background: #ffffff !important;
                    margin-bottom: 0 !important;
                }
                .sidebar-card {
                    position: sticky !important;
                    top: 0 !important;
                    z-index: 50 !important;
                    background: #ffffff !important;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05) !important;
                    margin-bottom: 0 !important;
                    padding: 8px 12px !important;
                }
                .sidebar-header {
                    margin-bottom: 0 !important;
                    cursor: pointer !important;
                    padding: 4px 0;
                    align-items: flex-start !important;
                }
                .sidebar-title {
                    margin-bottom: 0 !important;
                    font-size: 11px !important;
                }
                .selected-conditions {
                    display: block !important;
                }
                .toggle-icon {
                    display: block !important;
                    font-size: 14px !important;
                }
                .search-form {
                    display: none;
                    margin-top: 8px;
                }
                .search-form.active {
                    display: block !important;
                }
                .toggle-icon.active {
                    transform: rotate(180deg);
                }
                .container.main-inner {
                    flex-direction: column !important;
                }
                .sidebar {
                    order: -1 !important;
                }
            }
            @media (max-width: 1024px) {
                .sidebar-card {
                    padding: 24px 20px !important;
                }
                .sidebar-title {
                    font-size: 13px !important;
                    margin-bottom: 20px !important;
                }
                .form-group {
                    margin-bottom: 20px !important;
                }
                .form-group label {
                    font-size: 11px !important;
                    margin-bottom: 6px !important;
                }
                .form-group input[type="text"] {
                    padding: 10px 14px !important;
                    font-size: 13px !important;
                }
                .checkbox-list, .tag-checkbox-list {
                    padding: 6px !important;
                    max-height: 250px !important;
                }
                .checkbox-list label, .tag-checkbox-list label {
                    padding: 6px 8px !important;
                    font-size: 12px !important;
                }
                .checkbox-list label span, .tag-checkbox-list label span {
                    font-size: 12px !important;
                }
                button[type="submit"] {
                    padding: 12px 20px !important;
                    font-size: 12px !important;
                }
            }
            @media (max-width: 768px) {
                .sidebar-card {
                    padding: 20px 16px !important;
                }
                .sidebar-title {
                    font-size: 12px !important;
                    margin-bottom: 16px !important;
                }
                .form-group {
                    margin-bottom: 16px !important;
                }
                .form-group label {
                    font-size: 10px !important;
                    margin-bottom: 6px !important;
                }
                .form-group input[type="text"] {
                    padding: 10px 12px !important;
                    font-size: 12px !important;
                }
                .checkbox-list, .tag-checkbox-list {
                    padding: 6px !important;
                    max-height: 200px !important;
                }
                .checkbox-list label, .tag-checkbox-list label {
                    padding: 6px 8px !important;
                    font-size: 11px !important;
                }
                .checkbox-list label span, .tag-checkbox-list label span {
                    font-size: 11px !important;
                }
                .checkbox-list label input[type="checkbox"], .tag-checkbox-list label input[type="checkbox"] {
                    width: 14px !important;
                    height: 14px !important;
                    margin-right: 8px !important;
                }
                button[type="submit"] {
                    padding: 12px 16px !important;
                    font-size: 11px !important;
                }
            }
            @media (max-width: 480px) {
                .sidebar-card {
                    padding: 16px 12px !important;
                }
                .sidebar-title {
                    font-size: 11px !important;
                    margin-bottom: 12px !important;
                }
                .form-group {
                    margin-bottom: 12px !important;
                }
                .form-group label {
                    font-size: 9px !important;
                    margin-bottom: 4px !important;
                }
                .form-group input[type="text"] {
                    padding: 8px 10px !important;
                    font-size: 11px !important;
                }
                .checkbox-list, .tag-checkbox-list {
                    padding: 4px !important;
                    max-height: 180px !important;
                }
                .checkbox-list label, .tag-checkbox-list label {
                    padding: 4px 6px !important;
                    font-size: 10px !important;
                }
                .checkbox-list label span, .tag-checkbox-list label span {
                    font-size: 10px !important;
                }
                .checkbox-list label input[type="checkbox"], .tag-checkbox-list label input[type="checkbox"] {
                    width: 12px !important;
                    height: 12px !important;
                    margin-right: 6px !important;
                }
                button[type="submit"] {
                    padding: 10px 14px !important;
                    font-size: 10px !important;
                }
            }
            .filter-badge {
                background-color: transparent;
                border: 1px solid transparent;
                transition: background-color 0.15s, border-color 0.15s;
            }
            .filter-badge.is-selected {
                background-color: #f5f5f5;
                border-color: #1a1a1a;
            }
        </style>
        <form class="search-form" id="searchForm">
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
                            " class="region-label-{{ $regionKey }}">
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
                                        border-radius: 0;
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
                                        $count = isset($areaCounts[$pref->value]) ? $areaCounts[$pref->value] : 0;
                                        // バッジは選択状態に関わらずグレーに統一
                                        $badgeBgColor = '#e0e0e0';
                                        $badgeTextColor = '#666';
                                    @endphp
                                    <label class="filter-badge {{ $isChecked ? 'is-selected' : '' }}" style="
                                        display: flex;
                                        align-items: center;
                                        padding: 4px 8px;
                                        margin-bottom: 1px;
                                        cursor: pointer;
                                        border-radius: 0;
                                    ">
                                        <input type="checkbox" 
                                            name="area[]" 
                                            value="{{ $pref->value }}" 
                                            {{ $isChecked ? 'checked' : ''}}
                                            onchange="
                                                const badge = this.parentElement;
                                                if (badge) {
                                                    badge.classList.toggle('is-selected', this.checked);
                                                }
                                                updateRegionCheckbox('{{ $regionKey }}');
                                                "

                                            style="
                                                margin-right: 8px;
                                                cursor: pointer;
                                                width: 14px;
                                                height: 14px;
                                                accent-color: #1a1a1a;
                                                border-radius: 0;
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
                            const badge = cb.parentElement;
                            if (badge) {
                                badge.classList.toggle('is-selected', false);
                            }
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
                            // バッジは選択状態に関わらずグレーに統一
                            $badgeBgColor = '#e0e0e0';
                            $badgeTextColor = '#666';
                        @endphp
                        <label class="filter-badge {{ $isChecked ? 'is-selected' : '' }}" style="
                            display: flex;
                            align-items: center;
                            padding: 8px 10px;
                            margin-bottom: 2px;
                            cursor: pointer;
                            border-radius: 0;
                        " onmouseover="if(!this.querySelector('input[type=checkbox]').checked) this.style.backgroundColor='#fafafa';" onmouseout="if(!this.querySelector('input[type=checkbox]').checked) this.style.backgroundColor='transparent';">
                            <input type="checkbox" 
                                name="employment_type[]" 
                                value="{{ $typeValue }}" 
                                {{ $isChecked ? 'checked' : ''}}
                                onchange="this.parentElement.classList.toggle('is-selected', this.checked);"
                                style="
                                    margin-right: 10px;
                                    cursor: pointer;
                                    width: 16px;
                                    height: 16px;
                                    accent-color: #1a1a1a;
                                    border-radius: 0;
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
                            // バッジは選択状態に関わらずグレーに統一
                            $badgeBgColor = '#e0e0e0';
                            $badgeTextColor = '#666';
                        @endphp
                        <label class="filter-badge {{ $isChecked ? 'is-selected' : '' }}" style="
                            display: flex;
                            align-items: center;
                            padding: 8px 10px;
                            margin-bottom: 2px;
                            cursor: pointer;
                            border-radius: 0;
                        " onmouseover="if(!this.querySelector('input[type=checkbox]').checked) this.style.backgroundColor='#fafafa';" onmouseout="if(!this.querySelector('input[type=checkbox]').checked) this.style.backgroundColor='transparent';">
                            <input type="checkbox" 
                                name="tags[]" 
                                value="{{ $tag->id }}" 
                                {{ $isChecked ? 'checked' : ''}}
                                onchange="this.parentElement.classList.toggle('is-selected', this.checked);"
                                style="
                                    margin-right: 10px;
                                    cursor: pointer;
                                    width: 16px;
                                    height: 16px;
                                    accent-color: #1a1a1a;
                                    border-radius: 0;
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
    <script>
        function toggleSearchForm() {
            const form = document.getElementById('searchForm');
            const icon = document.querySelector('.toggle-icon');
            
            if (form && icon) {
                form.classList.toggle('active');
                icon.classList.toggle('active');
            }
        }
        
        // 検索条件を取得して表示する関数
        function updateSelectedConditions() {
            if (window.innerWidth > 768) return; // スマホ版のみ
            
            const conditions = [];
            const conditionsDiv = document.getElementById('selectedConditions');
            
            // キーワード
            const keyword = document.getElementById('keyword')?.value?.trim();
            if (keyword) {
                conditions.push('キーワード: ' + keyword);
            }
            
            // エリア
            const areaCheckboxes = document.querySelectorAll('input[name="area[]"]:checked');
            if (areaCheckboxes.length > 0) {
                const areas = Array.from(areaCheckboxes).map(cb => {
                    const label = cb.parentElement.querySelector('span').textContent.trim();
                    return label;
                });
                if (areas.length > 0) {
                    conditions.push('エリア: ' + (areas.length > 2 ? areas.slice(0, 2).join(', ') + '...' : areas.join(', ')));
                }
            }
            
            // 雇用形態
            const employmentCheckboxes = document.querySelectorAll('input[name="employment_type[]"]:checked');
            if (employmentCheckboxes.length > 0) {
                const types = Array.from(employmentCheckboxes).map(cb => {
                    return cb.parentElement.querySelector('span').textContent.trim();
                });
                if (types.length > 0) {
                    conditions.push('雇用形態: ' + types.join(', '));
                }
            }
            
            // タグ
            const tagCheckboxes = document.querySelectorAll('input[name="tags[]"]:checked');
            if (tagCheckboxes.length > 0) {
                const tags = Array.from(tagCheckboxes).map(cb => {
                    return cb.parentElement.querySelector('span').textContent.trim();
                });
                if (tags.length > 0) {
                    conditions.push('タグ: ' + (tags.length > 2 ? tags.slice(0, 2).join(', ') + '...' : tags.join(', ')));
                }
            }
            
            // 条件を表示
            if (conditions.length > 0 && conditionsDiv) {
                conditionsDiv.textContent = conditions.join(' / ');
                conditionsDiv.style.display = 'block';
            } else if (conditionsDiv) {
                conditionsDiv.style.display = 'none';
            }
        }
        
        // 検索フォーム送信時に条件を表示して折りたたむ
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('searchForm');
            if (searchForm) {
                searchForm.addEventListener('submit', function(e) {
                    if (window.innerWidth <= 768) {
                        // 送信前に条件を更新
                        updateSelectedConditions();
                        // フォームを折りたたむ
                        const form = document.getElementById('searchForm');
                        const icon = document.querySelector('.toggle-icon');
                        if (form && icon) {
                            form.classList.remove('active');
                            icon.classList.remove('active');
                        }
                    }
                });
            }
            
            // チェックボックスの変更を監視
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateSelectedConditions);
            });
            
            // キーワード入力の変更を監視
            const keywordInput = document.getElementById('keyword');
            if (keywordInput) {
                keywordInput.addEventListener('input', updateSelectedConditions);
            }
            
            // ページ読み込み時に現在の検索条件を表示
            const urlParams = new URLSearchParams(window.location.search);
            const hasSearchParams = urlParams.has('keyword') || 
                                   urlParams.has('area') || 
                                   urlParams.has('employment_type') || 
                                   urlParams.has('tags');
            
            if (hasSearchParams && window.innerWidth <= 768) {
                // 少し遅延させてから条件を更新（フォームが読み込まれた後）
                setTimeout(function() {
                    updateSelectedConditionsFromURL();
                }, 300);
            } else if (window.innerWidth <= 768) {
                // 検索条件がない場合も、フォームの状態を確認
                setTimeout(function() {
                    updateSelectedConditions();
                }, 200);
            }
        });
        
        // URLパラメータから検索条件を読み取って表示
        function updateSelectedConditionsFromURL() {
            if (window.innerWidth > 768) return; // スマホ版のみ
            
            const conditions = [];
            const conditionsDiv = document.getElementById('selectedConditions');
            const urlParams = new URLSearchParams(window.location.search);
            
            // 都道府県コードから都道府県名へのマッピング
            const prefectureMap = {
                1: '北海道', 2: '青森', 3: '岩手', 4: '宮城', 5: '秋田', 6: '山形', 7: '福島',
                8: '茨城', 9: '栃木', 10: '群馬', 11: '埼玉', 12: '千葉', 13: '東京', 14: '神奈川',
                15: '新潟', 16: '富山', 17: '石川', 18: '福井', 19: '山梨', 20: '長野', 21: '岐阜',
                22: '静岡', 23: '愛知', 24: '三重', 25: '滋賀', 26: '京都', 27: '大阪', 28: '兵庫',
                29: '奈良', 30: '和歌山', 31: '鳥取', 32: '島根', 33: '岡山', 34: '広島', 35: '山口',
                36: '徳島', 37: '香川', 38: '愛媛', 39: '高知', 40: '福岡', 41: '佐賀', 42: '長崎',
                43: '熊本', 44: '大分', 45: '宮崎', 46: '鹿児島', 47: '沖縄'
            };
            
            // 雇用形態マッピング
            const employmentTypeMap = {
                1: '正社員',
                2: 'パート・アルバイト',
                3: '業務委託',
                4: '契約社員'
            };
            
            // キーワード
            const keyword = urlParams.get('keyword');
            if (keyword && keyword.trim()) {
                conditions.push('キーワード: ' + keyword.trim());
            }
            
            // エリア
            const areas = urlParams.getAll('area[]');
            if (areas.length > 0) {
                const areaNames = areas.map(code => {
                    const codeNum = parseInt(code);
                    return prefectureMap[codeNum] || code;
                }).filter(Boolean);
                if (areaNames.length > 0) {
                    conditions.push('エリア: ' + (areaNames.length > 2 ? areaNames.slice(0, 2).join(', ') + '...' : areaNames.join(', ')));
                }
            }
            
            // 雇用形態
            const employmentTypes = urlParams.getAll('employment_type[]');
            if (employmentTypes.length > 0) {
                const typeNames = employmentTypes.map(type => {
                    const typeNum = parseInt(type);
                    return employmentTypeMap[typeNum] || type;
                }).filter(Boolean);
                if (typeNames.length > 0) {
                    conditions.push('雇用形態: ' + typeNames.join(', '));
                }
            }
            
            // タグ（タグIDからタグ名を取得する必要があるが、ここではIDを表示）
            const tags = urlParams.getAll('tags[]');
            if (tags.length > 0) {
                // タグ名を取得するため、チェックボックスから取得を試みる
                setTimeout(function() {
                    const tagCheckboxes = document.querySelectorAll('input[name="tags[]"]:checked');
                    if (tagCheckboxes.length > 0) {
                        const tagNames = Array.from(tagCheckboxes).map(cb => {
                            return cb.parentElement.querySelector('span').textContent.trim();
                        });
                        if (tagNames.length > 0) {
                            const existingIndex = conditions.findIndex(c => c.startsWith('タグ:'));
                            if (existingIndex >= 0) {
                                conditions[existingIndex] = 'タグ: ' + (tagNames.length > 2 ? tagNames.slice(0, 2).join(', ') + '...' : tagNames.join(', '));
                            } else {
                                conditions.push('タグ: ' + (tagNames.length > 2 ? tagNames.slice(0, 2).join(', ') + '...' : tagNames.join(', ')));
                            }
                            if (conditions.length > 0 && conditionsDiv) {
                                conditionsDiv.textContent = conditions.join(' / ');
                                conditionsDiv.style.display = 'block';
                            }
                        }
                    }
                }, 100);
            }
            
            // 条件を表示
            if (conditions.length > 0 && conditionsDiv) {
                conditionsDiv.textContent = conditions.join(' / ');
                conditionsDiv.style.display = 'block';
            } else if (conditionsDiv) {
                conditionsDiv.style.display = 'none';
            }
        }
    </script>
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
        <div class="job-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 32px 24px;">
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
            {{ $jobs->links('vendor.pagination.tailwind') }}
        </div>
    @endif

    <style>
        /* レスポンシブ対応のスタイル */
        @media (max-width: 1200px) {
            .job-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)) !important;
                gap: 24px 20px !important;
            }
        }
        
        @media (max-width: 1024px) {
            .page-header {
                margin-bottom: 32px !important;
                padding-bottom: 24px !important;
            }
            .page-title {
                font-size: 28px !important;
            }
            .job-grid {
                grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)) !important;
                gap: 24px 16px !important;
            }
            .job-card > a > .job-card-body > div:first-child {
                height: 240px !important;
            }
        }
        
        @media (max-width: 768px) {
            .content {
                padding-top: 24px !important;
            }
            .page-header {
                margin-bottom: 24px !important;
                padding-bottom: 20px !important;
            }
            .page-label {
                font-size: 10px !important;
                margin-bottom: 8px !important;
            }
            .page-title {
                font-size: 24px !important;
                margin-bottom: 12px !important;
            }
            .page-lead {
                font-size: 13px !important;
            }
            .job-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)) !important;
                gap: 20px 12px !important;
            }
            .job-card > a > .job-card-body > div:first-child {
                height: 200px !important;
            }
            .job-card-title {
                font-size: 14px !important;
            }
            .job-card-salon {
                font-size: 11px !important;
            }
            .job-card-location {
                font-size: 10px !important;
            }
            .job-card-salary {
                font-size: 12px !important;
            }
            .pagination-wrapper {
                margin-top: 48px !important;
                padding-top: 24px !important;
            }
        }
        
        @media (max-width: 480px) {
            .page-header {
                margin-bottom: 20px !important;
                padding-bottom: 16px !important;
            }
            .page-label {
                font-size: 9px !important;
                margin-bottom: 6px !important;
            }
            .page-title {
                font-size: 20px !important;
                margin-bottom: 10px !important;
            }
            .page-lead {
                font-size: 12px !important;
                line-height: 1.6 !important;
            }
            .job-grid {
                grid-template-columns: 1fr !important;
                gap: 16px !important;
            }
            .job-card > a > .job-card-body > div:first-child {
                height: 180px !important;
            }
            .job-card > a > .job-card-body > div:last-child {
                padding: 16px 12px 0 12px !important;
            }
            .job-card-title {
                font-size: 13px !important;
            }
            .job-card-salon {
                font-size: 10px !important;
            }
            .job-card-location {
                font-size: 9px !important;
            }
            .job-card-salary {
                font-size: 11px !important;
            }
            .pagination-wrapper {
                margin-top: 32px !important;
                padding-top: 20px !important;
            }
            .empty-message {
                padding: 48px 0 !important;
                font-size: 13px !important;
            }
        }
        
        @media (min-width: 1400px) {
            .job-grid {
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)) !important;
                gap: 40px 32px !important;
            }
        }
    </style>
@endsection


