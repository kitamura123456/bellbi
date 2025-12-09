@extends('layouts.company')

@section('title', 'スタッフ追加')

@section('content')
<div class="company-header">
    <h1 class="company-title">スタッフ追加</h1>
    <a href="{{ route('company.staffs.index') }}" class="btn-secondary">一覧に戻る</a>
</div>

<div class="company-card">
    <form action="{{ route('company.staffs.store') }}" method="POST" enctype="multipart/form-data" class="company-form">
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
            <label for="name">スタッフ名 <span class="required">必須</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="image_path">プロフィール写真</label>
            <input type="file" id="image_path" name="image_path" accept="image/jpeg,image/png,image/jpg">
            <small>JPEG, PNG形式、最大2MBまで</small>
            @error('image_path')
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
            <a href="{{ route('company.staffs.index') }}" class="btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection

