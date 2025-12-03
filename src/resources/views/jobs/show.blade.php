@extends('layouts.app')

@section('title', $job->title . ' | Bellbi')

@section('content')
    <article class="job-detail-card">
        <header class="job-detail-header">
            <p class="page-label">求人詳細</p>
            <h2 class="job-detail-title">{{ $job->title }}</h2>
            <p class="job-detail-salon">
                @if ($job->company)
                    {{ $job->company->name }}
                @endif
                @if ($job->store)
                    <span class="job-card-separator">/</span>{{ $job->store->name }}
                @endif
            </p>
        </header>

        <div class="job-detail-meta">
            @if ($job->prefecture_code || $job->work_location)
                <p>
                    <span class="meta-label">勤務地</span>
                    <span class="meta-value">
                        @if($job->prefecture_code)
                            {{ \App\Services\LocationService::getPrefectureName($job->prefecture_code) }}
                            @if($job->city){{ $job->city }}@endif
                        @else
                            {{ $job->work_location }}
                        @endif
                    </span>
                </p>
            @endif

            @if (!is_null($job->min_salary) || !is_null($job->max_salary))
                <p>
                    <span class="meta-label">給与</span>
                    <span class="meta-value">
                        @if (!is_null($job->min_salary))
                            {{ number_format($job->min_salary) }}円
                        @endif
                        〜
                        @if (!is_null($job->max_salary))
                            {{ number_format($job->max_salary) }}円
                        @endif
                    </span>
                </p>
            @endif
        </div>

        <section class="job-description">
            <h3>お仕事内容</h3>
            <p>{!! nl2br(e($job->description)) !!}</p>
        </section>
    </article>

    <section class="job-apply">
        <h3 class="job-apply-title">この求人に応募する</h3>

        @guest
            <p class="job-apply-note">応募するにはログインが必要です。</p>
            <p><a href="{{ route('login') }}" class="btn-secondary">ログインページへ</a></p>
        @else
            <form method="post" action="{{ route('jobs.apply', $job) }}" class="job-apply-form">
                @csrf
                <div class="form-group">
                    <label for="message">応募メッセージ（任意）</label>
                    <textarea id="message" name="message" rows="5">{{ old('message') }}</textarea>
                    @error('message')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">応募する</button>
                </div>
            </form>
        @endguest
    </section>
@endsection


