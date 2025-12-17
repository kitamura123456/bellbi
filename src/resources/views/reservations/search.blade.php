@php
use App\Enums\Todofuken;
@endphp
@extends('layouts.app')

@section('title', '予約可能な店舗を探す | Bellbi')

@section('sidebar')
    <div class="sidebar-card">
        <h3 class="sidebar-title">条件でさがす</h3>
        <form class="search-form">
            <div class="form-group">
                <label for="keyword">キーワード</label>
                <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}" placeholder="エリア・サロン名など">
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
    <div class="page-header">
        <p class="page-label">Reservation</p>
        <h1 class="page-title">予約可能な店舗</h1>
        <p class="page-lead">お気に入りのサロンを見つけて予約しましょう。</p>
    </div>

    @forelse($stores as $store)
    <div class="job-card" style="margin-bottom: 20px; display: flex; gap: 16px;">
        <div style="flex-shrink: 0;">
            @if($store->thumbnail_image)
                @if(strpos($store->thumbnail_image, 'templates/') === 0)
                    <img src="{{ asset('images/' . $store->thumbnail_image) }}" alt="{{ $store->name }}" style="width: 180px; height: 180px; object-fit: cover; border-radius: 12px;">
                @elseif(file_exists(public_path('storage/' . $store->thumbnail_image)))
                    <img src="{{ asset('storage/' . $store->thumbnail_image) }}" alt="{{ $store->name }}" style="width: 180px; height: 180px; object-fit: cover; border-radius: 12px;">
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
        <div style="flex: 1; display: flex; flex-direction: column;">
            <div class="job-card-body" style="flex: 1;">
                <span class="job-card-tag">予約可能</span>
                <h3 class="job-card-title">
                    <a href="{{ route('reservations.store', $store) }}">{{ $store->name }}</a>
                </h3>
                <p class="job-card-salon">{{ $store->company->name }}</p>
                @if($store->description)
                    <p style="margin-top: 8px; font-size: 14px; color: #6b7280; line-height: 1.6;">
                        {{ Str::limit($store->description, 120) }}
                    </p>
                @endif
                @if($store->address)
                    <p class="job-card-location" style="margin-top: 8px;">
                        <svg style="display: inline-block; width: 14px; height: 14px; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $store->address }}
                    </p>
                @endif
            </div>
            <div class="job-card-footer">
                <a href="{{ route('reservations.store', $store) }}" style="
                    padding: 8px 20px;
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
                    display: inline-block;
                " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                    予約する
                </a>
            </div>
        </div>
    </div>
    @empty
    <p class="empty-message">現在予約可能な店舗はありません。</p>
    @endforelse

    @if($stores->hasPages())
    <div class="pagination-wrapper">
        {{ $stores->links() }}
    </div>
    @endif
@endsection

