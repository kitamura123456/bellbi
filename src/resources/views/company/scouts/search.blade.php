@extends('layouts.company')

@section('title', 'スカウト候補者検索')

@section('content')
<div class="company-header">
    <h1 class="company-title">スカウト候補者検索</h1>
    <a href="{{ route('company.scouts.sent') }}" class="btn-secondary">送信済みスカウト</a>
</div>

<div class="company-card" style="margin-bottom: 20px;">
    <form action="{{ route('company.scouts.search') }}" method="GET" class="company-form">
        <div style="display: grid; grid-template-columns: 1fr 1fr 100px; gap: 12px; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="industry_type">業種</label>
                <select id="industry_type" name="industry_type">
                    <option value="">すべて</option>
                    <option value="1" {{ request('industry_type') == 1 ? 'selected' : '' }}>美容</option>
                    <option value="2" {{ request('industry_type') == 2 ? 'selected' : '' }}>医療</option>
                    <option value="3" {{ request('industry_type') == 3 ? 'selected' : '' }}>歯科</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="desired_job_category">職種</label>
                <select id="desired_job_category" name="desired_job_category">
                    <option value="">すべて</option>
                    <option value="1" {{ request('desired_job_category') == 1 ? 'selected' : '' }}>スタイリスト</option>
                    <option value="2" {{ request('desired_job_category') == 2 ? 'selected' : '' }}>アシスタント</option>
                    <option value="3" {{ request('desired_job_category') == 3 ? 'selected' : '' }}>エステティシャン</option>
                    <option value="4" {{ request('desired_job_category') == 4 ? 'selected' : '' }}>看護師</option>
                    <option value="5" {{ request('desired_job_category') == 5 ? 'selected' : '' }}>歯科衛生士</option>
                    <option value="99" {{ request('desired_job_category') == 99 ? 'selected' : '' }}>その他</option>
                </select>
            </div>

            <button type="submit" class="btn-primary">検索</button>
        </div>
    </form>
</div>

<div class="company-card">
    <h3 style="margin-top: 0;">候補者一覧（{{ $profiles->total() }}人）</h3>
    @forelse($profiles as $profile)
    <div class="scout-candidate-card">
        <div class="scout-candidate-body">
            <h4 class="scout-candidate-name">{{ $profile->user->name }}</h4>
            <div class="scout-candidate-info">
                <span class="badge badge-primary">
                    @if($profile->industry_type === 1) 美容
                    @elseif($profile->industry_type === 2) 医療
                    @elseif($profile->industry_type === 3) 歯科
                    @endif
                </span>
                <span class="scout-candidate-text">
                    希望職種：
                    @if($profile->desired_job_category === 1) スタイリスト
                    @elseif($profile->desired_job_category === 2) アシスタント
                    @elseif($profile->desired_job_category === 3) エステティシャン
                    @elseif($profile->desired_job_category === 4) 看護師
                    @elseif($profile->desired_job_category === 5) 歯科衛生士
                    @else その他
                    @endif
                </span>
                @if($profile->experience_years)
                <span class="scout-candidate-text">経験：{{ $profile->experience_years }}年</span>
                @endif
            </div>
        </div>
        <div class="scout-candidate-actions">
            <a href="{{ route('company.scouts.create', $profile) }}" class="btn-primary btn-sm">スカウトを送る</a>
        </div>
    </div>
    @empty
    <p class="empty-message">該当する候補者が見つかりませんでした。</p>
    @endforelse

    <div class="pagination-wrapper">
        {{ $profiles->links() }}
    </div>
</div>
@endsection


