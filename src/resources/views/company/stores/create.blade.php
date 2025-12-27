@extends('layouts.company')

@section('title', '店舗追加')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">店舗追加</h1>
    <a href="{{ route('company.stores.index') }}" style="
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
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">店舗情報</h3>
    </div>
    <form action="{{ route('company.stores.store') }}" method="POST" enctype="multipart/form-data" style="padding: 24px;">
        @csrf

        <div style="margin-bottom: 20px;">
            <label for="name" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                店舗名 <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="例：渋谷店" style="
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

        <div class="form-group">
            <label for="store_type">店舗種別 <span class="required">必須</span></label>
            <select id="store_type" name="store_type" required>
                <option value="">選択してください</option>
                <option value="1" {{ old('store_type') == 1 ? 'selected' : '' }}>美容室</option>
                <option value="2" {{ old('store_type') == 2 ? 'selected' : '' }}>エステサロン</option>
                <option value="3" {{ old('store_type') == 3 ? 'selected' : '' }}>医科クリニック</option>
                <option value="4" {{ old('store_type') == 4 ? 'selected' : '' }}>歯科クリニック</option>
                <option value="99" {{ old('store_type') == 99 ? 'selected' : '' }}>その他</option>
            </select>
            @error('store_type')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">店舗説明</label>
            <textarea id="description" name="description" rows="4" placeholder="店舗の特徴やアピールポイントを入力してください">{{ old('description') }}</textarea>
            <small>お客様に向けた店舗の紹介文を入力できます</small>
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
                <div id="upload-section" style="margin-left: 24px;">
                    <input type="file" id="thumbnail_image" name="thumbnail_image" accept="image/*">
                    <small>JPEG、PNG形式、最大2MBまで</small>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: normal;">
                    <input type="radio" name="image_source" value="template"> テンプレート画像を選択
                </label>
                <div id="template-section" style="margin-left: 24px; display: none;">
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
                        <label style="cursor: pointer;">
                            <input type="radio" name="template_image" value="templates/stores/store1.svg" style="display: none;">
                            <img src="{{ asset('images/templates/stores/store1.svg') }}" style="width: 100%; border: 2px solid transparent; border-radius: 8px; transition: border-color 0.2s;" onclick="this.style.borderColor='#90AFC5'">
                        </label>
                        <label style="cursor: pointer;">
                            <input type="radio" name="template_image" value="templates/stores/store2.svg" style="display: none;">
                            <img src="{{ asset('images/templates/stores/store2.svg') }}" style="width: 100%; border: 2px solid transparent; border-radius: 8px; transition: border-color 0.2s;" onclick="this.style.borderColor='#90AFC5'">
                        </label>
                        <label style="cursor: pointer;">
                            <input type="radio" name="template_image" value="templates/stores/store3.svg" style="display: none;">
                            <img src="{{ asset('images/templates/stores/store3.svg') }}" style="width: 100%; border: 2px solid transparent; border-radius: 8px; transition: border-color 0.2s;" onclick="this.style.borderColor='#90AFC5'">
                        </label>
                        <label style="cursor: pointer;">
                            <input type="radio" name="template_image" value="templates/stores/store4.svg" style="display: none;">
                            <img src="{{ asset('images/templates/stores/store4.svg') }}" style="width: 100%; border: 2px solid transparent; border-radius: 8px; transition: border-color 0.2s;" onclick="this.style.borderColor='#90AFC5'">
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
                const uploadSection = document.getElementById('upload-section');
                const templateSection = document.getElementById('template-section');
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

        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" placeholder="例：123-4567">
            @error('postal_code')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" id="address" name="address" value="{{ old('address') }}" placeholder="例：東京都渋谷区...">
            @error('address')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="tel">電話番号</label>
            <input type="text" id="tel" name="tel" value="{{ old('tel') }}" placeholder="例：03-1234-5678">
            @error('tel')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="accepts_reservations">予約受付</label>
            <select id="accepts_reservations" name="accepts_reservations">
                <option value="0" {{ old('accepts_reservations', 0) == 0 ? 'selected' : '' }}>受付しない</option>
                <option value="1" {{ old('accepts_reservations') == 1 ? 'selected' : '' }}>受付する</option>
            </select>
            @error('accepts_reservations')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="cancel_deadline_hours">キャンセル期限（時間前まで）</label>
            <input type="number" id="cancel_deadline_hours" name="cancel_deadline_hours" value="{{ old('cancel_deadline_hours', 24) }}" min="0" placeholder="例：24">
            <small>予約のキャンセルを受け付ける期限（予約時刻の何時間前まで）</small>
            @error('cancel_deadline_hours')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="max_concurrent_reservations">同時対応可能予約数</label>
            <input type="number" id="max_concurrent_reservations" name="max_concurrent_reservations" value="{{ old('max_concurrent_reservations', 3) }}" min="1" max="20" placeholder="例：3">
            <small>同じ時間帯に同時に対応できる予約の最大数（例：スタッフ3人なら3）</small>
            @error('max_concurrent_reservations')
                <span class="error">{{ $message }}</span>
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
                追加する
            </button>
            <a href="{{ route('company.stores.index') }}" style="
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

<style>
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 13px;
    font-weight: 700;
    color: #5D535E;
    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
}

.form-group .required {
    color: #763626;
    font-size: 11px;
    font-weight: 400;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group select,
.form-group textarea {
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
}

.form-group input[type="text"]:focus,
.form-group input[type="number"]:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #90AFC5;
    background: #ffffff;
    outline: none;
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.form-group small {
    display: block;
    margin-top: 6px;
    font-size: 12px;
    color: #6b7280;
}

.form-group .error {
    display: block;
    margin-top: 6px;
    color: #763626;
    font-size: 12px;
}

.form-group input[type="file"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    font-size: 14px;
}

/* スマホ用レスポンシブデザイン */
@media (max-width: 768px) {
    div[style*="margin-bottom: 24px; display: flex"] {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 12px !important;
    }

    div[style*="margin-bottom: 24px; display: flex"] h1 {
        font-size: 20px !important;
        margin-bottom: 0 !important;
    }

    div[style*="margin-bottom: 24px; display: flex"] > a {
        width: 100%;
        text-align: center;
        font-size: 13px;
        padding: 10px 16px;
    }

    div[style*="padding: 20px 24px"] {
        padding: 16px !important;
    }

    form[style*="padding: 24px"] {
        padding: 16px !important;
    }

    div[style*="display: grid; grid-template-columns: repeat(4, 1fr)"] {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 8px !important;
    }

    div[style*="display: flex; gap: 12px; justify-content: flex-end"] {
        flex-direction: column !important;
        gap: 8px !important;
    }

    div[style*="display: flex; gap: 12px; justify-content: flex-end"] button,
    div[style*="display: flex; gap: 12px; justify-content: flex-end"] a {
        width: 100%;
        text-align: center;
        font-size: 13px;
        padding: 12px 16px;
    }

    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group select,
    .form-group textarea {
        font-size: 16px;
        padding: 10px 12px;
    }
}

@media (max-width: 480px) {
    form[style*="padding: 24px"] {
        padding: 12px !important;
    }

    div[style*="display: grid; grid-template-columns: repeat(4, 1fr)"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection

