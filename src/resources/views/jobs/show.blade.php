@extends('layouts.app')

@section('title', $job->title . ' | Bellbi')

@section('content')
    <article class="job-detail-card">
        <header class="job-detail-header">
            <p class="page-label">求人詳細</p>
            <h2 class="job-detail-title">{{ $job->title }}</h2>
            <p class="job-detail-salon">
                @if ($job->company)
                    {{ $job->company->name }}
                @endif
                @if ($job->store)
                    <span class="job-card-separator">/</span>{{ $job->store->name }}
                @endif
            </p>
        </header>

        @php
            // 画像を統合：imagesの最初の1枚をメイン画像として使用
            $allImages = collect();
            if ($job->images && $job->images->count() > 0) {
                $allImages = $job->images;
            } elseif ($job->thumbnail_image) {
                // フォールバック：thumbnail_imageがある場合はそれを使用
                $allImages = collect([(object)['path' => $job->thumbnail_image, 'is_template' => strpos($job->thumbnail_image, 'templates/') === 0]]);
            }
            $mainImage = $allImages->first();
            $thumbnailImages = $allImages->slice(1);
        @endphp

        @if($mainImage)
        <section class="job-images-gallery" style="margin-top: 24px; margin-bottom: 24px;">
            {{-- メイン画像（最初の1枚を大きく表示） --}}
            <div style="margin-bottom: 12px;">
                <img src="{{ $mainImage->is_template ?? false ? asset('images/' . $mainImage->path) : asset('storage/' . $mainImage->path) }}" 
                     alt="求人画像" 
                     id="mainJobImage"
                     class="gallery-image"
                     data-image-index="0"
                     style="width: 100%; max-height: 500px; object-fit: contain; border-radius: 12px; cursor: pointer; background: #f9fafb;"
                     onclick="openImageModal(0)">
            </div>
            
            {{-- サムネイル画像（2枚目以降を横並び） --}}
            @if($thumbnailImages->count() > 0)
            <div class="thumbnail-images" style="
                display: flex;
                gap: 8px;
                overflow-x: auto;
                padding: 8px 0;
            ">
                @foreach($thumbnailImages as $index => $image)
                    <img src="{{ $image->is_template ?? false ? asset('images/' . $image->path) : asset('storage/' . $image->path) }}" 
                         alt="求人画像 {{ $index + 2 }}" 
                         class="thumbnail-image"
                         data-image-index="{{ $index + 1 }}"
                         style="
                             width: 120px;
                             height: 120px;
                             object-fit: cover;
                             border-radius: 8px;
                             cursor: pointer;
                             border: 3px solid transparent;
                             transition: all 0.2s;
                             flex-shrink: 0;
                         "
                         onmouseover="this.style.borderColor='#90AFC5'; this.style.transform='scale(1.05)'"
                         onmouseout="this.style.borderColor='transparent'; this.style.transform='scale(1)'"
                         onclick="changeMainImage({{ $index + 1 }}, '{{ $image->is_template ?? false ? asset('images/' . $image->path) : asset('storage/' . $image->path) }}')">
                @endforeach
            </div>
            @endif
        </section>
        @endif

        <div class="job-detail-meta">
            @if ($job->prefecture_code || $job->work_location)
                <p>
                    <span class="meta-label">勤務地</span>
                    <span class="meta-value">
                        @if($job->prefecture_code)
                            @php
                                $prefecture = \App\Enums\Todofuken::tryFrom($job->prefecture_code);
                            @endphp
                            @if($prefecture)
                                {{ $prefecture->label() }}
                            @else
                                {{ $job->prefecture_code }}
                            @endif
                            @if($job->city){{ $job->city }}@endif
                        @else
                            {{ $job->work_location }}
                        @endif
                    </span>
                </p>
            @endif

            @if (!is_null($job->min_salary) || !is_null($job->max_salary))
                <p>
                    <span class="meta-label">給与</span>
                    <span class="meta-value">
                        @if (!is_null($job->min_salary))
                            {{ number_format($job->min_salary) }}円
                        @endif
                        〜
                        @if (!is_null($job->max_salary))
                            {{ number_format($job->max_salary) }}円
                        @endif
                    </span>
                </p>
            @endif
        </div>

        <section class="job-description">
            <h3>お仕事内容</h3>
            <p>{!! nl2br(e($job->description)) !!}</p>
        </section>
    </article>

    {{-- 画像拡大モーダル --}}
    @if($allImages && $allImages->count() > 0)
    <div id="imageModal" style="
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
        <button onclick="closeImageModal()" style="
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
        <button onclick="prevImage()" style="
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
        <button onclick="nextImage()" style="
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
        <img id="modalImage" src="" alt="拡大画像" style="
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        ">
    </div>

    <script>
        let currentImageIndex = 0;
        const images = [
            @foreach($allImages as $image)
                '{{ ($image->is_template ?? false) ? asset('images/' . $image->path) : asset('storage/' . $image->path) }}',
            @endforeach
        ];

        function changeMainImage(index, imageSrc) {
            document.getElementById('mainJobImage').src = imageSrc;
            document.getElementById('mainJobImage').dataset.imageIndex = index;
            currentImageIndex = index;
            
            // サムネイルの選択状態を更新
            document.querySelectorAll('.thumbnail-image').forEach(thumb => {
                thumb.style.borderColor = 'transparent';
            });
            event.target.style.borderColor = '#90AFC5';
        }

        function openImageModal(index) {
            currentImageIndex = index;
            document.getElementById('imageModal').style.display = 'flex';
            updateModalImage();
        }

        function closeImageModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        function prevImage() {
            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            updateModalImage();
        }

        function nextImage() {
            currentImageIndex = (currentImageIndex + 1) % images.length;
            updateModalImage();
        }

        function updateModalImage() {
            document.getElementById('modalImage').src = images[currentImageIndex];
        }

        // ESCキーで閉じる
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            } else if (e.key === 'ArrowLeft') {
                prevImage();
            } else if (e.key === 'ArrowRight') {
                nextImage();
            }
        });
    </script>
    @endif

    <section class="job-apply">
        @if($isExpired)
            {{-- 公開期間終了のメッセージ --}}
            <div style="
                padding: 24px;
                background: #e0f2fe;
                border: 2px solid #90AFC5;
                border-radius: 12px;
                text-align: center;
                margin-bottom: 24px;
            ">
                <p style="
                    margin: 0;
                    color: #0c4a6e;
                    font-size: 16px;
                    font-weight: 700;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">求人掲載期間は終了しました</p>
                <p style="
                    margin: 8px 0 0 0;
                    color: #075985;
                    font-size: 13px;
                ">この求人への応募は受け付けておりません。</p>
            </div>
        @else
            <h3 class="job-apply-title">この求人に応募する</h3>

            @if(session('status'))
            <div style="
                padding: 12px 16px;
                background: #d1fae5;
                color: #065f46;
                border-radius: 8px;
                margin-bottom: 16px;
                font-size: 14px;
            ">{{ session('status') }}</div>
        @endif

        @if(session('error'))
            <div style="
                padding: 12px 16px;
                background: #fee2e2;
                color: #991b1b;
                border-radius: 8px;
                margin-bottom: 16px;
                font-size: 14px;
            ">{{ session('error') }}</div>
        @endif

        @guest
            <p class="job-apply-note">応募するにはログインが必要です。</p>
            <p><a href="{{ route('login') }}" style="
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
                ログインページへ
            </a></p>
        @else
            @if($hasApplied)
                <div style="
                    padding: 16px;
                    background: #f3f4f6;
                    border: 1px solid #d1d5db;
                    border-radius: 8px;
                    text-align: center;
                ">
                    <p style="
                        margin: 0;
                        color: #374151;
                        font-size: 14px;
                        font-weight: 500;
                    ">この求人には既に応募済みです。</p>
                    <p style="
                        margin: 8px 0 0 0;
                        color: #6b7280;
                        font-size: 12px;
                    "><a href="{{ route('mypage') }}" style="color: #5D535E; text-decoration: underline;">応募履歴を確認する</a></p>
                </div>
            @else
                <form method="post" action="{{ route('jobs.apply', $job) }}" class="job-apply-form">
                    @csrf
                    <div class="form-group">
                        <label for="message">応募メッセージ（任意）</label>
                        <textarea id="message" name="message" rows="5">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-actions">
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
                            応募する
                        </button>
                    </div>
                </form>
            @endif
        @endguest
        @endif
    </section>
@endsection


