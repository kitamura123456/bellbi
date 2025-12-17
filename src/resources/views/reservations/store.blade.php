@extends('layouts.app')

@section('title', $store->name . ' - 予約 | Bellbi')

@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $store->name }}</h1>
        <p class="page-lead">{{ $store->company->name }}</p>
    </div>

    @if($store->thumbnail_image)
        @if(strpos($store->thumbnail_image, 'templates/') === 0)
        <div class="job-detail-card">
            <img src="{{ asset('images/' . $store->thumbnail_image) }}" alt="{{ $store->name }}" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 12px;">
        </div>
        @elseif(file_exists(public_path('storage/' . $store->thumbnail_image)))
        <div class="job-detail-card">
            <img src="{{ asset('storage/' . $store->thumbnail_image) }}" alt="{{ $store->name }}" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 12px;">
        </div>
        @else
        <div class="job-detail-card">
            <div style="width: 100%; height: 300px; background: #f3f4f6; border: 2px dashed #d1d5db; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 18px;">
                No Image
            </div>
        </div>
        @endif
    @endif

    @if($store->images && $store->images->count() > 0)
    <div class="job-detail-card" style="margin-top: 24px;">
        <h3 style="margin-top: 0; margin-bottom: 16px; font-size: 18px; font-weight: 700; color: #5D535E;">画像ギャラリー</h3>
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
                         style="max-width: 100%; max-height: 500px; object-fit: contain; border-radius: 12px; cursor: pointer;"
                         onclick="openStoreImageModal(0)">
                @endforeach
            </div>
        @else
            {{-- 複数枚の場合はグリッドレイアウト --}}
            <div class="image-grid" style="
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 12px;
            ">
                @foreach($store->images as $index => $image)
                    <img src="{{ asset('storage/' . $image->path) }}" 
                         alt="店舗画像 {{ $index + 1 }}" 
                         class="gallery-image"
                         data-image-index="{{ $index }}"
                         style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; cursor: pointer; transition: transform 0.2s;"
                         onmouseover="this.style.transform='scale(1.02)'"
                         onmouseout="this.style.transform='scale(1)'"
                         onclick="openStoreImageModal({{ $index }})">
                @endforeach
            </div>
        @endif
    </div>
    @endif

    @if($store->description)
    <div class="job-detail-card">
        <h3 style="margin-top: 0;">店舗について</h3>
        <p style="white-space: pre-wrap; line-height: 1.8;">{{ $store->description }}</p>
    </div>
    @endif

    <div class="job-detail-card">
        <h3 style="margin-top: 0;">店舗情報</h3>
        <table class="company-table">
            @if($store->address)
            <tr>
                <th style="width: 120px;">住所</th>
                <td>{{ $store->address }}</td>
            </tr>
            @endif
            @if($store->tel)
            <tr>
                <th>電話番号</th>
                <td>{{ $store->tel }}</td>
            </tr>
            @endif
            @if($store->cancel_deadline_hours)
            <tr>
                <th>キャンセル</th>
                <td>予約の{{ $store->cancel_deadline_hours }}時間前までキャンセル可能</td>
            </tr>
            @endif
        </table>
    </div>

    <form action="{{ route('reservations.booking', $store) }}" method="POST" id="reservation-form">
        @csrf

        <div class="job-detail-card">
            <h3 style="margin-top: 0;">メニュー選択 <span class="required">必須</span></h3>
            <p style="font-size: 13px; color: #6b7280; margin-bottom: 16px;">ご希望のメニューを選択してください（複数選択可）</p>
            
            @if($menus->isEmpty())
                <p class="empty-message">メニューが登録されていません。</p>
            @else
                @foreach($menus as $menu)
                <label style="display: flex; gap: 12px; padding: 12px; background-color: #f9fafb; border-radius: 8px; margin-bottom: 12px; cursor: pointer; transition: all 0.2s; border: 2px solid transparent;" onmouseover="this.style.borderColor='#90AFC5'" onmouseout="this.style.borderColor='transparent'">
                    <input type="checkbox" name="menu_ids[]" value="{{ $menu->id }}" style="margin-top: 4px;">
                    <div style="flex-shrink: 0;">
                        @if($menu->thumbnail_image)
                            @if(strpos($menu->thumbnail_image, 'templates/') === 0)
                                <img src="{{ asset('images/' . $menu->thumbnail_image) }}" alt="{{ $menu->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                            @elseif(file_exists(public_path('storage/' . $menu->thumbnail_image)))
                                <img src="{{ asset('storage/' . $menu->thumbnail_image) }}" alt="{{ $menu->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                            @else
                                <div style="width: 80px; height: 80px; background: #f3f4f6; border: 1px dashed #d1d5db; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 11px;">
                                    No Image
                                </div>
                            @endif
                        @else
                            <div style="width: 80px; height: 80px; background: #f3f4f6; border: 1px dashed #d1d5db; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 11px;">
                                No Image
                            </div>
                        @endif
                    </div>
                    <div style="flex: 1;">
                        <div>
                            <strong style="font-size: 15px;">{{ $menu->name }}</strong>
                            <span style="color: #5D535E; margin-left: 12px; font-weight: 600;">{{ number_format($menu->price) }}円</span>
                            <span style="color: #6b7280; margin-left: 8px; font-size: 14px;">（{{ $menu->duration_minutes }}分）</span>
                        </div>
                        @if($menu->description)
                            <div style="font-size: 13px; color: #6b7280; margin-top: 6px; line-height: 1.6;">{{ $menu->description }}</div>
                        @endif
                    </div>
                </label>
                @endforeach
            @endif

            @error('menu_ids')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        @if($staffs->isNotEmpty())
        <div class="job-detail-card">
            <h3 style="margin-top: 0;">スタッフ指名（任意）</h3>
            <p style="font-size: 13px; color: #6b7280; margin-bottom: 16px;">ご希望のスタッフを選択してください</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px;">
                <label style="display: flex; flex-direction: column; align-items: center; padding: 12px; background-color: #f9fafb; border-radius: 8px; cursor: pointer; transition: all 0.2s; border: 2px solid transparent;" class="staff-option">
                    <input type="radio" name="staff_id" value="" checked style="display: none;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #e8e8e8 0%, #d1d5db 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #5D535E; font-size: 12px; margin-bottom: 8px;">
                        指名なし
                    </div>
                    <span style="font-size: 14px; font-weight: 500; text-align: center;">指名なし</span>
                </label>
                @foreach($staffs as $staff)
                    <label style="display: flex; flex-direction: column; align-items: center; padding: 12px; background-color: #f9fafb; border-radius: 8px; cursor: pointer; transition: all 0.2s; border: 2px solid transparent;" class="staff-option">
                        <input type="radio" name="staff_id" value="{{ $staff->id }}" style="display: none;">
                        @if($staff->image_path)
                            <img src="{{ asset('storage/' . $staff->image_path) }}" alt="{{ $staff->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; margin-bottom: 8px;">
                        @else
                            <div style="width: 80px; height: 80px; background: #f3f4f6; border: 2px dashed #d1d5db; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 11px; margin-bottom: 8px;">
                                No Image
                            </div>
                        @endif
                        <span style="font-size: 14px; font-weight: 500; text-align: center;">{{ $staff->name }}</span>
                    </label>
                @endforeach
            </div>
            
            <style>
            .staff-option:hover {
                border-color: #90AFC5 !important;
                background-color: #f0f4f8 !important;
            }
            .staff-option input:checked + div,
            .staff-option input:checked ~ img,
            .staff-option:has(input:checked) {
                border-color: #90AFC5 !important;
                background-color: #f0f4f8 !important;
            }
            </style>
            
            @error('staff_id')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        @endif

        <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
            <button type="submit" style="
                padding: 12px 32px;
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
                日時を選択する
            </button>
            <a href="{{ route('reservations.search') }}" style="
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
                戻る
            </a>
        </div>
    </form>

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
@endsection

