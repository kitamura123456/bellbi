@extends('layouts.company')

@section('title', '求人編集')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">求人編集</h1>
    <a href="{{ route('company.job-posts.index') }}" style="
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
        一覧に戻る
    </a>
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">求人情報</h3>
    </div>
    <form action="{{ route('company.job-posts.update', $jobPost) }}" method="POST" enctype="multipart/form-data" style="padding: 24px;">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 20px;">
            <label for="title" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                求人タイトル <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <input type="text" id="title" name="title" value="{{ old('title', $jobPost->title) }}" required style="
                width: 100%;
                padding: 12px 16px;
                border: 1px solid #e8e8e8;
                border-radius: 12px;
                font-size: 14px;
                font-family: inherit;
                color: #2A3132;
                background: #fafafa;
                transition: all 0.2s ease;
                box-sizing: border-box;
            " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
            @error('title')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="store_id" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">勤務店舗</label>
            <select id="store_id" name="store_id" style="
                width: 100%;
                padding: 12px 16px;
                border: 1px solid #e8e8e8;
                border-radius: 12px;
                font-size: 14px;
                font-family: inherit;
                color: #2A3132;
                background: #fafafa;
                transition: all 0.2s ease;
                box-sizing: border-box;
            " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
                <option value="">本社・複数店舗など</option>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}" {{ old('store_id', $jobPost->store_id) == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>
            @error('store_id')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="job_category" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                職種 <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <select id="job_category" name="job_category" required style="
                width: 100%;
                padding: 12px 16px;
                border: 1px solid #e8e8e8;
                border-radius: 12px;
                font-size: 14px;
                font-family: inherit;
                color: #2A3132;
                background: #fafafa;
                transition: all 0.2s ease;
                box-sizing: border-box;
            " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
                <option value="1" {{ old('job_category', $jobPost->job_category) == 1 ? 'selected' : '' }}>スタイリスト</option>
                <option value="2" {{ old('job_category', $jobPost->job_category) == 2 ? 'selected' : '' }}>アシスタント</option>
                <option value="3" {{ old('job_category', $jobPost->job_category) == 3 ? 'selected' : '' }}>エステティシャン</option>
                <option value="4" {{ old('job_category', $jobPost->job_category) == 4 ? 'selected' : '' }}>看護師</option>
                <option value="5" {{ old('job_category', $jobPost->job_category) == 5 ? 'selected' : '' }}>歯科衛生士</option>
                <option value="99" {{ old('job_category', $jobPost->job_category) == 99 ? 'selected' : '' }}>その他</option>
            </select>
            @error('job_category')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="employment_type" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                雇用形態 <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <select id="employment_type" name="employment_type" required style="
                width: 100%;
                padding: 12px 16px;
                border: 1px solid #e8e8e8;
                border-radius: 12px;
                font-size: 14px;
                font-family: inherit;
                color: #2A3132;
                background: #fafafa;
                transition: all 0.2s ease;
                box-sizing: border-box;
            " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
                <option value="1" {{ old('employment_type', $jobPost->employment_type) == 1 ? 'selected' : '' }}>正社員</option>
                <option value="2" {{ old('employment_type', $jobPost->employment_type) == 2 ? 'selected' : '' }}>パート・アルバイト</option>
                <option value="3" {{ old('employment_type', $jobPost->employment_type) == 3 ? 'selected' : '' }}>業務委託</option>
                <option value="4" {{ old('employment_type', $jobPost->employment_type) == 4 ? 'selected' : '' }}>契約社員</option>
            </select>
            @error('employment_type')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="prefecture_code" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">勤務地（都道府県）</label>
            <select id="prefecture_code" name="prefecture_code" style="
                width: 100%;
                padding: 12px 16px;
                border: 1px solid #e8e8e8;
                border-radius: 12px;
                font-size: 14px;
                font-family: inherit;
                color: #2A3132;
                background: #fafafa;
                transition: all 0.2s ease;
                box-sizing: border-box;
            " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
                <option value="">選択してください</option>
                @foreach($prefectures as $pref)
                    <option value="{{ $pref }}" {{ old('prefecture_code') == $pref ? 'selected' : '' }}>
                        {{ $pref->label() }}
                    </option>
                @endforeach
            </select>
            @error('prefecture_code')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="city" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">勤務地（市区町村）</label>
            <input type="text" id="city" name="city" value="{{ old('city', $jobPost->city) }}" placeholder="例：渋谷区、品川区、○○市" style="
                width: 100%;
                padding: 12px 16px;
                border: 1px solid #e8e8e8;
                border-radius: 12px;
                font-size: 14px;
                font-family: inherit;
                color: #2A3132;
                background: #fafafa;
                transition: all 0.2s ease;
                box-sizing: border-box;
            " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
            <small style="display: block; margin-top: 6px; color: #999999; font-size: 12px;">市区町村名を自由に入力してください</small>
            @error('city')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="min_salary" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">給与（下限）</label>
            <input type="number" id="min_salary" name="min_salary" value="{{ old('min_salary', $jobPost->min_salary) }}" min="0" style="
                width: 100%;
                padding: 12px 16px;
                border: 1px solid #e8e8e8;
                border-radius: 12px;
                font-size: 14px;
                font-family: inherit;
                color: #2A3132;
                background: #fafafa;
                transition: all 0.2s ease;
                box-sizing: border-box;
            " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
            @error('min_salary')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="max_salary" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">給与（上限）</label>
            <input type="number" id="max_salary" name="max_salary" value="{{ old('max_salary', $jobPost->max_salary) }}" min="0" style="
                width: 100%;
                padding: 12px 16px;
                border: 1px solid #e8e8e8;
                border-radius: 12px;
                font-size: 14px;
                font-family: inherit;
                color: #2A3132;
                background: #fafafa;
                transition: all 0.2s ease;
                box-sizing: border-box;
            " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
            @error('max_salary')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="description" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                仕事内容・詳細 <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <textarea id="description" name="description" required rows="6" style="
                width: 100%;
                padding: 12px 16px;
                border: 1px solid #e8e8e8;
                border-radius: 12px;
                font-size: 14px;
                font-family: inherit;
                line-height: 1.5;
                color: #2A3132;
                background: #fafafa;
                transition: all 0.2s ease;
                resize: vertical;
                box-sizing: border-box;
            " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">{{ old('description', $jobPost->description) }}</textarea>
            @error('description')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">タグ</label>
            @if($tags->isNotEmpty())
            <div style="margin-bottom: 12px; display: flex; flex-wrap: wrap; gap: 8px;">
                @foreach($tags as $tag)
                    <label style="
                        display: inline-flex;
                        align-items: center;
                        padding: 6px 12px;
                        border: 1px solid #e8e8e8;
                        border-radius: 16px;
                        background: #fafafa;
                        cursor: pointer;
                        font-size: 13px;
                        color: #2A3132;
                        transition: all 0.2s ease;
                    " onmouseover="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onmouseout="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $jobPost->tags->pluck('id')->toArray())) ? 'checked' : '' }} style="margin-right: 6px;">
                        {{ $tag->name }}
                    </label>
                @endforeach
            </div>
            @endif
            <input type="text" id="new_tags" name="new_tags" value="{{ old('new_tags') }}" placeholder="新しいタグをカンマ区切りで入力（例：未経験OK, 交通費支給, 研修あり）" style="
                width: 100%;
                padding: 12px 16px;
                border: 1px solid #e8e8e8;
                border-radius: 12px;
                font-size: 14px;
                font-family: inherit;
                color: #2A3132;
                background: #fafafa;
                transition: all 0.2s ease;
                box-sizing: border-box;
            " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
            <small style="display: block; margin-top: 6px; color: #999999; font-size: 12px;">既存のタグを選択するか、新しいタグをカンマ区切りで入力してください</small>
            @error('tags')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
            @error('new_tags')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        
        <div style="margin-bottom: 20px;">
            <label style="
                display: block;
                margin-bottom: 12px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">画像（複数枚アップロード可能）</label>
            <small style="display: block; margin-bottom: 12px; color: #999999; font-size: 12px;">最初の画像がサムネイルとして使用されます。ドラッグ&ドロップで並び替えができます。</small>
            
            <input type="file" id="gallery_images" name="gallery_images[]" accept="image/*" multiple style="
                width: 100%;
                padding: 12px;
                border: 1px solid #e8e8e8;
                border-radius: 12px;
                font-size: 13px;
                color: #5D535E;
                background: #fafafa;
                cursor: pointer;
                margin-bottom: 16px;
            ">
            <small style="display: block; margin-top: 6px; color: #999999; font-size: 12px;">JPEG、PNG形式、最大2MBまで。複数選択可能です。</small>
            
            <div id="gallery-preview" style="
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                gap: 12px;
                margin-top: 16px;
            ">
                @foreach($jobPost->images as $index => $image)
                    <div class="gallery-item existing" data-image-id="{{ $image->id }}" style="
                        position: relative;
                        width: 120px;
                        height: 120px;
                        border: 2px solid {{ $index === 0 ? '#90AFC5' : '#e8e8e8' }};
                        border-radius: 8px;
                        overflow: hidden;
                        cursor: move;
                    ">
                        @if($index === 0)
                            <div style="position: absolute; top: 4px; left: 4px; background: #90AFC5; color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700; z-index: 10;">メイン</div>
                        @endif
                        <img src="{{ asset('storage/' . $image->path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        <button type="button" class="delete-existing-image" data-image-id="{{ $image->id }}" style="
                            position: absolute;
                            top: 4px;
                            right: 4px;
                            background: rgba(255, 0, 0, 0.7);
                            color: white;
                            border: none;
                            border-radius: 50%;
                            width: 24px;
                            height: 24px;
                            cursor: pointer;
                            font-size: 16px;
                            line-height: 1;
                        ">×</button>
                        <input type="hidden" name="gallery_sort_order[{{ $image->id }}]" value="{{ $image->sort_order }}">
                        <input type="checkbox" name="delete_gallery_images[]" value="{{ $image->id }}" style="display: none;" class="delete-checkbox">
                    </div>
                @endforeach
            </div>
            
            @error('gallery_images')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 24px;">
            <label for="status" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                ステータス <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <select id="status" name="status" required style="
                width: 100%;
                padding: 12px 16px;
                border: 1px solid #e8e8e8;
                border-radius: 12px;
                font-size: 14px;
                font-family: inherit;
                color: #2A3132;
                background: #fafafa;
                transition: all 0.2s ease;
                box-sizing: border-box;
            " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
                <option value="0" {{ old('status', $jobPost->status) == 0 ? 'selected' : '' }}>下書き（非公開）</option>
                <option value="1" {{ old('status', $jobPost->status) == 1 ? 'selected' : '' }}>公開</option>
                <option value="2" {{ old('status', $jobPost->status) == 2 ? 'selected' : '' }}>停止</option>
            </select>
            @error('status')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end;">
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
                更新する
            </button>
            <a href="{{ route('company.job-posts.index') }}" style="
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
                キャンセル
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const galleryInput = document.getElementById('gallery_images');
    const galleryPreview = document.getElementById('gallery-preview');
    let imageFiles = [];
    let sortOrder = {{ $jobPost->images->max('sort_order') ?? 0 }};

    // 既存画像の削除ボタン
    document.querySelectorAll('.delete-existing-image').forEach(btn => {
        btn.addEventListener('click', function() {
            const imageId = this.dataset.imageId;
            const checkbox = document.querySelector(`.delete-checkbox[value="${imageId}"]`);
            if (checkbox) {
                checkbox.checked = true;
                this.closest('.gallery-item').style.opacity = '0.5';
                this.closest('.gallery-item').style.borderColor = '#ef4444';
            }
        });
    });

    // 画像選択時の処理
    galleryInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        files.forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageData = {
                        file: file,
                        preview: e.target.result,
                        sortOrder: ++sortOrder
                    };
                    imageFiles.push(imageData);
                    renderNewImages();
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // 新規画像の描画
    function renderNewImages() {
        imageFiles.forEach((imageData, index) => {
            if (document.querySelector(`[data-new-index="${index}"]`)) return;
            
            const imageItem = document.createElement('div');
            imageItem.className = 'gallery-item new';
            imageItem.draggable = true;
            imageItem.dataset.newIndex = index;
            imageItem.style.cssText = `
                position: relative;
                width: 120px;
                height: 120px;
                border: 2px solid #e8e8e8;
                border-radius: 8px;
                overflow: hidden;
                cursor: move;
            `;
            
            const img = document.createElement('img');
            img.src = imageData.preview;
            img.style.cssText = 'width: 100%; height: 100%; object-fit: cover;';
            
            const deleteBtn = document.createElement('button');
            deleteBtn.innerHTML = '×';
            deleteBtn.type = 'button';
            deleteBtn.style.cssText = `
                position: absolute;
                top: 4px;
                right: 4px;
                background: rgba(255, 0, 0, 0.7);
                color: white;
                border: none;
                border-radius: 50%;
                width: 24px;
                height: 24px;
                cursor: pointer;
                font-size: 16px;
                line-height: 1;
            `;
            deleteBtn.onclick = function() {
                imageFiles.splice(index, 1);
                imageItem.remove();
                updateFileInput();
            };
            
            imageItem.appendChild(img);
            imageItem.appendChild(deleteBtn);
            
            // ドラッグ&ドロップ
            imageItem.addEventListener('dragstart', function(e) {
                e.dataTransfer.setData('text/plain', 'new-' + index);
                this.style.opacity = '0.5';
            });
            
            imageItem.addEventListener('dragend', function() {
                this.style.opacity = '1';
            });
            
            imageItem.addEventListener('dragover', function(e) {
                e.preventDefault();
            });
            
            imageItem.addEventListener('drop', function(e) {
                e.preventDefault();
                const data = e.dataTransfer.getData('text/plain');
                const toIndex = parseInt(this.dataset.newIndex);
                
                if (data.startsWith('new-')) {
                    const fromIndex = parseInt(data.replace('new-', ''));
                    const moved = imageFiles.splice(fromIndex, 1)[0];
                    imageFiles.splice(toIndex, 0, moved);
                    renderNewImages();
                }
            });
            
            galleryPreview.appendChild(imageItem);
        });
    }

    // 既存画像のドラッグ&ドロップ
    document.querySelectorAll('.gallery-item.existing').forEach((item, index) => {
        item.addEventListener('dragstart', function(e) {
            e.dataTransfer.setData('text/plain', 'existing-' + this.dataset.imageId);
            this.style.opacity = '0.5';
        });
        
        item.addEventListener('dragend', function() {
            this.style.opacity = '1';
            updateMainImageIndicator();
        });
        
        item.addEventListener('dragover', function(e) {
            e.preventDefault();
        });
        
        item.addEventListener('drop', function(e) {
            e.preventDefault();
            // 並び替えの処理はサーバー側で行う
            updateMainImageIndicator();
        });
    });

    // 最初の画像に「メイン」ラベルを表示
    function updateMainImageIndicator() {
        const items = document.querySelectorAll('.gallery-item.existing');
        items.forEach((item, index) => {
            // 既存のメインラベルを削除
            const existingLabel = item.querySelector('.main-label');
            if (existingLabel) {
                existingLabel.remove();
            }
            
            // ボーダーをリセット
            item.style.borderColor = '#e8e8e8';
            
            // 最初の画像にラベルとボーダーを追加
            if (index === 0) {
                item.style.borderColor = '#90AFC5';
                const label = document.createElement('div');
                label.className = 'main-label';
                label.textContent = 'メイン';
                label.style.cssText = 'position: absolute; top: 4px; left: 4px; background: #90AFC5; color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700; z-index: 10;';
                item.appendChild(label);
            }
        });
    }

    // 初期表示時にメインラベルを設定
    updateMainImageIndicator();

    // ファイル入力の更新
    function updateFileInput() {
        const dt = new DataTransfer();
        imageFiles.forEach(imageData => {
            dt.items.add(imageData.file);
        });
        galleryInput.files = dt.files;
    }
});
</script>

@endsection

