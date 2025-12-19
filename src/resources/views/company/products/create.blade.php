@extends('layouts.company')

@section('title', '商品登録')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">商品登録</h1>
    <a href="{{ route('company.products.index') }}" style="
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
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">商品情報</h3>
    </div>
    <form action="{{ route('company.products.store') }}" method="POST" style="padding: 24px;">
        @csrf

        <div style="margin-bottom: 20px;">
            <label for="shop_id" style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                ショップ <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <select id="shop_id" name="shop_id" required style="
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
                @foreach($shops as $shop)
                    <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
                @endforeach
            </select>
            @error('shop_id')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="name" style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                商品名 <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="例：シャンプー 500ml" style="
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
            <label for="description" style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                商品説明
            </label>
            <textarea id="description" name="description" rows="5" placeholder="商品の説明を入力してください" style="
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
                resize: vertical;
            " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">{{ old('description') }}</textarea>
            @error('description')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="price" style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                価格（円） <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <input type="number" id="price" name="price" value="{{ old('price') }}" required min="0" placeholder="0" style="
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
            <label for="stock" style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                在庫数 <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" required min="0" placeholder="0" style="
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
            @error('stock')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="category" style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                カテゴリ
            </label>
            <select id="category" name="category" style="
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
                <option value="">選択しない</option>
                <option value="1" {{ old('category') == 1 ? 'selected' : '' }}>美容用品</option>
                <option value="2" {{ old('category') == 2 ? 'selected' : '' }}>医療用品</option>
                <option value="3" {{ old('category') == 3 ? 'selected' : '' }}>その他</option>
            </select>
            @error('category')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="status" style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
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
                <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>販売中</option>
                <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>在庫切れ</option>
                <option value="9" {{ old('status') == 9 ? 'selected' : '' }}>非公開</option>
            </select>
            @error('status')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; gap: 12px; margin-top: 32px;">
            <button type="submit" style="
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
                flex: 1;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                商品を登録
            </button>
        </div>
    </form>
</div>
@endsection

