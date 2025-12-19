@extends('layouts.admin')

@section('title', '求人編集')

@section('content')
<div class="admin-header">
    <h1 class="admin-title">求人編集</h1>
    <a href="{{ route('admin.job-posts.index') }}" class="btn-secondary">一覧に戻る</a>
</div>

<div class="admin-card">
    <form action="{{ route('admin.job-posts.update', $jobPost) }}" method="POST" class="admin-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="company_id">事業者 <span class="required">必須</span></label>
            <select id="company_id" name="company_id" required>
                <option value="">選択してください</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id', $jobPost->company_id) == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
            @error('company_id')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="store_id">勤務店舗</label>
            <select id="store_id" name="store_id">
                <option value="">本社・複数店舗など</option>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}" {{ old('store_id', $jobPost->store_id) == $store->id ? 'selected' : '' }}>
                        {{ $store->name }} ({{ $store->company->name }})
                    </option>
                @endforeach
            </select>
            @error('store_id')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="title">求人タイトル <span class="required">必須</span></label>
            <input type="text" id="title" name="title" value="{{ old('title', $jobPost->title) }}" required>
            @error('title')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">仕事内容・詳細 <span class="required">必須</span></label>
            <textarea id="description" name="description" required rows="6">{{ old('description', $jobPost->description) }}</textarea>
            @error('description')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="job_category">職種 <span class="required">必須</span></label>
            <select id="job_category" name="job_category" required>
                <option value="">選択してください</option>
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
                <option value="">選択してください</option>
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
                @foreach($prefectures as $pref)
                    <option value="{{ $pref->value }}" {{ old('prefecture_code', $jobPost->prefecture_code) == $pref->value ? 'selected' : '' }}>
                        {{ $pref->label() }}
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
            <label for="work_location">勤務地（詳細）</label>
            <input type="text" id="work_location" name="work_location" value="{{ old('work_location', $jobPost->work_location) }}" placeholder="例：東京都渋谷区○○1-2-3">
            @error('work_location')
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
            <label for="status">ステータス <span class="required">必須</span></label>
            <select id="status" name="status" required>
                <option value="0" {{ old('status', $jobPost->status) == 0 ? 'selected' : '' }}>下書き</option>
                <option value="1" {{ old('status', $jobPost->status) == 1 ? 'selected' : '' }}>公開中</option>
                <option value="2" {{ old('status', $jobPost->status) == 2 ? 'selected' : '' }}>停止</option>
            </select>
            @error('status')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="publish_start_at">公開開始日時</label>
            <input type="datetime-local" id="publish_start_at" name="publish_start_at" value="{{ old('publish_start_at', $jobPost->publish_start_at ? $jobPost->publish_start_at->format('Y-m-d\TH:i') : '') }}">
            <small>未設定の場合は即座に公開されます</small>
            @error('publish_start_at')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="publish_end_at">公開終了日時</label>
            <input type="datetime-local" id="publish_end_at" name="publish_end_at" value="{{ old('publish_end_at', $jobPost->publish_end_at ? $jobPost->publish_end_at->format('Y-m-d\TH:i') : '') }}">
            <small>未設定の場合は公開終了しません</small>
            @error('publish_end_at')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">更新する</button>
            <a href="{{ route('admin.job-posts.index') }}" class="btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection

