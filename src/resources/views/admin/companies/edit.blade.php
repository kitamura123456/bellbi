@extends('layouts.admin')

@section('title', '事業者編集')

@section('content')
<div class="admin-header">
    <h1 class="admin-title">事業者編集</h1>
    <a href="{{ route('admin.companies.index') }}" class="btn-secondary">一覧に戻る</a>
</div>

<div class="admin-card">
    <form action="{{ route('admin.companies.update', $company) }}" method="POST" class="admin-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">事業者名 <span class="required">必須</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $company->name) }}" required>
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="contact_name">担当者名</label>
            <input type="text" id="contact_name" name="contact_name" value="{{ old('contact_name', $company->contact_name) }}">
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
                <option value="">選択してください</option>
                @php
                    $industryType = old('industry_type', $company->industry_type);
                    $categories = \App\Services\BusinessCategoryService::getCategoriesByIndustry($industryType);
                @endphp
                @foreach($categories as $code => $name)
                    <option value="{{ $code }}" {{ old('business_category', $company->business_category) == $code ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
            <small>業種（大分類）を選択すると、該当する詳細が表示されます</small>
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

        <div class="form-group">
            <label for="plan_id">プラン</label>
            <select id="plan_id" name="plan_id">
                <option value="">未設定</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ old('plan_id', $company->plan_id) == $plan->id ? 'selected' : '' }}>
                        {{ $plan->name }} (¥{{ number_format($plan->price_monthly) }}/月)
                    </option>
                @endforeach
            </select>
            @error('plan_id')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="plan_status">プランステータス <span class="required">必須</span></label>
            <select id="plan_status" name="plan_status" required>
                <option value="1" {{ old('plan_status', $company->plan_status) == 1 ? 'selected' : '' }}>トライアル</option>
                <option value="2" {{ old('plan_status', $company->plan_status) == 2 ? 'selected' : '' }}>有効</option>
                <option value="3" {{ old('plan_status', $company->plan_status) == 3 ? 'selected' : '' }}>遅延</option>
                <option value="9" {{ old('plan_status', $company->plan_status) == 9 ? 'selected' : '' }}>解約</option>
            </select>
            @error('plan_status')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>関連ユーザー情報</label>
            <div style="padding: 12px; background-color: #f8fafc; border-radius: 6px;">
                <p style="margin: 0; font-size: 13px;">
                    <strong>ユーザー名:</strong> {{ $company->user->name }}<br>
                    <strong>メールアドレス:</strong> {{ $company->user->email }}
                </p>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">更新する</button>
            <a href="{{ route('admin.companies.index') }}" class="btn-secondary">キャンセル</a>
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
        
        $.get('/api/business-categories/' + industryType, function(data) {
            let options = '<option value="">選択してください</option>';
            $.each(data.categories, function(code, name) {
                options += '<option value="' + code + '">' + name + '</option>';
            });
            $categorySelect.html(options);
        });
    });
    
    // ページ読み込み時に業種が選択されている場合、カテゴリを読み込む
    @if(old('industry_type', $company->industry_type))
        $('#industry_type').trigger('change');
        @if(old('business_category', $company->business_category))
            setTimeout(function() {
                $('#business_category').val('{{ old('business_category', $company->business_category) }}');
            }, 100);
        @endif
    @endif
});
</script>
@endsection

