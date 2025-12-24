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
                <button type="button" id="getCurrentLocationBtn" onclick="getCurrentLocation()" style="
                    width: 100%;
                    padding: 10px 16px;
                    margin-bottom: 12px;
                    background-color: #1a1a1a;
                    color: #ffffff;
                    border: none;
                    border-radius: 0;
                    font-size: 12px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                " onmouseover="this.style.backgroundColor='#333'" onmouseout="this.style.backgroundColor='#1a1a1a'">
                    現在地から検索(位置情報を取得)
                </button>
                <div id="currentLocationInfo" style="
                    display: none;
                    padding: 8px 12px;
                    margin-bottom: 12px;
                    background-color: #f0f8ff;
                    border: 1px solid #b0d4f1;
                    border-radius: 0;
                    font-size: 11px;
                    color: #0066cc;
                "></div>
                {{-- 現在地から取得した市区町村セクション（動的に追加される） --}}
                <div id="currentLocationCitiesContainer"></div>
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
                                    <div style="display: flex; align-items: center;">
                                    <label class="filter-badge {{ $isChecked ? 'is-selected' : '' }}" style="
                                        display: flex;
                                        align-items: center;
                                        padding: 4px 8px;
                                        margin-bottom: 1px;
                                        cursor: pointer;
                                        border-radius: 0;
                                            flex: 1;
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
                                                    updateCitiesForPrefecture({{ $pref->value }}, this.checked);
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
                                        {{-- 市区町村を閉じるボタン（市区町村が表示されている時だけ表示） --}}
                                        <button type="button" 
                                            id="collapse-cities-{{ $pref->value }}"
                                            onclick="collapseCitiesForPrefecture({{ $pref->value }})"
                                            style="
                                                padding: 2px 6px;
                                                margin-right: 4px;
                                                background: transparent;
                                                border: none;
                                                cursor: pointer;
                                                font-size: 14px;
                                                color: #666;
                                                transition: color 0.3s ease;
                                                display: none;
                                                align-items: center;
                                                justify-content: center;
                                            "
                                            onmouseover="this.style.color='#1a1a1a';"
                                            onmouseout="this.style.color='#666';"
                                            title="市区町村を閉じる">
                                            ▲
                                        </button>
                                    </div>
                                    {{-- 市区町村コンテナ（都道府県の下に表示） --}}
                                    <div id="cities-{{ $pref->value }}" style="
                                        display: none;
                                        margin-left: 32px;
                                        margin-top: 4px;
                                        margin-bottom: 4px;
                                    "></div>
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
                        // チェックされている都道府県の市区町村を表示
                        const checkedPrefs = prefectureDiv.querySelectorAll('input[name="area[]"]:checked');
                        checkedPrefs.forEach(prefCheckbox => {
                            const prefCode = parseInt(prefCheckbox.value);
                            if (prefCode > 0 && prefCode <= 47) {
                                updateCitiesForPrefecture(prefCode, true);
                            }
                        });
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
                            // 市区町村も非表示
                            const prefCode = parseInt(cb.value);
                            if (prefCode > 0 && prefCode <= 47) {
                                updateCitiesForPrefecture(prefCode, false);
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
                
                // 特定の都道府県の市区町村を表示/非表示
                function updateCitiesForPrefecture(prefCode, isChecked) {
                    const citiesContainer = document.getElementById('cities-' + prefCode);
                    const collapseButton = document.getElementById('collapse-cities-' + prefCode);
                    
                    if (!citiesContainer) return;
                    
                    if (!isChecked) {
                        // チェックが外れたら市区町村を非表示
                        citiesContainer.style.display = 'none';
                        // データをクリアして、次回読み込み時に再取得できるようにする
                        citiesContainer.innerHTML = '';
                        citiesContainer.dataset.loaded = 'false';
                        if (collapseButton) {
                            collapseButton.style.display = 'none';
                        }
                        return;
                    }
                    
                    // 既に読み込み済みで内容がある場合は表示のみ
                    if (citiesContainer.dataset.loaded === 'true' && citiesContainer.innerHTML.trim() !== '') {
                        citiesContainer.style.display = 'block';
                        if (collapseButton) {
                            collapseButton.style.display = 'flex';
                        }
                        return;
                    }
                    
                    // 市区町村を取得
                    fetch(`{{ url('/api/cities/by-prefecture') }}?prefecture_code=${prefCode}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(result => {
                        if (!result.success || !result.cities || result.cities.length === 0) {
                            citiesContainer.style.display = 'none';
                            if (collapseButton) {
                                collapseButton.style.display = 'none';
                            }
                            return;
                        }
                        
                        citiesContainer.innerHTML = '';
                        
                        // 市区町村を追加
                        result.cities.forEach(city => {
                            const labelEl = document.createElement('label');
                            labelEl.className = 'filter-badge';
                            labelEl.style.cssText = 'display: flex; align-items: center; padding: 4px 8px; margin-bottom: 1px; cursor: pointer; border-radius: 0;';
                            
                            const checkbox = document.createElement('input');
                            checkbox.type = 'checkbox';
                            checkbox.name = 'city[]';
                            checkbox.value = city.city_code;
                            checkbox.style.cssText = 'margin-right: 8px; cursor: pointer; width: 14px; height: 14px; accent-color: #1a1a1a; border-radius: 0;';
                            checkbox.onchange = function() {
                                const badge = this.parentElement;
                                if (badge) {
                                    badge.classList.toggle('is-selected', this.checked);
                                }
                                // 他の同じ市区町村のチェックも連動
                                syncCityCheckboxes(city.city_code, this.checked);
                            };
                            
                            const span = document.createElement('span');
                            span.textContent = city.name;
                            span.style.cssText = 'flex: 1; font-size: 12px; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Hiragino Sans", "Yu Gothic", "Noto Sans JP", sans-serif;';
                            
                            if (city.count > 0) {
                                const countSpan = document.createElement('span');
                                countSpan.textContent = city.count;
                                countSpan.style.cssText = 'display: inline-block; padding: 1px 6px; background-color: #e0e0e0; color: #666; border-radius: 0; font-size: 10px; font-weight: 500; min-width: 20px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Hiragino Sans", "Yu Gothic", "Noto Sans JP", sans-serif;';
                                labelEl.appendChild(checkbox);
                                labelEl.appendChild(span);
                                labelEl.appendChild(countSpan);
                            } else {
                                labelEl.appendChild(checkbox);
                                labelEl.appendChild(span);
                            }
                            
                            citiesContainer.appendChild(labelEl);
                        });
                        
                        citiesContainer.dataset.loaded = 'true';
                        citiesContainer.style.display = 'block';
                        if (collapseButton) {
                            collapseButton.style.display = 'flex';
                        }
                    })
                    .catch(error => {
                        console.error('市区町村の取得に失敗しました:', error);
                        citiesContainer.style.display = 'none';
                        if (collapseButton) {
                            collapseButton.style.display = 'none';
                        }
                    });
                }
                
                // 都道府県の市区町村を閉じる
                function collapseCitiesForPrefecture(prefCode) {
                    const citiesContainer = document.getElementById('cities-' + prefCode);
                    const collapseButton = document.getElementById('collapse-cities-' + prefCode);
                    
                    if (citiesContainer) {
                        citiesContainer.style.display = 'none';
                    }
                    if (collapseButton) {
                        collapseButton.style.display = 'none';
                    }
                }
                
                // 選択された都道府県に基づいて市区町村を動的に表示（初期表示用）
                function updateCitiesByPrefecture() {
                    const checkedPrefs = Array.from(document.querySelectorAll('input[name="area[]"]:checked'))
                        .map(cb => parseInt(cb.value))
                        .filter(v => v > 0 && v <= 47);
                    
                    checkedPrefs.forEach(prefCode => {
                        updateCitiesForPrefecture(prefCode, true);
                    });
                }
                
                // 現在地を取得して市区町村を検索
                function getCurrentLocation() {
                    const btn = document.getElementById('getCurrentLocationBtn');
                    const info = document.getElementById('currentLocationInfo');
                    
                    if (!navigator.geolocation) {
                        info.style.display = 'block';
                        info.style.backgroundColor = '#fff3cd';
                        info.style.borderColor = '#ffc107';
                        info.style.color = '#856404';
                        info.textContent = 'お使いのブラウザは位置情報をサポートしていません。';
                        return;
                    }
                    
                    // HTTPSでない場合の警告
                    if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
                        info.style.display = 'block';
                        info.style.backgroundColor = '#fff3cd';
                        info.style.borderColor = '#ffc107';
                        info.style.color = '#856404';
                        info.textContent = '位置情報を取得するにはHTTPS接続が必要です。';
                        return;
                    }
                    
                    btn.disabled = true;
                    btn.textContent = '位置情報を取得中...';
                    info.style.display = 'block';
                    info.style.backgroundColor = '#f0f8ff';
                    info.style.borderColor = '#b0d4f1';
                    info.style.color = '#0066cc';
                    info.textContent = '位置情報を取得しています...';
                    
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            
                            console.log('位置情報取得成功:', lat, lng);
                            
                            // サーバー側で逆ジオコーディングを実行（CORS回避）
                            fetch('{{ url("/api/cities/by-location") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    latitude: lat,
                                    longitude: lng
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('HTTP error! status: ' + response.status);
                                }
                                return response.json();
                            })
                            .then(result => {
                                console.log('API結果:', result);
                                
                                if (result.success && result.cities && result.cities.length > 0) {
                                    // 都道府県を自動選択
                                    selectPrefectureByCode(result.prefecture_code);
                                    
                                    // 市区町村を表示（最初の1つだけ）
                                    // 少し待ってから市区町村を選択（市区町村が表示されるのを待つ）
                                    setTimeout(() => {
                                        displayCitiesFromLocation(result.cities, result.prefecture_code, result.prefecture_name);
                                    }, 300);
                                    
                                    info.style.backgroundColor = '#d4edda';
                                    info.style.borderColor = '#c3e6cb';
                                    info.style.color = '#155724';
                                    info.textContent = `現在地: ${result.prefecture_name}${result.city_name ? ' ' + result.city_name : ''}`;
                                } else {
                                    info.style.backgroundColor = '#fff3cd';
                                    info.style.borderColor = '#ffc107';
                                    info.style.color = '#856404';
                                    info.textContent = result.message || '市区町村データが見つかりませんでした。';
                                }
                                btn.disabled = false;
                                btn.textContent = '現在地から検索(位置情報を取得)';
                            })
                            .catch(error => {
                                console.error('API Error:', error);
                                info.style.backgroundColor = '#f8d7da';
                                info.style.borderColor = '#f5c6cb';
                                info.style.color = '#721c24';
                                info.textContent = 'エラーが発生しました: ' + error.message;
                                btn.disabled = false;
                                btn.textContent = '現在地から検索(位置情報を取得)';
                            });
                        },
                        function(error) {
                            btn.disabled = false;
                            btn.textContent = '現在地から検索(位置情報を取得)';
                            info.style.display = 'block';
                            info.style.backgroundColor = '#f8d7da';
                            info.style.borderColor = '#f5c6cb';
                            info.style.color = '#721c24';
                            
                            let errorMsg = '位置情報の取得に失敗しました。';
                            switch(error.code) {
                                case error.PERMISSION_DENIED:
                                    errorMsg = '位置情報の使用が拒否されました。ブラウザの設定で位置情報の許可を確認してください。';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    errorMsg = '位置情報が利用できません。GPSがオフになっている可能性があります。';
                                    break;
                                case error.TIMEOUT:
                                    errorMsg = '位置情報の取得がタイムアウトしました。もう一度お試しください。';
                                    break;
                            }
                            info.textContent = errorMsg;
                            console.error('位置情報取得エラー:', error);
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 15000,
                            maximumAge: 60000
                        }
                    );
                }
                
                // 都道府県コードから都道府県を自動選択
                function selectPrefectureByCode(prefectureCode) {
                    // 都道府県コードに対応するチェックボックスを探す
                    const prefCheckbox = document.querySelector(`input[name="area[]"][value="${prefectureCode}"]`);
                    if (!prefCheckbox) return;
                    
                    // チェックボックスをチェック
                    prefCheckbox.checked = true;
                    
                    // バッジのスタイルを更新
                    const badge = prefCheckbox.parentElement;
                    if (badge) {
                        badge.classList.add('is-selected');
                    }
                    
                    // 地域を探す
                    const prefectureDiv = prefCheckbox.closest('[id^="prefectures-"]');
                    if (prefectureDiv) {
                        const regionKey = prefectureDiv.id.replace('prefectures-', '');
                        
                        // 地域のチェックボックスを更新
                        const regionCheckbox = document.getElementById('region-' + regionKey);
                        if (regionCheckbox) {
                            regionCheckbox.checked = true;
                            updateRegionCheckbox(regionKey);
                        }
                        
                        // 地域を展開
                        prefectureDiv.style.display = 'block';
                        const label = document.querySelector('.region-label-' + regionKey);
                        if (label) {
                            label.style.backgroundColor = '#f5f5f5';
                        }
                        
                        // 市区町村を表示
                        updateCitiesForPrefecture(prefectureCode, true);
                    }
                }
                
                // 現在地から取得した市区町村を表示（1つだけ）
                let currentLocationPrefectureCode = null; // 現在地の都道府県コードを保持
                function displayCitiesFromLocation(cities, prefectureCode, prefectureName) {
                    currentLocationPrefectureCode = prefectureCode; // 都道府県コードを保存
                    // コンテナを取得
                    const container = document.getElementById('currentLocationCitiesContainer');
                    if (!container) return;
                    
                    // 既存の内容をクリア
                    container.innerHTML = '';
                    
                    if (cities.length === 0) return;
                    
                    // 最初の市区町村のみを表示
                    const city = cities[0];
                    
                    // 新しい市区町村セクションを作成
                    const citySection = document.createElement('div');
                    citySection.className = 'form-group current-location-cities';
                    citySection.style.marginBottom = '24px';
                    
                    const label = document.createElement('label');
                    label.textContent = '現在地の市区町村';
                    label.style.cssText = 'display: block; font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.02em;';
                    
                    const checkboxList = document.createElement('div');
                    checkboxList.style.cssText = 'max-height: 300px; overflow-y: auto; border: 1px solid #e0e0e0; border-radius: 0; padding: 8px; background-color: #ffffff;';
                    
                    // 市区町村を1つだけ表示（自動的にチェック）
                    const labelEl = document.createElement('label');
                    labelEl.className = 'filter-badge is-selected';
                    labelEl.style.cssText = 'display: flex; align-items: center; padding: 4px 8px; margin-bottom: 1px; cursor: pointer; border-radius: 0;';
                    
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'city[]';
                    checkbox.value = city.city_code;
                    checkbox.checked = true; // 自動的にチェック
                    checkbox.style.cssText = 'margin-right: 8px; cursor: pointer; width: 14px; height: 14px; accent-color: #1a1a1a; border-radius: 0;';
                    checkbox.onchange = function() {
                        const badge = this.parentElement;
                        if (badge) {
                            badge.classList.toggle('is-selected', this.checked);
                        }
                        // エリアセクションの同じ市区町村のチェックも連動
                        syncCityCheckboxes(city.city_code, this.checked);
                        
                        // 都道府県のチェックも連動
                        if (currentLocationPrefectureCode) {
                            if (this.checked) {
                                // チェックが付けられた場合、都道府県も選択
                                selectPrefectureByCode(currentLocationPrefectureCode);
                            } else {
                                // チェックが外された場合、都道府県のチェックも確認
                                checkAndUncheckPrefectureIfNoCities(currentLocationPrefectureCode);
                            }
                        }
                    };
                    
                    const span = document.createElement('span');
                    span.textContent = city.name;
                    span.style.cssText = 'flex: 1; font-size: 12px; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Hiragino Sans", "Yu Gothic", "Noto Sans JP", sans-serif;';
                    
                    labelEl.appendChild(checkbox);
                    labelEl.appendChild(span);
                    checkboxList.appendChild(labelEl);
                    
                    citySection.appendChild(label);
                    citySection.appendChild(checkboxList);
                    container.appendChild(citySection);
                    
                    // エリアセクションの市区町村も選択状態にする（少し待ってから実行）
                    setTimeout(() => {
                        syncCityCheckboxes(city.city_code, true);
                    }, 500);
                }
                
                // 市区町村のチェックボックスを連動させる
                function syncCityCheckboxes(cityCode, isChecked) {
                    const allCityCheckboxes = document.querySelectorAll(`input[name="city[]"][value="${cityCode}"]`);
                    allCityCheckboxes.forEach(cb => {
                        if (cb.checked !== isChecked) {
                            cb.checked = isChecked;
                            const badge = cb.parentElement;
                            if (badge) {
                                badge.classList.toggle('is-selected', isChecked);
                            }
                        }
                    });
                }
                
                // 都道府県の市区町村がすべて選択されていない場合、都道府県のチェックを外す
                function checkAndUncheckPrefectureIfNoCities(prefectureCode) {
                    // その都道府県の市区町村のチェックボックスを取得
                    const citiesContainer = document.getElementById('cities-' + prefectureCode);
                    let hasCheckedCity = false;
                    
                    if (citiesContainer) {
                        const cityCheckboxes = citiesContainer.querySelectorAll('input[name="city[]"]');
                        hasCheckedCity = Array.from(cityCheckboxes).some(cb => cb.checked);
                    }
                    
                    // 現在地の市区町村も確認
                    const currentLocationCheckboxes = document.querySelectorAll('#currentLocationCitiesContainer input[name="city[]"]');
                    const hasCheckedCurrentLocation = Array.from(currentLocationCheckboxes).some(cb => cb.checked);
                    
                    // その都道府県の市区町村が1つも選択されていない場合
                    if (!hasCheckedCity && !hasCheckedCurrentLocation) {
                        const prefCheckbox = document.querySelector(`input[name="area[]"][value="${prefectureCode}"]`);
                        if (prefCheckbox && prefCheckbox.checked) {
                            prefCheckbox.checked = false;
                            const badge = prefCheckbox.parentElement;
                            if (badge) {
                                badge.classList.remove('is-selected');
                            }
                            // 地域のチェックボックスも更新
                            const prefectureDiv = prefCheckbox.closest('[id^="prefectures-"]');
                            if (prefectureDiv) {
                                const regionKey = prefectureDiv.id.replace('prefectures-', '');
                                updateRegionCheckbox(regionKey);
                            }
                        }
                    }
                }
                
                // ページ読み込み時に選択されている地域を展開し、市区町村を表示
                document.addEventListener('DOMContentLoaded', function() {
                    // 初期表示時に市区町村を更新
                    updateCitiesByPrefecture();
                    
                    // チェックされている都道府県の市区町村を表示
                    const allCheckedPrefs = document.querySelectorAll('input[name="area[]"]:checked');
                    allCheckedPrefs.forEach(prefCheckbox => {
                        const prefCode = parseInt(prefCheckbox.value);
                        if (prefCode > 0 && prefCode <= 47) {
                            updateCitiesForPrefecture(prefCode, true);
                        }
                    });
                    
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


