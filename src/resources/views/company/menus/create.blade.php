@extends('layouts.company')

@section('title', 'メニュー追加')

@section('content')
<div class="company-header">
    <h1 class="company-title">メニュー追加</h1>
    <a href="{{ route('company.menus.index') }}" class="btn-secondary">一覧に戻る</a>
</div>

<div class="company-card">
    <form action="{{ route('company.menus.store') }}" method="POST" class="company-form" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="store_id">店舗 <span class="required">必須</span></label>
            <select id="store_id" name="store_id" required>
                <option value="">選択してください</option>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>
            @error('store_id')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="name">メニュー名 <span class="required">必須</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="例：カット">
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">説明</label>
            <textarea id="description" name="description" placeholder="メニューの詳細説明">{{ old('description') }}</textarea>
            @error('description')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="thumbnail_image">サムネイル画像</label>
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: normal;">
                    <input type="radio" name="image_source" value="upload" checked> 画像をアップロード
                </label>
                <div id="upload-section-menu" style="margin-left: 24px;">
                    <input type="file" id="thumbnail_image" name="thumbnail_image" accept="image/jpeg,image/png,image/jpg">
                    <small>JPEG, PNG形式、最大2MBまで</small>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: normal;">
                    <input type="radio" name="image_source" value="template"> テンプレート画像を選択
                </label>
                <div id="template-section-menu" style="margin-left: 24px; display: none;">
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
                        <label style="cursor: pointer;">
                            <input type="radio" name="template_image" value="templates/menus/menu1.svg" style="display: none;">
                            <img src="{{ asset('images/templates/menus/menu1.svg') }}" style="width: 100%; border: 2px solid transparent; border-radius: 8px; transition: border-color 0.2s;" onclick="this.style.borderColor='#ec4899'">
                        </label>
                        <label style="cursor: pointer;">
                            <input type="radio" name="template_image" value="templates/menus/menu2.svg" style="display: none;">
                            <img src="{{ asset('images/templates/menus/menu2.svg') }}" style="width: 100%; border: 2px solid transparent; border-radius: 8px; transition: border-color 0.2s;" onclick="this.style.borderColor='#ec4899'">
                        </label>
                        <label style="cursor: pointer;">
                            <input type="radio" name="template_image" value="templates/menus/menu3.svg" style="display: none;">
                            <img src="{{ asset('images/templates/menus/menu3.svg') }}" style="width: 100%; border: 2px solid transparent; border-radius: 8px; transition: border-color 0.2s;" onclick="this.style.borderColor='#ec4899'">
                        </label>
                        <label style="cursor: pointer;">
                            <input type="radio" name="template_image" value="templates/menus/menu4.svg" style="display: none;">
                            <img src="{{ asset('images/templates/menus/menu4.svg') }}" style="width: 100%; border: 2px solid transparent; border-radius: 8px; transition: border-color 0.2s;" onclick="this.style.borderColor='#ec4899'">
                        </label>
                    </div>
                </div>
            </div>

            @error('thumbnail_image')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const imageSourceRadios = document.querySelectorAll('input[name="image_source"]');
                const uploadSection = document.getElementById('upload-section-menu');
                const templateSection = document.getElementById('template-section-menu');
                const fileInput = document.getElementById('thumbnail_image');

                imageSourceRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (this.value === 'upload') {
                            uploadSection.style.display = 'block';
                            templateSection.style.display = 'none';
                            fileInput.disabled = false;
                            document.querySelectorAll('input[name="template_image"]').forEach(t => t.checked = false);
                        } else {
                            uploadSection.style.display = 'none';
                            templateSection.style.display = 'block';
                            fileInput.disabled = true;
                            fileInput.value = '';
                        }
                    });
                });

                // Template image selection
                document.querySelectorAll('input[name="template_image"]').forEach(radio => {
                    radio.addEventListener('change', function() {
                        document.querySelectorAll('input[name="template_image"] + img').forEach(img => {
                            img.style.borderColor = 'transparent';
                        });
                        if (this.checked) {
                            this.nextElementSibling.style.borderColor = '#ec4899';
                        }
                    });
                });
            });
        </script>

        <div class="form-group">
            <label for="duration_minutes">所要時間（分） <span class="required">必須</span></label>
            <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 30) }}" required min="30" step="30">
            <small>30分単位で入力してください</small>
            @error('duration_minutes')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="price">料金（円） <span class="required">必須</span></label>
            <input type="number" id="price" name="price" value="{{ old('price', 0) }}" required min="0">
            @error('price')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="category">カテゴリ <span class="required">必須</span></label>
            <select id="category" name="category" required>
                <option value="1" {{ old('category') == 1 ? 'selected' : '' }}>カット</option>
                <option value="2" {{ old('category') == 2 ? 'selected' : '' }}>カラー</option>
                <option value="3" {{ old('category') == 3 ? 'selected' : '' }}>パーマ</option>
                <option value="4" {{ old('category') == 4 ? 'selected' : '' }}>トリートメント</option>
                <option value="5" {{ old('category') == 5 ? 'selected' : '' }}>ヘッドスパ</option>
                <option value="6" {{ old('category') == 6 ? 'selected' : '' }}>まつエク</option>
                <option value="7" {{ old('category') == 7 ? 'selected' : '' }}>ネイル</option>
                <option value="8" {{ old('category') == 8 ? 'selected' : '' }}>フェイシャル</option>
                <option value="9" {{ old('category') == 9 ? 'selected' : '' }}>ボディ</option>
                <option value="99" {{ old('category') == 99 ? 'selected' : '' }}>その他</option>
            </select>
            @error('category')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="display_order">表示順</label>
            <input type="number" id="display_order" name="display_order" value="{{ old('display_order', 0) }}" min="0">
            <small>数値が小さいほど上位に表示されます</small>
            @error('display_order')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="is_active">公開設定 <span class="required">必須</span></label>
            <select id="is_active" name="is_active" required>
                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>公開</option>
                <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>非公開</option>
            </select>
            @error('is_active')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">登録する</button>
            <a href="{{ route('company.menus.index') }}" class="btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection

