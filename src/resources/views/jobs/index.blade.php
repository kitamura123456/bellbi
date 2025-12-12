@extends('layouts.app')

@section('title', '求人一覧 | Bellbi')

@section('sidebar')
    <div class="sidebar-card">
        <h3 class="sidebar-title">条件でさがす</h3>
        <form class="search-form">
            <div class="form-group">
                <label for="keyword">キーワード</label>
                <input type="text" id="keyword" name="keyword" placeholder="エリア・サロン名・職種など">
            </div>
            <div class="form-group">
                <label for="area">エリア</label>
                <select id="area" name="area">
                    <option value="">指定なし</option>
                    <option>東京</option>
                    <option>神奈川</option>
                    <option>大阪</option>
                </select>
            </div>
            <div class="form-group">
                <label for="employment_type">雇用形態</label>
                <select id="employment_type" name="employment_type">
                    <option value="">指定なし</option>
                    <option value="1">正社員</option>
                    <option value="2">パート・アルバイト</option>
                    <option value="3">業務委託</option>
                </select>
            </div>
            <button type="submit" class="btn-primary btn-block">この条件で検索</button>
        </form>
    </div>
@endsection

@section('content')
    <header class="page-header">
        <p class="page-label">Beauty / Medical / Dental</p>
        <h2 class="page-title">美容・医療・歯科の求人一覧</h2>
        <p class="page-lead">
            サロンで働きたい美容師さん、クリニックで働きたい看護師さんに向けた、
            働きやすい職場を集めました。
        </p>
    </header>

    @if ($jobs->isEmpty())
        <p class="empty-message">現在公開中の求人はありません。</p>
    @else
        <div class="job-grid">
            @foreach ($jobs as $job)
                <article class="job-card">
                    <div class="job-card-body">
                        <p class="job-card-tag">募集</p>
                        <h3 class="job-card-title">
                            <a href="{{ route('jobs.show', $job) }}">
                                {{ $job->title }}
                            </a>
                        </h3>
                        <p class="job-card-salon">
                            @if ($job->company)
                                {{ $job->company->name }}
                            @endif
                            @if ($job->store)
                                <span class="job-card-separator">/</span>{{ $job->store->name }}
                            @endif
                        </p>
                        @if ($job->work_location)
                            <p class="job-card-location">勤務地: {{ $job->work_location }}</p>
                        @endif
                        @if (!is_null($job->min_salary) || !is_null($job->max_salary))
                            <p class="job-card-salary">
                                @if (!is_null($job->min_salary))
                                    {{ number_format($job->min_salary) }}円
                                @endif
                                〜
                                @if (!is_null($job->max_salary))
                                    {{ number_format($job->max_salary) }}円
                                @endif
                            </p>
                        @endif
                    </div>
                    <div class="job-card-footer">
                        <a href="{{ route('jobs.show', $job) }}" class="btn-secondary">詳細を見る</a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="pagination-wrapper">
            {{ $jobs->links() }}
        </div>
    @endif
@endsection


