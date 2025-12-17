@extends('layouts.app')

@section('title', $job->title . ' | Bellbi')

@section('content')
    <article class="job-detail-card">
        <header class="job-detail-header">
            <p class="page-label">求人詳細</p>
            <h2 class="job-detail-title">{{ $job->title }}</h2>
            <div style="flex-shrink: 0;">
                                @if($job->thumbnail_image)
                                    @if(strpos($job->thumbnail_image, 'templates/') === 0)
                                        <img src="{{ asset('images/' . $job->thumbnail_image) }}" alt="{{ $job->name }}" style="width: 180px; height: 180px; object-fit: cover; border-radius: 12px;">
                                    @elseif(file_exists(public_path('storage/' . $job->thumbnail_image)))
                                        <img src="{{ asset('storage/' . $job->thumbnail_image) }}" alt="{{ $job->name }}" style="width: 180px; height: 180px; object-fit: cover; border-radius: 12px;">
                                    @else
                                        <div style="width: 180px; height: 180px; background: #f3f4f6; border: 2px dashed #d1d5db; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 14px;">
                                            No Image
                                        </div>
                                    @endif
                                @else
                                    <div style="width: 180px; height: 180px; background: #f3f4f6; border: 2px dashed #d1d5db; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 14px;">
                                        No Image
                                    </div>
                                @endif
                            </div>
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
                            @php
                                $prefecture = \App\Enums\Todofuken::tryFrom($job->prefecture_code);
                            @endphp
                            @if($prefecture)
                                {{ $prefecture->label() }}
                            @else
                                {{ $job->prefecture_code }}
                            @endif
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
            <p><a href="{{ route('login') }}" style="
                padding: 12px 24px;
                background: transparent;
                color: #5D535E;
                border: 1px solid #5D535E;
                border-radius: 24px;
                font-size: 14px;
                font-weight: 700;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.2s ease;
                position: relative;
                display: inline-block;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                ログインページへ
            </a></p>
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
                    " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                        応募する
                    </button>
                </div>
            </form>
        @endguest
    </section>
@endsection


