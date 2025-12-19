@extends('layouts.app')

@section('title', $store->name . ' - 予約 | Bellbi')

@section('sidebar')
    {{-- サイドバーなし --}}
@endsection

@section('content')
    <style>
        /* 予約ページのみ：サイドバーを非表示にしてコンテンツを中央寄せ */
        @media (min-width: 769px) {
            .reservation-store-page-wrapper .main-inner {
                justify-content: center !important;
            }
            .reservation-store-page-wrapper .sidebar {
                display: none !important;
            }
            .reservation-store-page-wrapper .content {
                max-width: 800px !important;
                width: 100% !important;
                margin: 0 auto !important;
            }
        }
    </style>
    <script>
        // ページ読み込み時にbodyにクラスを追加
        (function() {
            document.body.classList.add('reservation-store-page-wrapper');
        })();
    </script>
    <div class="reservation-store-page">
    <header class="page-header" style="margin-bottom: 48px; padding-bottom: 32px; border-bottom: 1px solid #f0f0f0;">
        <h1 class="page-title" style="font-size: 32px; font-weight: 400; color: #1a1a1a; margin: 0 0 12px 0; letter-spacing: -0.02em; line-height: 1.3; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{{ $store->name }}</h1>
        <p class="page-lead" style="font-size: 14px; color: #666; line-height: 1.7; margin: 0; font-weight: 400; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{{ $store->company->name }}</p>
    </header>

    @if($store->thumbnail_image)
        @if(strpos($store->thumbnail_image, 'templates/') === 0)
        <div class="job-detail-card" style="background: #ffffff; border: none; padding: 0; margin-bottom: 32px;">
            <img src="{{ asset('images/' . $store->thumbnail_image) }}" alt="{{ $store->name }}" style="width: 100%; max-height: 500px; object-fit: cover;">
        </div>
        @elseif(file_exists(public_path('storage/' . $store->thumbnail_image)))
        <div class="job-detail-card" style="background: #ffffff; border: none; padding: 0; margin-bottom: 32px;">
            <img src="{{ asset('storage/' . $store->thumbnail_image) }}" alt="{{ $store->name }}" style="width: 100%; max-height: 500px; object-fit: cover;">
        </div>
        @else
        <div class="job-detail-card" style="background: #ffffff; border: none; padding: 0; margin-bottom: 32px;">
            <div style="width: 100%; height: 400px; background: #fafafa; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 14px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                No Image
            </div>
        </div>
        @endif
    @endif

    @if($store->images && $store->images->count() > 0)
    <div class="job-detail-card" style="background: #ffffff; border: none; padding: 0; margin-bottom: 32px;">
        <h3 style="margin: 0 0 24px 0; font-size: 18px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">画像ギャラリー</h3>
        @php
            $imageCount = $store->images->count();
        @endphp
        
        @if($imageCount === 1)
            {{-- 1枚の場合は大きく表示 --}}
            <div style="display: flex; justify-content: center;">
                @foreach($store->images as $image)
                    <img src="{{ asset('storage/' . $image->path) }}" 
                         alt="店舗画像" 
                         class="gallery-image"
                         data-image-index="0"
                         style="max-width: 100%; max-height: 500px; object-fit: contain; cursor: pointer; transition: opacity 0.3s ease;"
                         onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';"
                         onclick="openStoreImageModal(0)">
                @endforeach
            </div>
        @else
            {{-- 複数枚の場合はグリッドレイアウト（最適化） --}}
            <div class="image-grid" style="
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 12px;
            ">
                @foreach($store->images as $index => $image)
                    <div style="position: relative; width: 100%; aspect-ratio: 1; overflow: hidden; background: #fafafa; cursor: pointer; transition: all 0.3s ease;" 
                         onmouseover="this.style.opacity='0.95'; this.querySelector('img').style.transform='scale(1.05)';" 
                         onmouseout="this.style.opacity='1'; this.querySelector('img').style.transform='scale(1)';"
                         onclick="openStoreImageModal({{ $index }})">
                    <img src="{{ asset('storage/' . $image->path) }}" 
                         alt="店舗画像 {{ $index + 1 }}" 
                         class="gallery-image"
                         data-image-index="{{ $index }}"
                             style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @endif

    @if($store->description)
    <div class="job-detail-card" style="background: #ffffff; border: none; padding: 0; margin-bottom: 32px;">
        <h3 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">店舗について</h3>
        <p style="white-space: pre-wrap; line-height: 1.8; font-size: 14px; color: #666; margin: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{{ $store->description }}</p>
    </div>
    @endif

    <div class="job-detail-card" style="background: #ffffff; border: none; padding: 0; margin-bottom: 32px;">
        <h3 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">店舗情報</h3>
        <table class="company-table" style="width: 100%; border-collapse: collapse; font-size: 14px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
            @if($store->address)
            <tr style="border-bottom: 1px solid #f0f0f0;">
                <th style="width: 120px; padding: 12px 0; text-align: left; font-weight: 500; color: #666; vertical-align: top;">住所</th>
                <td style="padding: 12px 0; color: #1a1a1a;">{{ $store->address }}</td>
            </tr>
            @endif
            @if($store->tel)
            <tr style="border-bottom: 1px solid #f0f0f0;">
                <th style="padding: 12px 0; text-align: left; font-weight: 500; color: #666; vertical-align: top;">電話番号</th>
                <td style="padding: 12px 0; color: #1a1a1a;">{{ $store->tel }}</td>
            </tr>
            @endif
            @if($store->cancel_deadline_hours)
            <tr style="border-bottom: 1px solid #f0f0f0;">
                <th style="padding: 12px 0; text-align: left; font-weight: 500; color: #666; vertical-align: top;">キャンセル</th>
                <td style="padding: 12px 0; color: #1a1a1a;">予約の{{ $store->cancel_deadline_hours }}時間前までキャンセル可能</td>
            </tr>
            @endif
        </table>
    </div>

    <form action="{{ route('reservations.booking', $store) }}" method="POST" id="reservation-form">
        @csrf

        <div class="job-detail-card" style="background: #ffffff; border: none; padding: 0; margin-bottom: 32px;">
            <h3 style="margin: 0 0 8px 0; font-size: 18px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">メニュー選択 <span class="required" style="color: #dc2626; font-weight: 500; margin-left: 4px;">必須</span></h3>
            <p style="font-size: 13px; color: #999; margin: 0 0 24px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">ご希望のメニューを選択してください（複数選択可）</p>
            
            @if($menus->isEmpty())
                <p class="empty-message" style="font-size: 14px; color: #999; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">メニューが登録されていません。</p>
            @else
                @foreach($menus as $menu)
                <label class="menu-option" style="display: flex; gap: 16px; padding: 16px; background-color: #ffffff; border: 1px solid #f0f0f0; margin-bottom: 12px; cursor: pointer; transition: all 0.3s ease;" onmouseover="if(!this.querySelector('input[type=checkbox]').checked) this.style.borderColor='#1a1a1a';" onmouseout="if(!this.querySelector('input[type=checkbox]').checked) this.style.borderColor='#f0f0f0';">
                    <input type="checkbox" name="menu_ids[]" value="{{ $menu->id }}" style="margin-top: 4px; accent-color: #1a1a1a; cursor: pointer; width: 18px; height: 18px;" onchange="if(this.checked) { this.closest('label').style.borderColor='#1a1a1a'; } else { this.closest('label').style.borderColor='#f0f0f0'; }">
                    <div style="flex-shrink: 0;">
                        @if($menu->thumbnail_image)
                            @if(strpos($menu->thumbnail_image, 'templates/') === 0)
                                <img src="{{ asset('images/' . $menu->thumbnail_image) }}" alt="{{ $menu->name }}" style="width: 80px; height: 80px; object-fit: cover;">
                            @elseif(file_exists(public_path('storage/' . $menu->thumbnail_image)))
                                <img src="{{ asset('storage/' . $menu->thumbnail_image) }}" alt="{{ $menu->name }}" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div style="width: 80px; height: 80px; background: #fafafa; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 11px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                                    No Image
                                </div>
                            @endif
                        @else
                            <div style="width: 80px; height: 80px; background: #fafafa; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 11px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                                No Image
                            </div>
                        @endif
                    </div>
                    <div style="flex: 1;">
                        <div>
                            <strong style="font-size: 15px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{{ $menu->name }}</strong>
                            <span style="color: #1a1a1a; margin-left: 12px; font-weight: 500; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{{ number_format($menu->price) }}円</span>
                            <span style="color: #999; margin-left: 8px; font-size: 13px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">（{{ $menu->duration_minutes }}分）</span>
                        </div>
                        @if($menu->description)
                            <div style="font-size: 13px; color: #666; margin-top: 8px; line-height: 1.6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{{ $menu->description }}</div>
                        @endif
                    </div>
                </label>
                @endforeach
            @endif

            @error('menu_ids')
                <span class="error" style="color: #dc2626; font-size: 12px; margin-top: 8px; display: block; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{{ $message }}</span>
            @enderror
        </div>

        @if($staffs->isNotEmpty())
        <div class="job-detail-card" style="background: #ffffff; border: none; padding: 0; margin-bottom: 32px;">
            <h3 style="margin: 0 0 8px 0; font-size: 18px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">スタッフ指名（任意）</h3>
            <p style="font-size: 13px; color: #999; margin: 0 0 24px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">ご希望のスタッフを選択してください</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 16px;">
                <label style="display: flex; flex-direction: column; align-items: center; padding: 16px; background-color: #ffffff; border: 1px solid #f0f0f0; cursor: pointer; transition: all 0.3s ease;" class="staff-option" onmouseover="this.style.borderColor='#1a1a1a';" onmouseout="if(!this.querySelector('input[type=radio]').checked) this.style.borderColor='#f0f0f0';">
                    <input type="radio" name="staff_id" value="" checked style="display: none;">
                    <div style="width: 80px; height: 80px; background: #fafafa; display: flex; align-items: center; justify-content: center; color: #999; font-size: 11px; margin-bottom: 8px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                        指名なし
                    </div>
                    <span style="font-size: 13px; font-weight: 400; text-align: center; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">指名なし</span>
                </label>
                @foreach($staffs as $staff)
                    <label style="display: flex; flex-direction: column; align-items: center; padding: 16px; background-color: #ffffff; border: 1px solid #f0f0f0; cursor: pointer; transition: all 0.3s ease;" class="staff-option" onmouseover="this.style.borderColor='#1a1a1a';" onmouseout="if(!this.querySelector('input[type=radio]').checked) this.style.borderColor='#f0f0f0';">
                        <input type="radio" name="staff_id" value="{{ $staff->id }}" style="display: none;" onchange="document.querySelectorAll('.staff-option').forEach(opt => { if(!opt.querySelector('input[type=radio]').checked) opt.style.borderColor='#f0f0f0'; }); this.closest('label').style.borderColor='#1a1a1a';">
                        @if($staff->image_path)
                            <img src="{{ asset('storage/' . $staff->image_path) }}" alt="{{ $staff->name }}" style="width: 80px; height: 80px; object-fit: cover; margin-bottom: 8px;">
                        @else
                            <div style="width: 80px; height: 80px; background: #fafafa; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 11px; margin-bottom: 8px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                                No Image
                            </div>
                        @endif
                        <span style="font-size: 13px; font-weight: 400; text-align: center; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{{ $staff->name }}</span>
                    </label>
                @endforeach
            </div>
            
            @error('staff_id')
                <span class="error" style="color: #dc2626; font-size: 12px; margin-top: 16px; display: block; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{{ $message }}</span>
            @enderror
        </div>
        @endif

        <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 48px; padding-top: 32px; border-top: 1px solid #f0f0f0;">
            <button type="submit" style="
                padding: 14px 32px;
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
                日時を選択する
            </button>
            <a href="{{ route('reservations.search') }}" style="
                padding: 14px 32px;
                background: transparent;
                color: #1a1a1a;
                border: 1px solid #1a1a1a;
                border-radius: 0;
                font-size: 13px;
                font-weight: 500;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.3s ease;
                position: relative;
                letter-spacing: 0.05em;
                text-transform: uppercase;
            " onmouseover="this.style.background='#1a1a1a'; this.style.color='#ffffff';" onmouseout="this.style.background='transparent'; this.style.color='#1a1a1a';">
                戻る
            </a>
        </div>
    </form>

    <script>
        // ページ読み込み時に、既に選択されているチェックボックスの親labelに黒いボーダーを適用
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[type="checkbox"][name="menu_ids[]"]').forEach(function(checkbox) {
                if(checkbox.checked) {
                    checkbox.closest('label').style.borderColor = '#1a1a1a';
                }
            });
        });
    </script>

    {{-- 画像拡大モーダル --}}
    @if($store->images && $store->images->count() > 0)
    <div id="storeImageModal" style="
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        z-index: 10000;
        align-items: center;
        justify-content: center;
    ">
        <button onclick="closeStoreImageModal()" style="
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 32px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 10001;
        ">&times;</button>
        <button onclick="prevStoreImage()" style="
            position: absolute;
            left: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 24px;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            z-index: 10001;
        ">&#8249;</button>
        <button onclick="nextStoreImage()" style="
            position: absolute;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 24px;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            z-index: 10001;
        ">&#8250;</button>
        <img id="storeModalImage" src="" alt="拡大画像" style="
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        ">
    </div>

    <script>
        let currentStoreImageIndex = 0;
        const storeImages = [
            @foreach($store->images as $image)
                '{{ asset('storage/' . $image->path) }}',
            @endforeach
        ];

        function openStoreImageModal(index) {
            currentStoreImageIndex = index;
            document.getElementById('storeImageModal').style.display = 'flex';
            updateStoreModalImage();
        }

        function closeStoreImageModal() {
            document.getElementById('storeImageModal').style.display = 'none';
        }

        function prevStoreImage() {
            currentStoreImageIndex = (currentStoreImageIndex - 1 + storeImages.length) % storeImages.length;
            updateStoreModalImage();
        }

        function nextStoreImage() {
            currentStoreImageIndex = (currentStoreImageIndex + 1) % storeImages.length;
            updateStoreModalImage();
        }

        function updateStoreModalImage() {
            document.getElementById('storeModalImage').src = storeImages[currentStoreImageIndex];
        }

        // ESCキーで閉じる
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeStoreImageModal();
            } else if (e.key === 'ArrowLeft') {
                prevStoreImage();
            } else if (e.key === 'ArrowRight') {
                nextStoreImage();
            }
        });
    </script>
    @endif
    </div>
@endsection

