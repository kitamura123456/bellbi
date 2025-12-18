@extends('layouts.admin')

@section('title', 'プラン編集')

@section('content')
<div class="admin-header">
    <h1 class="admin-title">プラン編集</h1>
    <a href="{{ route('admin.plans.index') }}" class="btn-secondary">一覧に戻る</a>
</div>

<div class="admin-card">
    <form action="{{ route('admin.plans.update', $plan) }}" method="POST" class="admin-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">プラン名 <span class="required">必須</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $plan->name) }}" required placeholder="例: ベーシックプラン">
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">プラン説明</label>
            <textarea id="description" name="description" rows="5" placeholder="プランの説明を入力してください">{{ old('description', $plan->description) }}</textarea>
            <small>事業者向けに表示されるプランの説明文を入力してください</small>
            @error('description')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="price_monthly">月額料金 <span class="required">必須</span></label>
            <input type="number" id="price_monthly" name="price_monthly" value="{{ old('price_monthly', $plan->price_monthly) }}" required min="0" placeholder="例: 5000">
            <small>円単位で入力してください（例: 5000 = ¥5,000/月）</small>
            @error('price_monthly')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="features_bitmask">機能ビットマスク</label>
            <input type="number" id="features_bitmask" name="features_bitmask" value="{{ old('features_bitmask', $plan->features_bitmask) }}" min="0" placeholder="0">
            <small>機能のON/OFFをビットフラグで管理します（数値で入力）</small>
            @error('features_bitmask')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="status">ステータス <span class="required">必須</span></label>
            <select id="status" name="status" required>
                <option value="1" {{ old('status', $plan->status) == 1 ? 'selected' : '' }}>有効</option>
                <option value="0" {{ old('status', $plan->status) == 0 ? 'selected' : '' }}>無効</option>
            </select>
            @error('status')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">更新する</button>
            <a href="{{ route('admin.plans.index') }}" class="btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection

