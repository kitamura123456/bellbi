@extends('layouts.company')

@section('title', 'スカウト送信')

@section('content')
<div class="company-header">
    <h1 class="company-title">スカウト送信</h1>
    <a href="{{ route('company.scouts.search') }}" class="btn-secondary">検索に戻る</a>
</div>

<div class="company-card" style="margin-bottom: 20px;">
    <h3 style="margin-top: 0;">送信先</h3>
    <p><strong>{{ $profile->user->name }}</strong> さん</p>
    <p style="font-size: 13px; color: #6b7280;">
        希望業種：
        @if($profile->industry_type === 1) 美容
        @elseif($profile->industry_type === 2) 医療
        @elseif($profile->industry_type === 3) 歯科
        @endif
        / 希望職種：
        @if($profile->desired_job_category === 1) スタイリスト
        @elseif($profile->desired_job_category === 2) アシスタント
        @elseif($profile->desired_job_category === 3) エステティシャン
        @elseif($profile->desired_job_category === 4) 看護師
        @elseif($profile->desired_job_category === 5) 歯科衛生士
        @else その他
        @endif
        @if($profile->experience_years) / 経験：{{ $profile->experience_years }}年 @endif
    </p>
</div>

<div class="company-card">
    <form action="{{ route('company.scouts.store', $profile) }}" method="POST" class="company-form">
        @csrf

        <div class="form-group">
            <label for="from_store_id">送信元店舗</label>
            <select id="from_store_id" name="from_store_id">
                <option value="">本社・会社名義</option>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}" {{ old('from_store_id') == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>
            @error('from_store_id')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="subject">件名 <span class="required">必須</span></label>
            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required placeholder="例：スタイリスト募集のご案内">
            @error('subject')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="body">メッセージ <span class="required">必須</span></label>
            <textarea id="body" name="body" required placeholder="スカウトメッセージを入力してください">{{ old('body') }}</textarea>
            @error('body')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">スカウトを送信する</button>
            <a href="{{ route('company.scouts.search') }}" class="btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection


