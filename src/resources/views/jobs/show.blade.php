@extends('layouts.app')

@section('title', $job->title . ' | Bellbi')

@section('sidebar')
    {{-- サイドバーなし --}}
@endsection

@section('content')
    <style>
        /* 求人詳細ページのみ：サイドバーを非表示にしてコンテンツを中央寄せ */
        @media (min-width: 769px) {
            .job-detail-page-wrapper .main-inner {
                justify-content: center !important;
            }
            .job-detail-page-wrapper .sidebar {
                display: none !important;
            }
            .job-detail-page-wrapper .content {
                max-width: 800px !important;
                width: 100% !important;
                margin: 0 auto !important;
            }
        }
    </style>
    <script>
        // ページ読み込み時にbodyにクラスを追加
        (function() {
            document.body.classList.add('job-detail-page-wrapper');
        })();
    </script>
    <div class="job-detail-page">
    <header class="page-header" style="margin-bottom: 48px; padding-bottom: 32px; border-bottom: 1px solid #f0f0f0;">
        <p class="page-label" style="font-size: 11px; color: #999; letter-spacing: 0.15em; text-transform: uppercase; margin: 0 0 12px 0; font-weight: 500; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">求人詳細</p>
        <h2 class="job-detail-title" style="font-size: 32px; font-weight: 400; color: #1a1a1a; margin: 0 0 12px 0; letter-spacing: -0.02em; line-height: 1.3; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{{ $job->title }}</h2>
        <p class="job-detail-salon" style="font-size: 14px; color: #666; line-height: 1.7; margin: 0; font-weight: 400; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
            @if ($job->company)
                {{ $job->company->name }}
            @endif
            @if ($job->store)
                <span class="job-card-separator" style="margin: 0 6px; color: #ccc;">/</span>{{ $job->store->name }}
            @endif
        </p>
    </header>

    <article class="job-detail-card" style="background: #ffffff; border: none; padding: 0; margin-bottom: 32px;">

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
        <section class="job-images-gallery" style="margin-bottom: 32px;">
            {{-- メイン画像（最初の1枚を大きく表示） --}}
            <div style="position: relative; margin-bottom: 16px;">
                @if($allImages->count() > 1)
                <button onclick="prevMainImage()" id="prevMainBtn" style="
                    position: absolute;
                    left: 12px;
                    top: 50%;
                    transform: translateY(-50%);
                    background: rgba(26, 26, 26, 0.8);
                    border: none;
                    color: #ffffff;
                    width: 48px;
                    height: 48px;
                    font-size: 20px;
                    cursor: pointer;
                    z-index: 10;
                    transition: all 0.3s ease;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                " onmouseover="this.style.background='rgba(26, 26, 26, 1)';" onmouseout="this.style.background='rgba(26, 26, 26, 0.8)';">
                    ‹
                </button>
                <button onclick="nextMainImage()" id="nextMainBtn" style="
                    position: absolute;
                    right: 12px;
                    top: 50%;
                    transform: translateY(-50%);
                    background: rgba(26, 26, 26, 0.8);
                    border: none;
                    color: #ffffff;
                    width: 48px;
                    height: 48px;
                    font-size: 20px;
                    cursor: pointer;
                    z-index: 10;
                    transition: all 0.3s ease;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                " onmouseover="this.style.background='rgba(26, 26, 26, 1)';" onmouseout="this.style.background='rgba(26, 26, 26, 0.8)';">
                    ›
                </button>
                @endif
                <img src="{{ $mainImage->is_template ?? false ? asset('images/' . $mainImage->path) : asset('storage/' . $mainImage->path) }}" 
                     alt="求人画像" 
                     id="mainJobImage"
                     class="gallery-image"
                     data-image-index="0"
                     style="width: 100%; max-height: 500px; object-fit: contain; cursor: pointer; background: #fafafa; transition: opacity 0.3s ease;"
                     onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';"
                     onclick="openImageModalFromMain()">
            </div>
            
            {{-- サムネイル画像（すべての画像を表示） --}}
            @if($allImages->count() > 1)
            <div class="thumbnail-images" style="
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
                gap: 12px;
                padding: 0;
            ">
                @foreach($allImages as $index => $image)
                    <img src="{{ $image->is_template ?? false ? asset('images/' . $image->path) : asset('storage/' . $image->path) }}" 
                         alt="求人画像 {{ $index + 1 }}" 
                         class="thumbnail-image"
                         data-image-index="{{ $index }}"
                         data-image-src="{{ $image->is_template ?? false ? asset('images/' . $image->path) : asset('storage/' . $image->path) }}"
                         style="
                             width: 100%;
                             aspect-ratio: 1;
                             object-fit: cover;
                             cursor: pointer;
                             border: 2px solid {{ $index === 0 ? '#1a1a1a' : 'transparent' }};
                             transition: all 0.3s ease;
                             background: #fafafa;
                         "
                         onmouseover="if(!this.classList.contains('active')) this.style.borderColor='#1a1a1a'; this.style.opacity='0.9';"
                         onmouseout="if(!this.classList.contains('active')) this.style.borderColor='transparent'; this.style.opacity='1';"
                         onclick="changeMainImage({{ $index }})">
                @endforeach
            </div>
            @endif
        </section>
        @endif

        <div class="job-detail-meta" style="border-top: 1px solid #f0f0f0; border-bottom: 1px solid #f0f0f0; padding: 20px 0; margin-bottom: 32px;">
            @if ($job->prefecture_code || $job->work_location)
                <p style="margin: 0 0 12px 0; font-size: 14px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                    <span class="meta-label" style="display: inline-block; min-width: 80px; font-weight: 500; color: #666;">勤務地</span>
                    <span class="meta-value" style="color: #1a1a1a;">
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
                <p style="margin: 0; font-size: 14px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                    <span class="meta-label" style="display: inline-block; min-width: 80px; font-weight: 500; color: #666;">給与</span>
                    <span class="meta-value" style="color: #1a1a1a;">
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

        <section class="job-description" style="margin-bottom: 32px;">
            <h3 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">お仕事内容</h3>
            <p style="white-space: pre-wrap; line-height: 1.8; font-size: 14px; color: #666; margin: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{!! nl2br(e($job->description)) !!}</p>
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

        function changeMainImage(index) {
            const imageSrc = images[index];
            document.getElementById('mainJobImage').src = imageSrc;
            document.getElementById('mainJobImage').dataset.imageIndex = index;
            currentImageIndex = index;
            
            // サムネイルの選択状態を更新（すべてのサムネイルを表示したまま）
            document.querySelectorAll('.thumbnail-image').forEach(thumb => {
                thumb.classList.remove('active');
                if(parseInt(thumb.dataset.imageIndex) === index) {
                    thumb.style.borderColor = '#1a1a1a';
                    thumb.classList.add('active');
                } else {
                    thumb.style.borderColor = 'transparent';
                }
            });
        }

        function prevMainImage() {
            const newIndex = (currentImageIndex - 1 + images.length) % images.length;
            changeMainImage(newIndex);
        }

        function nextMainImage() {
            const newIndex = (currentImageIndex + 1) % images.length;
            changeMainImage(newIndex);
        }

        function openImageModal(index) {
            currentImageIndex = index;
            document.getElementById('imageModal').style.display = 'flex';
            updateModalImage();
        }

        function openImageModalFromMain() {
            const currentIndex = parseInt(document.getElementById('mainJobImage').dataset.imageIndex);
            openImageModal(currentIndex);
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

    <section class="job-apply" style="background: #ffffff; border: none; padding: 0; margin-top: 48px; padding-top: 32px; border-top: 1px solid #f0f0f0;">
        @if($isExpired)
            {{-- 公開期間終了のメッセージ --}}
            <div style="
                padding: 24px;
                background: #fafafa;
                border: 1px solid #e0e0e0;
                text-align: center;
                margin-bottom: 32px;
            ">
                <p style="
                    margin: 0;
                    color: #1a1a1a;
                    font-size: 16px;
                    font-weight: 400;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                ">求人掲載期間は終了しました</p>
                <p style="
                    margin: 8px 0 0 0;
                    color: #666;
                    font-size: 13px;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                ">この求人への応募は受け付けておりません。</p>
            </div>
        @else
            <h3 class="job-apply-title" style="margin: 0 0 24px 0; font-size: 18px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">この求人に応募する</h3>

            @if(session('status'))
            <div style="
                padding: 12px 16px;
                background: #fafafa;
                color: #1a1a1a;
                border: 1px solid #e0e0e0;
                margin-bottom: 16px;
                font-size: 14px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            ">{{ session('status') }}</div>
        @endif

        @if(session('error'))
            <div style="
                padding: 12px 16px;
                background: #fafafa;
                color: #dc2626;
                border: 1px solid #e0e0e0;
                margin-bottom: 16px;
                font-size: 14px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            ">{{ session('error') }}</div>
        @endif

        @guest
            <p class="job-apply-note" style="font-size: 14px; color: #666; margin: 0 0 16px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">応募するにはログインが必要です。</p>
            <p><a href="{{ route('login') }}" style="
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
                display: inline-block;
                letter-spacing: 0.05em;
                text-transform: uppercase;
            " onmouseover="this.style.background='#1a1a1a'; this.style.color='#ffffff';" onmouseout="this.style.background='transparent'; this.style.color='#1a1a1a';">
                ログインページへ
            </a></p>
        @else
            @if($hasApplied)
                <div style="
                    padding: 20px;
                    background: #fafafa;
                    border: 1px solid #e0e0e0;
                    text-align: center;
                ">
                    <p style="
                        margin: 0;
                        color: #1a1a1a;
                        font-size: 14px;
                        font-weight: 400;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    ">この求人には既に応募済みです。</p>
                    <p style="
                        margin: 8px 0 0 0;
                        color: #666;
                        font-size: 13px;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    "><a href="{{ route('mypage') }}" style="color: #1a1a1a; text-decoration: none; border-bottom: 1px solid #1a1a1a; transition: opacity 0.3s ease;" onmouseover="this.style.opacity='0.7';" onmouseout="this.style.opacity='1';">応募履歴を確認する</a></p>
                </div>
            @else
                <form method="post" action="{{ route('jobs.apply', $job) }}" class="job-apply-form">
                    @csrf
                    <div class="form-group" style="margin-bottom: 24px;">
                        <label for="message" style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.02em;">応募メッセージ（任意）</label>
                        <textarea id="message" name="message" rows="5" style="width: 100%; padding: 12px 16px; border: 1px solid #e0e0e0; border-radius: 0; font-size: 14px; background: #ffffff; color: #1a1a1a; transition: all 0.3s ease; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; box-sizing: border-box; resize: vertical;" onfocus="this.style.borderColor='#1a1a1a'; this.style.outline='none';" onblur="this.style.borderColor='#e0e0e0';">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="error" style="color: #dc2626; font-size: 12px; margin-top: 4px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-actions">
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
                            応募する
                        </button>
                    </div>
                </form>
            @endif
        @endguest
        @endif
    </section>
    </div>
@endsection


