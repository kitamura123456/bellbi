-- Active: 1765333928509@@127.0.0.1@3306@bellbi
@php
use App\Enums\Todofuken;
@endphp
@extends('layouts.app')

@section('title', '求人一覧 | Bellbi')

@section('sidebar')
    <div class="sidebar-card">
        <h3 class="sidebar-title">条件でさがす</h3>
        <form class="search-form">
            <div class="form-group">
                <label for="keyword">キーワード</label>
                <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}" placeholder="エリア・サロン名・職種など">
            </div>
            <div class="form-group">
                {{-- enumで都道府県全部対応させる --}}
                <label for="area">エリア</label>
                <select multiple id="area" name="area">
                    <option value="">指定なし</option>
                    @foreach(App\Enums\Todofuken::cases() as $pref)
                    <option value="{{ $pref->value }}"
                        {{ is_array(request('area')) && in_array($pref->value, request('area', [])) ? 'selected' : ''}}>
                        {{ $pref->label()}}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="employment_type">雇用形態</label>
                <select multiple id="employment_type" name="employment_type">
                    <option value="">指定なし</option>
                    <option value="1" {{ is_array(request('employment_type')) && in_array(1, request('employment_type', [])) ? 'selected' : ''}}>正社員</option>
                    <option value="2" {{ is_array(request('employment_type')) && in_array(2, request('employment_type', [])) ? 'selected' : ''}}>パート・アルバイト</option>
                    <option value="3" {{ is_array(request('employment_type')) && in_array(3, request('employment_type', [])) ? 'selected' : ''}}>業務委託</option>
                </select>
            </div>
            @if(isset($tags) && $tags->isNotEmpty())
            <div class="form-group">
                <label for="tags">タグ</label>
                <select multiple id="tags" name="tags[]">
                    <option value="">指定なし</option>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ is_array(request('tags')) && in_array($tag->id, request('tags', [])) ? 'selected' : ''}}>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            <button type="submit" style="
                width: 100%;
                padding: 12px 24px;
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
                この条件で検索
            </button>
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
                        <a href="{{ route('jobs.show', $job) }}" style="
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
                            詳細を見る
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="pagination-wrapper">
            {{ $jobs->links() }}
        </div>
    @endif
@endsection


