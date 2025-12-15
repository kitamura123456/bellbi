@extends('layouts.company')

@section('title', '求人編集')

@section('content')
<div class="company-header">
    <h1 class="company-title">求人編集</h1>
    <a href="{{ route('company.job-posts.index') }}" class="btn-secondary">一覧に戻る</a>
</div>

<div class="company-card">
    <form action="{{ route('company.job-posts.update', $jobPost) }}" method="POST" class="company-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">求人タイトル <span class="required">必須</span></label>
            <input type="text" id="title" name="title" value="{{ old('title', $jobPost->title) }}" required>
            @error('title')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="store_id">勤務店舗</label>
            <select id="store_id" name="store_id">
                <option value="">本社・複数店舗など</option>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}" {{ old('store_id', $jobPost->store_id) == $store->id ? 'selected' : '' }}>
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
                <option value="1" {{ old('job_category', $jobPost->job_category) == 1 ? 'selected' : '' }}>スタイリスト</option>
                <option value="2" {{ old('job_category', $jobPost->job_category) == 2 ? 'selected' : '' }}>アシスタント</option>
                <option value="3" {{ old('job_category', $jobPost->job_category) == 3 ? 'selected' : '' }}>エステティシャン</option>
                <option value="4" {{ old('job_category', $jobPost->job_category) == 4 ? 'selected' : '' }}>看護師</option>
                <option value="5" {{ old('job_category', $jobPost->job_category) == 5 ? 'selected' : '' }}>歯科衛生士</option>
                <option value="99" {{ old('job_category', $jobPost->job_category) == 99 ? 'selected' : '' }}>その他</option>
            </select>
            @error('job_category')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="employment_type">雇用形態 <span class="required">必須</span></label>
            <select id="employment_type" name="employment_type" required>
                <option value="1" {{ old('employment_type', $jobPost->employment_type) == 1 ? 'selected' : '' }}>正社員</option>
                <option value="2" {{ old('employment_type', $jobPost->employment_type) == 2 ? 'selected' : '' }}>パート・アルバイト</option>
                <option value="3" {{ old('employment_type', $jobPost->employment_type) == 3 ? 'selected' : '' }}>業務委託</option>
                <option value="4" {{ old('employment_type', $jobPost->employment_type) == 4 ? 'selected' : '' }}>契約社員</option>
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
                    <option value="{{ $code }}" {{ old('prefecture_code', $jobPost->prefecture_code) == $code ? 'selected' : '' }}>
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
            <input type="text" id="city" name="city" value="{{ old('city', $jobPost->city) }}" placeholder="例：渋谷区、品川区、○○市">
            <small>市区町村名を自由に入力してください</small>
            @error('city')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="min_salary">給与（下限）</label>
            <input type="number" id="min_salary" name="min_salary" value="{{ old('min_salary', $jobPost->min_salary) }}" min="0">
            @error('min_salary')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="max_salary">給与（上限）</label>
            <input type="number" id="max_salary" name="max_salary" value="{{ old('max_salary', $jobPost->max_salary) }}" min="0">
            @error('max_salary')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">仕事内容・詳細 <span class="required">必須</span></label>
            <textarea id="description" name="description" required>{{ old('description', $jobPost->description) }}</textarea>
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
                            <img src="{{ asset('images/templates/stores/store1.svg') }}" style="width: 100%; border: 2px solid transparent; border-radius: 8px; transition: border-color 0.2s;" onclick="this.style.borderColor='#ec4899'">
                        </label>
                        <label style="cursor: pointer;">
                            <input type="radio" name="template_image" value="templates/stores/store2.svg" style="display: none;">
                            <img src="{{ asset('images/templates/stores/store2.svg') }}" style="width: 100%; border: 2px solid transparent; border-radius: 8px; transition: border-color 0.2s;" onclick="this.style.borderColor='#ec4899'">
                        </label>
                        <label style="cursor: pointer;">
                            <input type="radio" name="template_image" value="templates/stores/store3.svg" style="display: none;">
                            <img src="{{ asset('images/templates/stores/store3.svg') }}" style="width: 100%; border: 2px solid transparent; border-radius: 8px; transition: border-color 0.2s;" onclick="this.style.borderColor='#ec4899'">
                        </label>
                        <label style="cursor: pointer;">
                            <input type="radio" name="template_image" value="templates/stores/store4.svg" style="display: none;">
                            <img src="{{ asset('images/templates/stores/store4.svg') }}" style="width: 100%; border: 2px solid transparent; border-radius: 8px; transition: border-color 0.2s;" onclick="this.style.borderColor='#ec4899'">
                        </label>
                    </div>
                </div>
            </div>

            @error('thumbnail_image')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="status">ステータス <span class="required">必須</span></label>
            <select id="status" name="status" required>
                <option value="0" {{ old('status', $jobPost->status) == 0 ? 'selected' : '' }}>下書き（非公開）</option>
                <option value="1" {{ old('status', $jobPost->status) == 1 ? 'selected' : '' }}>公開</option>
                <option value="2" {{ old('status', $jobPost->status) == 2 ? 'selected' : '' }}>停止</option>
            </select>
            @error('status')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">更新する</button>
            <a href="{{ route('company.job-posts.index') }}" class="btn-secondary">キャンセル</a>
        </div>
    </form>
</div>

@endsection

