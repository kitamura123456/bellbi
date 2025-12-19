@extends('layouts.admin')

@section('title', '補助金情報新規作成')

@section('content')
<div class="admin-header">
    <h1 class="admin-title">補助金情報新規作成</h1>
    <a href="{{ route('admin.subsidies.index') }}" class="btn-secondary">一覧に戻る</a>
</div>

<div class="admin-card">
    <form action="{{ route('admin.subsidies.store') }}" method="POST" class="admin-form">
        @csrf

        <div class="form-group">
            <label for="title">タイトル <span class="required">必須</span></label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="例: 小規模事業者持続化補助金">
            @error('title')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="category">カテゴリ</label>
            <select id="category" name="category">
                <option value="">選択してください</option>
                <option value="1" {{ old('category') == 1 ? 'selected' : '' }}>設備投資</option>
                <option value="2" {{ old('category') == 2 ? 'selected' : '' }}>人材確保</option>
                <option value="3" {{ old('category') == 3 ? 'selected' : '' }}>事業継続</option>
                <option value="4" {{ old('category') == 4 ? 'selected' : '' }}>その他</option>
            </select>
            @error('category')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="target_region">対象地域</label>
            <select id="target_region" name="target_region">
                <option value="">全国（未指定）</option>
                @foreach($prefectures as $pref)
                    <option value="{{ $pref->value }}" {{ old('target_region') == $pref->value ? 'selected' : '' }}>
                        {{ $pref->label() }}
                    </option>
                @endforeach
            </select>
            <small>特定の都道府県を対象とする場合は選択してください。未選択の場合は全国対象となります。</small>
            @error('target_region')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="applicable_industry_type">対象業種</label>
            <select id="applicable_industry_type" name="applicable_industry_type">
                <option value="">全業種（未指定）</option>
                @foreach($industryTypes as $code => $name)
                    <option value="{{ $code }}" {{ old('applicable_industry_type') == $code ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
            <small>特定の業種を対象とする場合は選択してください。未選択の場合は全業種対象となります。</small>
            @error('applicable_industry_type')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="application_start_at">申請開始日</label>
            <input type="date" id="application_start_at" name="application_start_at" value="{{ old('application_start_at') }}">
            <small>申請受付開始日を入力してください</small>
            @error('application_start_at')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="application_end_at">申請終了日</label>
            <input type="date" id="application_end_at" name="application_end_at" value="{{ old('application_end_at') }}">
            <small>申請受付終了日を入力してください（開始日以降の日付を入力してください）</small>
            @error('application_end_at')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="status">ステータス <span class="required">必須</span></label>
            <select id="status" name="status" required>
                <option value="">選択してください</option>
                <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>募集中</option>
                <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>締切</option>
                <option value="3" {{ old('status') == 3 ? 'selected' : '' }}>未開始</option>
            </select>
            <small>申請期間を入力した場合、ステータスは自動的に更新されます</small>
            @error('status')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="summary">概要</label>
            <textarea id="summary" name="summary" rows="8" placeholder="補助金の概要を入力してください">{{ old('summary') }}</textarea>
            <small>事業者向けに表示される補助金の概要を入力してください</small>
            @error('summary')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="detail_url">詳細URL</label>
            <input type="url" id="detail_url" name="detail_url" value="{{ old('detail_url') }}" placeholder="https://example.com/subsidy">
            <small>補助金の詳細情報が掲載されている公式サイトのURLを入力してください</small>
            @error('detail_url')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">作成する</button>
            <a href="{{ route('admin.subsidies.index') }}" class="btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection

