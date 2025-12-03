@extends('layouts.company')

@section('title', '店舗編集')

@section('content')
<div class="company-header">
    <h1 class="company-title">店舗編集</h1>
    <a href="{{ route('company.stores.index') }}" class="btn-secondary">一覧に戻る</a>
</div>

<div class="company-card">
    <form action="{{ route('company.stores.update', $store) }}" method="POST" class="company-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">店舗名 <span class="required">必須</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $store->name) }}" required>
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="store_type">店舗種別 <span class="required">必須</span></label>
            <select id="store_type" name="store_type" required>
                <option value="1" {{ old('store_type', $store->store_type) == 1 ? 'selected' : '' }}>美容室</option>
                <option value="2" {{ old('store_type', $store->store_type) == 2 ? 'selected' : '' }}>エステサロン</option>
                <option value="3" {{ old('store_type', $store->store_type) == 3 ? 'selected' : '' }}>医科クリニック</option>
                <option value="4" {{ old('store_type', $store->store_type) == 4 ? 'selected' : '' }}>歯科クリニック</option>
                <option value="99" {{ old('store_type', $store->store_type) == 99 ? 'selected' : '' }}>その他</option>
            </select>
            @error('store_type')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $store->postal_code) }}">
            @error('postal_code')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" id="address" name="address" value="{{ old('address', $store->address) }}">
            @error('address')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="tel">電話番号</label>
            <input type="text" id="tel" name="tel" value="{{ old('tel', $store->tel) }}">
            @error('tel')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">更新する</button>
            <a href="{{ route('company.stores.index') }}" class="btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection

