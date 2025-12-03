@extends('layouts.company')

@section('title', '求人作成')

@section('content')
<div class="company-header">
    <h1 class="company-title">求人作成</h1>
    <a href="{{ route('company.job-posts.index') }}" class="btn-secondary">一覧に戻る</a>
</div>

<div class="company-card">
    <form action="{{ route('company.job-posts.store') }}" method="POST" class="company-form">
        @csrf

        <div class="form-group">
            <label for="title">求人タイトル <span class="required">必須</span></label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="例：スタイリスト募集（経験者優遇）">
            @error('title')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="store_id">勤務店舗</label>
            <select id="store_id" name="store_id">
                <option value="">本社・複数店舗など</option>
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
            <label for="job_category">職種 <span class="required">必須</span></label>
            <select id="job_category" name="job_category" required>
                <option value="">選択してください</option>
                <option value="1" {{ old('job_category') == 1 ? 'selected' : '' }}>スタイリスト</option>
                <option value="2" {{ old('job_category') == 2 ? 'selected' : '' }}>アシスタント</option>
                <option value="3" {{ old('job_category') == 3 ? 'selected' : '' }}>エステティシャン</option>
                <option value="4" {{ old('job_category') == 4 ? 'selected' : '' }}>看護師</option>
                <option value="5" {{ old('job_category') == 5 ? 'selected' : '' }}>歯科衛生士</option>
                <option value="99" {{ old('job_category') == 99 ? 'selected' : '' }}>その他</option>
            </select>
            @error('job_category')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="employment_type">雇用形態 <span class="required">必須</span></label>
            <select id="employment_type" name="employment_type" required>
                <option value="">選択してください</option>
                <option value="1" {{ old('employment_type') == 1 ? 'selected' : '' }}>正社員</option>
                <option value="2" {{ old('employment_type') == 2 ? 'selected' : '' }}>パート・アルバイト</option>
                <option value="3" {{ old('employment_type') == 3 ? 'selected' : '' }}>業務委託</option>
                <option value="4" {{ old('employment_type') == 4 ? 'selected' : '' }}>契約社員</option>
            </select>
            @error('employment_type')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="prefecture_code">勤務地（都道府県）</label>
            <select id="prefecture_code" name="prefecture_code">
                <option value="">選択してください</option>
                @foreach($prefectures as $code => $name)
                    <option value="{{ $code }}" {{ old('prefecture_code') == $code ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
            @error('prefecture_code')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="city">勤務地（市区町村）</label>
            <input type="text" id="city" name="city" value="{{ old('city') }}" placeholder="例：渋谷区、品川区、○○市">
            <small>市区町村名を自由に入力してください</small>
            @error('city')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="min_salary">給与（下限）</label>
            <input type="number" id="min_salary" name="min_salary" value="{{ old('min_salary') }}" placeholder="例：250000" min="0">
            <small>月給の場合は月額、時給の場合は時給額を入力</small>
            @error('min_salary')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="max_salary">給与（上限）</label>
            <input type="number" id="max_salary" name="max_salary" value="{{ old('max_salary') }}" placeholder="例：400000" min="0">
            @error('max_salary')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">仕事内容・詳細 <span class="required">必須</span></label>
            <textarea id="description" name="description" required placeholder="具体的な業務内容、求める経験・スキル、福利厚生などを記載してください">{{ old('description') }}</textarea>
            @error('description')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="status">ステータス <span class="required">必須</span></label>
            <select id="status" name="status" required>
                <option value="0" {{ old('status', 0) == 0 ? 'selected' : '' }}>下書き（非公開）</option>
                <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>公開</option>
                <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>停止</option>
            </select>
            @error('status')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">作成する</button>
            <a href="{{ route('company.job-posts.index') }}" class="btn-secondary">キャンセル</a>
        </div>
    </form>
</div>

@endsection

