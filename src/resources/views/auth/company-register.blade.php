@extends('layouts.app')

@section('title', '事業者登録 | Bellbi')

@section('content')
    <div class="auth-card">
        <h2 class="auth-title">事業者登録（店舗アカウント）</h2>
        <p class="auth-lead">
            サロン・クリニック・歯科医院などの事業者アカウントを作成します。<br>
            求人の掲載や応募管理を行うためのオーナー用アカウントです。
        </p>

        <form method="post" action="{{ route('company.register.post') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="name">担当者名</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="company_name">サロン・クリニック名</label>
                <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}" required>
                @error('company_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="industry_type">業種（大分類）</label>
                <select id="industry_type" name="industry_type" required>
                    <option value="">選択してください</option>
                    @foreach($industryTypes as $code => $name)
                        <option value="{{ $code }}" {{ old('industry_type') == $code ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                @error('industry_type')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="business_category">業種（詳細）</label>
                <select id="business_category" name="business_category">
                    <option value="">まず業種（大分類）を選択してください</option>
                </select>
                @error('business_category')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">パスワード（8文字以上）</label>
                <input id="password" type="password" name="password" required>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">パスワード（確認）</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>

            <div class="form-actions">
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
                    width: 100%;
                " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                    事業者アカウントを作成
                </button>
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


