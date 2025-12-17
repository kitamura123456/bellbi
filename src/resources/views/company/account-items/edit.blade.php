@extends('layouts.company')

@section('title', '科目編集')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">科目編集</h1>
</div>

<div class="form-container">
    <form action="{{ route('company.account-items.update', $accountItem) }}" method="POST" class="standard-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="type" class="form-label">種別 <span class="required">*</span></label>
            <select name="type" id="type" class="form-control" required>
                <option value="">選択してください</option>
                <option value="1" {{ old('type', $accountItem->type) == 1 ? 'selected' : '' }}>売上</option>
                <option value="2" {{ old('type', $accountItem->type) == 2 ? 'selected' : '' }}>経費</option>
            </select>
            @error('type')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="name" class="form-label">科目名 <span class="required">*</span></label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $accountItem->name) }}" required maxlength="255">
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
            <small class="form-help">例: カット、カラー、材料費、広告費など</small>
        </div>

        <div class="form-group">
            <label for="default_tax_rate" class="form-label">デフォルト税率（%）</label>
            <input type="number" name="default_tax_rate" id="default_tax_rate" class="form-control" value="{{ old('default_tax_rate', $accountItem->default_tax_rate) }}" step="0.01" min="0" max="100">
            @error('default_tax_rate')
                <span class="error-message">{{ $message }}</span>
            @enderror
            <small class="form-help">取引登録時にこの税率がデフォルトで適用されます</small>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('company.account-items.index') }}" style="
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
        </div>
    </form>
</div>

<style>
.form-container {
    background: white;
    border-radius: 8px;
    padding: 32px;
    max-width: 600px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.standard-form {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.required {
    color: #e53e3e;
}

.form-control {
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    font-size: 14px;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-help {
    margin-top: 4px;
    font-size: 13px;
    color: #6b7280;
}

.error-message {
    color: #e53e3e;
    font-size: 13px;
    margin-top: 4px;
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 8px;
}
</style>
@endsection


