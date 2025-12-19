@extends('layouts.app')

@section('title', 'スカウト用プロフィール | Bellbi')

@section('sidebar')
    <div class="sidebar-card">
        <h3 class="sidebar-title">メニュー</h3>
        <ul class="sidebar-menu">
            <li><a href="{{ route('mypage') }}" class="sidebar-menu-link">応募履歴</a></li>
            <li><a href="{{ route('mypage.scouts.index') }}" class="sidebar-menu-link">スカウト受信</a></li>
            <li><a href="{{ route('mypage.messages.index') }}" class="sidebar-menu-link">メッセージ</a></li>
            <li><a href="{{ route('mypage.scout-profile.edit') }}" class="sidebar-menu-link active">スカウト用プロフィール</a></li>
            <li><a href="{{ route('mypage.reservations.index') }}" class="sidebar-menu-link">予約履歴</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="page-header">
        <p class="page-label">Scout Profile</p>
        <h1 class="page-title">スカウト用プロフィール</h1>
        <p class="page-lead">企業からスカウトを受けるためのプロフィール設定です。</p>
    </div>

    <div class="job-detail-card">
        <form action="{{ route('mypage.scout-profile.update') }}" method="POST" class="company-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="industry_type">希望業種 <span class="required">必須</span></label>
                <select id="industry_type" name="industry_type" required>
                    <option value="">選択してください</option>
                    <option value="1" {{ old('industry_type', $profile->industry_type) == 1 ? 'selected' : '' }}>美容</option>
                    <option value="2" {{ old('industry_type', $profile->industry_type) == 2 ? 'selected' : '' }}>医療</option>
                    <option value="3" {{ old('industry_type', $profile->industry_type) == 3 ? 'selected' : '' }}>歯科</option>
                </select>
                @error('industry_type')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="desired_job_category">希望職種 <span class="required">必須</span></label>
                <select id="desired_job_category" name="desired_job_category" required>
                    <option value="">選択してください</option>
                    <option value="1" {{ old('desired_job_category', $profile->desired_job_category) == 1 ? 'selected' : '' }}>スタイリスト</option>
                    <option value="2" {{ old('desired_job_category', $profile->desired_job_category) == 2 ? 'selected' : '' }}>アシスタント</option>
                    <option value="3" {{ old('desired_job_category', $profile->desired_job_category) == 3 ? 'selected' : '' }}>エステティシャン</option>
                    <option value="4" {{ old('desired_job_category', $profile->desired_job_category) == 4 ? 'selected' : '' }}>看護師</option>
                    <option value="5" {{ old('desired_job_category', $profile->desired_job_category) == 5 ? 'selected' : '' }}>歯科衛生士</option>
                    <option value="99" {{ old('desired_job_category', $profile->desired_job_category) == 99 ? 'selected' : '' }}>その他</option>
                </select>
                @error('desired_job_category')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="experience_years">経験年数</label>
                <input type="number" id="experience_years" name="experience_years" value="{{ old('experience_years', $profile->experience_years) }}" min="0" max="50" placeholder="例：3">
                <small>年数を入力してください</small>
                @error('experience_years')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="desired_work_style">希望勤務形態</label>
                <select id="desired_work_style" name="desired_work_style">
                    <option value="">選択してください</option>
                    <option value="1" {{ old('desired_work_style', $profile->desired_work_style) == 1 ? 'selected' : '' }}>正社員</option>
                    <option value="2" {{ old('desired_work_style', $profile->desired_work_style) == 2 ? 'selected' : '' }}>パート・アルバイト</option>
                    <option value="3" {{ old('desired_work_style', $profile->desired_work_style) == 3 ? 'selected' : '' }}>業務委託</option>
                    <option value="4" {{ old('desired_work_style', $profile->desired_work_style) == 4 ? 'selected' : '' }}>時短勤務</option>
                </select>
                @error('desired_work_style')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="is_public">公開設定 <span class="required">必須</span></label>
                <select id="is_public" name="is_public" required>
                    <option value="0" {{ old('is_public', $profile->is_public) == 0 ? 'selected' : '' }}>非公開（スカウトを受けない）</option>
                    <option value="1" {{ old('is_public', $profile->is_public) == 1 ? 'selected' : '' }}>公開（スカウトを受ける）</option>
                </select>
                <small>公開にすると、企業からスカウトメッセージが届くようになります。</small>
                @error('is_public')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" style="
                    padding: 10px 24px;
                    background: #1a1a1a;
                    color: #ffffff;
                    border: none;
                    border-radius: 4px;
                    font-size: 13px;
                    font-weight: 500;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    cursor: pointer;
                    transition: all 0.15s ease;
                " onmouseover="this.style.backgroundColor='#333333';" onmouseout="this.style.backgroundColor='#1a1a1a';">
                    保存する
                </button>
            </div>
        </form>
    </div>
@endsection


