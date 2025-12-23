@php
use App\Enums\Todofuken;
@endphp
@extends('layouts.app')

@section('title', '予約可能な店舗を探す | Bellbi')

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
                .checkbox-list {
                    padding: 6px !important;
                    max-height: 250px !important;
                }
                .checkbox-list label {
                    padding: 6px 8px !important;
                    font-size: 12px !important;
                }
                .checkbox-list label span {
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
                .checkbox-list {
                    padding: 6px !important;
                    max-height: 200px !important;
                }
                .checkbox-list label {
                    padding: 6px 8px !important;
                    font-size: 11px !important;
                }
                .checkbox-list label span {
                    font-size: 11px !important;
                }
                .checkbox-list label input[type="checkbox"] {
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
                .checkbox-list {
                    padding: 4px !important;
                    max-height: 180px !important;
                }
                .checkbox-list label {
                    padding: 4px 6px !important;
                    font-size: 10px !important;
                }
                .checkbox-list label span {
                    font-size: 10px !important;
                }
                .checkbox-list label input[type="checkbox"] {
                    width: 12px !important;
                    height: 12px !important;
                    margin-right: 6px !important;
                }
                button[type="submit"] {
                    padding: 10px 14px !important;
                    font-size: 10px !important;
                }
            }
        </style>
        <form class="search-form" id="searchForm">
            <div class="form-group" style="margin-bottom: 24px;">
                <label for="keyword" style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.02em;">キーワード</label>
                <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}" placeholder="エリア・サロン名など" style="width: 100%; padding: 12px 16px; border: 1px solid #e0e0e0; border-radius: 0; font-size: 14px; background: #ffffff; color: #1a1a1a; transition: all 0.3s ease; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;" onfocus="this.style.borderColor='#1a1a1a'; this.style.outline='none';" onblur="this.style.borderColor='#e0e0e0';">
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
                                        border-radius: 0;
                                        transition: background-color 0.3s ease;
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
                searchForm.addEventListener('submit', function() {
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
            const hasSearchParams = urlParams.has('keyword') || urlParams.has('area');
            
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
    <div style="margin-top: 24px;">
    <header class="page-header" style="margin-bottom: 48px; padding-bottom: 32px; border-bottom: 1px solid #f0f0f0;">
        <p class="page-label" style="font-size: 11px; color: #999; letter-spacing: 0.15em; text-transform: uppercase; margin: 0 0 12px 0; font-weight: 500; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">Reservation</p>
        <h1 class="page-title" style="font-size: 32px; font-weight: 400; color: #1a1a1a; margin: 0 0 16px 0; letter-spacing: -0.02em; line-height: 1.3; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">予約可能な店舗</h1>
        <p class="page-lead" style="font-size: 14px; color: #666; line-height: 1.7; margin: 0; font-weight: 400; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">お気に入りのサロンを見つけて予約しましょう。</p>
    </header>

    @forelse($stores as $store)
    <article class="job-card" style="background: #ffffff; border: 1px solid transparent; padding: 0; transition: border-color 0.3s ease; position: relative; margin-bottom: 32px;" onmouseover="this.style.borderColor='#1a1a1a';" onmouseout="this.style.borderColor='transparent';">
        <a href="{{ route('reservations.store', $store) }}" style="text-decoration: none; color: inherit; display: block;">
            <div class="job-card-body" style="padding: 0; display: flex; gap: 24px;">
                <div style="flex-shrink: 0; width: 280px; height: 280px; overflow: hidden; background: #fafafa;">
                    @if($store->thumbnail_image)
                        @if(strpos($store->thumbnail_image, 'templates/') === 0)
                            <img src="{{ asset('images/' . $store->thumbnail_image) }}" alt="{{ $store->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: opacity 0.3s ease;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                        @elseif(file_exists(public_path('storage/' . $store->thumbnail_image)))
                            <img src="{{ asset('storage/' . $store->thumbnail_image) }}" alt="{{ $store->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: opacity 0.3s ease;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
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
                <div style="flex: 1; display: flex; flex-direction: column; padding: 20px 12px 20px 0;">
                    <div style="flex: 1;">
                        <p style="display: inline-block; font-size: 10px; color: #999; letter-spacing: 0.1em; text-transform: uppercase; margin: 0 0 12px 0; font-weight: 500; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">予約可能</p>
                        <h3 class="job-card-title" style="margin: 0 0 8px 0; font-size: 18px; font-weight: 400; color: #1a1a1a; line-height: 1.4; letter-spacing: -0.01em; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                            {{ $store->name }}
                        </h3>
                        <p style="margin: 0 0 12px 0; font-size: 13px; color: #666; font-weight: 400; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{{ $store->company->name }}</p>
                        @if($store->description)
                            <p class="store-description" style="margin: 0 0 12px 0; font-size: 13px; color: #666; line-height: 1.6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                                {{ Str::limit($store->description, 120) }}
                            </p>
                        @endif
                        @if($store->address)
                            <p style="margin: 0; font-size: 12px; color: #999; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                                <svg style="display: inline-block; width: 12px; height: 12px; margin-right: 4px; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $store->address }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    </article>
    @empty
    <p class="empty-message" style="font-size: 14px; color: #999; text-align: center; padding: 64px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">現在予約可能な店舗はありません。</p>
    @endforelse

    @if($stores->hasPages())
    <div class="pagination-wrapper" style="margin-top: 64px; padding-top: 32px; border-top: 1px solid #f0f0f0;">
        {{ $stores->links() }}
    </div>
    @endif

    <style>
        /* スマホ版のコンパクト化 */
        @media (max-width: 768px) {
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
                display: none !important;
            }
            .job-card {
                margin-bottom: 20px !important;
            }
            .job-card-body {
                flex-direction: column !important;
                gap: 12px !important;
            }
            .job-card-body > div:first-child {
                width: 100% !important;
                height: 200px !important;
            }
            .job-card-body > div:last-child {
                padding: 12px 0 0 0 !important;
            }
            .job-card-title {
                font-size: 16px !important;
                margin-bottom: 6px !important;
            }
            .job-card-body p[style*="font-size: 10px"] {
                font-size: 9px !important;
                margin-bottom: 8px !important;
            }
            .job-card-body p[style*="font-size: 13px"] {
                font-size: 12px !important;
                margin-bottom: 8px !important;
            }
            .job-card-body p[style*="font-size: 12px"] {
                font-size: 11px !important;
            }
            /* 説明文を非表示 */
            .store-description {
                display: none !important;
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
            .job-card {
                margin-bottom: 16px !important;
            }
            .job-card-body > div:first-child {
                height: 180px !important;
            }
            .job-card-body > div:last-child {
                padding: 10px 0 0 0 !important;
            }
            .job-card-title {
                font-size: 15px !important;
                margin-bottom: 4px !important;
            }
            .job-card-body p[style*="font-size: 10px"] {
                font-size: 8px !important;
                margin-bottom: 6px !important;
            }
            .job-card-body p[style*="font-size: 13px"] {
                font-size: 11px !important;
                margin-bottom: 6px !important;
            }
            .job-card-body p[style*="font-size: 12px"] {
                font-size: 10px !important;
            }
            .pagination-wrapper {
                margin-top: 24px !important;
                padding-top: 16px !important;
            }
        }
    </style>
    </div>
@endsection

