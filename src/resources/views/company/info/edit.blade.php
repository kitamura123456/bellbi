@extends('layouts.company')

@section('title', '会社情報編集')

@section('content')
<div class="company-header">
    <h1 class="company-title">会社情報編集</h1>
    <a href="{{ route('company.info') }}" class="btn-secondary">戻る</a>
</div>

<div class="company-card">
    <form action="{{ route('company.info.update') }}" method="POST" class="company-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">会社名 <span class="required">必須</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $company->name) }}" required>
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="contact_name">担当者名</label>
            <input type="text" id="contact_name" name="contact_name" value="{{ old('contact_name', $company->contact_name) }}" placeholder="例：山田太郎">
            @error('contact_name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="industry_type">業種（大分類） <span class="required">必須</span></label>
            <select id="industry_type" name="industry_type" required>
                <option value="">選択してください</option>
                <option value="1" {{ old('industry_type', $company->industry_type) == 1 ? 'selected' : '' }}>美容</option>
                <option value="2" {{ old('industry_type', $company->industry_type) == 2 ? 'selected' : '' }}>医療</option>
                <option value="3" {{ old('industry_type', $company->industry_type) == 3 ? 'selected' : '' }}>歯科</option>
            </select>
            @error('industry_type')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="business_category">業種（詳細）</label>
            <select id="business_category" name="business_category">
                @if($company->industry_type && count($categories) > 0)
                    <option value="">選択してください</option>
                    @foreach($categories as $code => $name)
                        <option value="{{ $code }}" {{ old('business_category', $company->business_category) == $code ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                @else
                    <option value="">まず業種（大分類）を選択してください</option>
                @endif
            </select>
            @error('business_category')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $company->postal_code) }}" placeholder="例: 123-4567">
            @error('postal_code')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" id="address" name="address" value="{{ old('address', $company->address) }}">
            @error('address')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="tel">電話番号</label>
            <input type="text" id="tel" name="tel" value="{{ old('tel', $company->tel) }}" placeholder="例: 03-1234-5678">
            @error('tel')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">更新する</button>
            <a href="{{ route('company.info') }}" class="btn-secondary">キャンセル</a>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#industry_type').on('change', function() {
        const industryType = $(this).val();
        const $categorySelect = $('#business_category');
        
        if (!industryType) {
            $categorySelect.html('<option value="">まず業種（大分類）を選択してください</option>');
            return;
        }
        
        $categorySelect.html('<option value="">読み込み中...</option>');
        
        $.get('/bellbi/api/business-categories/' + industryType, function(data) {
            let options = '<option value="">選択してください</option>';
            $.each(data.categories, function(code, name) {
                options += '<option value="' + code + '">' + name + '</option>';
            });
            $categorySelect.html(options);
        });
    });
});
</script>
@endsection

