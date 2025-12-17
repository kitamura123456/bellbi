@extends('layouts.company')

@section('title', 'メニュー追加')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">メニュー追加</h1>
    <a href="{{ route('company.menus.index') }}" style="
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
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">メニュー情報</h3>
    </div>
    <form action="{{ route('company.menus.store') }}" method="POST" enctype="multipart/form-data" style="padding: 24px;">
        @csrf

        <div style="margin-bottom: 20px;">
            <label for="store_id" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                店舗 <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <select id="store_id" name="store_id" required style="
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
                @foreach($stores as $store)
                    <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>
            @error('store_id')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="name" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                メニュー名 <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="例：カット" style="
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
            @error('name')
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
            ">説明</label>
            <textarea id="description" name="description" placeholder="メニューの詳細説明" rows="4" style="
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
            " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">{{ old('description') }}</textarea>
            @error('description')
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
            ">サムネイル画像</label>
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #2A3132; font-size: 14px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                    <input type="radio" name="image_source" value="upload" checked style="margin-right: 8px;"> 画像をアップロード
                </label>
                <div id="upload-section-menu" style="margin-left: 24px;">
                    <input type="file" id="thumbnail_image" name="thumbnail_image" accept="image/jpeg,image/png,image/jpg" style="
                        width: 100%;
                        padding: 12px;
                        border: 1px solid #e8e8e8;
                        border-radius: 12px;
                        font-size: 13px;
                        color: #5D535E;
                        background: #fafafa;
                        cursor: pointer;
                    ">
                    <small style="display: block; margin-top: 6px; color: #999999; font-size: 12px;">JPEG, PNG形式、最大2MBまで</small>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #2A3132; font-size: 14px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                    <input type="radio" name="image_source" value="template" style="margin-right: 8px;"> テンプレート画像を選択
                </label>
                <div id="template-section-menu" style="margin-left: 24px; display: none;">
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
                        <label style="cursor: pointer;">
                            <input type="radio" name="template_image" value="templates/menus/menu1.svg" style="display: none;">
                            <img src="{{ asset('images/templates/menus/menu1.svg') }}" style="width: 100%; border: 2px solid transparent; border-radius: 8px; transition: border-color 0.2s;" onclick="this.style.borderColor='#90AFC5'">
                        </label>
                        <label style="cursor: pointer;">
                            <input type="radio" name="template_image" value="templates/menus/menu2.svg" style="display: none;">
                            <img src="{{ asset('images/templates/menus/menu2.svg') }}" style="width: 100%; border: 2px solid transparent; border-radius: 8px; transition: border-color 0.2s;" onclick="this.style.borderColor='#90AFC5'">
                        </label>
                        <label style="cursor: pointer;">
                            <input type="radio" name="template_image" value="templates/menus/menu3.svg" style="display: none;">
                            <img src="{{ asset('images/templates/menus/menu3.svg') }}" style="width: 100%; border: 2px solid transparent; border-radius: 8px; transition: border-color 0.2s;" onclick="this.style.borderColor='#90AFC5'">
                        </label>
                        <label style="cursor: pointer;">
                            <input type="radio" name="template_image" value="templates/menus/menu4.svg" style="display: none;">
                            <img src="{{ asset('images/templates/menus/menu4.svg') }}" style="width: 100%; border: 2px solid transparent; border-radius: 8px; transition: border-color 0.2s;" onclick="this.style.borderColor='#90AFC5'">
                        </label>
                    </div>
                </div>
            </div>

            @error('thumbnail_image')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
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
                            this.nextElementSibling.style.borderColor = '#90AFC5';
                        }
                    });
                });
            });
        </script>

        <div style="margin-bottom: 20px;">
            <label for="duration_minutes" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                所要時間（分） <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 30) }}" required min="30" step="30" style="
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
            <small style="display: block; margin-top: 6px; color: #999999; font-size: 12px;">30分単位で入力してください</small>
            @error('duration_minutes')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="price" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                料金（円） <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <input type="number" id="price" name="price" value="{{ old('price', 0) }}" required min="0" style="
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
            @error('price')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="category" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                カテゴリ <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <select id="category" name="category" required style="
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
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="display_order" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">表示順</label>
            <input type="number" id="display_order" name="display_order" value="{{ old('display_order', 0) }}" min="0" style="
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
            <small style="display: block; margin-top: 6px; color: #999999; font-size: 12px;">数値が小さいほど上位に表示されます</small>
            @error('display_order')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 24px;">
            <label for="is_active" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                公開設定 <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <select id="is_active" name="is_active" required style="
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
                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>公開</option>
                <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>非公開</option>
            </select>
            @error('is_active')
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
                登録する
            </button>
            <a href="{{ route('company.menus.index') }}" style="
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
@endsection

