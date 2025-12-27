@extends('layouts.company')

@section('title', '求人管理')

@section('content')
<div style="margin-bottom: 24px; margin-top: 48px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">求人管理</h1>
    <a href="{{ route('company.job-posts.create') }}" style="
        padding: 12px 24px;
        background: #5D535E;
        color: #ffffff;
        border: none;
        border-radius: 24px;
        font-size: 14px;
        font-weight: 700;
        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
        新規求人作成
    </a>
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
    overflow-x: auto;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">求人一覧</h3>
    </div>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #fafafa; border-bottom: 1px solid #e8e8e8;">
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">求人タイトル</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">ステータス</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">雇用形態</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">給与</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">作成日</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobPosts as $jobPost)
            <tr style="border-bottom: 1px solid #f5f5f5;" onmouseover="this.style.background='#fafafa';" onmouseout="this.style.background='#ffffff';">
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $jobPost->title }}</td>
                <td style="padding: 12px 16px;">
                    @if($jobPost->status === 0)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #999999;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">下書き</span>
                    @elseif($jobPost->status === 1)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #336B87;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">公開中</span>
                    @elseif($jobPost->status === 2)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #763626;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">停止</span>
                    @endif
                </td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">
                    @if($jobPost->employment_type === 1) 正社員
                    @elseif($jobPost->employment_type === 2) パート
                    @elseif($jobPost->employment_type === 3) 業務委託
                    @else その他
                    @endif
                </td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">
                    @if($jobPost->min_salary && $jobPost->max_salary)
                        {{ number_format($jobPost->min_salary) }}円〜{{ number_format($jobPost->max_salary) }}円
                    @elseif($jobPost->min_salary)
                        {{ number_format($jobPost->min_salary) }}円〜
                    @else
                        応相談
                    @endif
                </td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $jobPost->created_at->format('Y-m-d') }}</td>
                <td style="padding: 12px 16px;">
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <a href="{{ route('jobs.show', $jobPost) }}" target="_blank" style="
                            padding: 6px 16px;
                            background: transparent;
                            color: #5D535E;
                            border: 1px solid #5D535E;
                            border-radius: 16px;
                            font-size: 12px;
                            font-weight: 700;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                            text-decoration: none;
                            transition: all 0.2s ease;
                            position: relative;
                        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                            表示
                        </a>
                        <a href="{{ route('company.job-posts.edit', $jobPost) }}" style="
                            padding: 6px 16px;
                            background: transparent;
                            color: #5D535E;
                            border: 1px solid #5D535E;
                            border-radius: 16px;
                            font-size: 12px;
                            font-weight: 700;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                            text-decoration: none;
                            transition: all 0.2s ease;
                            position: relative;
                        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                            編集
                        </a>
                        <form action="{{ route('company.job-posts.destroy', $jobPost) }}" method="POST" style="display:inline;" onsubmit="return confirm('この求人を削除してもよろしいですか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="
                                padding: 6px 16px;
                                background: #763626;
                                color: #ffffff;
                                border: none;
                                border-radius: 16px;
                                font-size: 12px;
                                font-weight: 700;
                                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                                cursor: pointer;
                                transition: all 0.2s ease;
                                position: relative;
                            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                                削除
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 40px 16px; text-align: center; color: #999999; font-size: 14px;">求人が登録されていません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- スマホ用カードレイアウト -->
<div class="job-posts-cards">
    @forelse($jobPosts as $jobPost)
    <div class="job-post-card">
        <div class="job-post-card-header">
            <div class="job-post-card-title">{{ $jobPost->title }}</div>
            <div>
                @if($jobPost->status === 0)
                    <span class="job-post-badge job-post-badge-draft">下書き</span>
                @elseif($jobPost->status === 1)
                    <span class="job-post-badge job-post-badge-public">公開中</span>
                @elseif($jobPost->status === 2)
                    <span class="job-post-badge job-post-badge-stopped">停止</span>
                @endif
            </div>
        </div>
        <div class="job-post-card-body">
            <div class="job-post-card-row">
                <span class="job-post-card-label">雇用形態</span>
                <span class="job-post-card-value">
                    @if($jobPost->employment_type === 1) 正社員
                    @elseif($jobPost->employment_type === 2) パート
                    @elseif($jobPost->employment_type === 3) 業務委託
                    @else その他
                    @endif
                </span>
            </div>
            <div class="job-post-card-row">
                <span class="job-post-card-label">給与</span>
                <span class="job-post-card-value">
                    @if($jobPost->min_salary && $jobPost->max_salary)
                        {{ number_format($jobPost->min_salary) }}円〜{{ number_format($jobPost->max_salary) }}円
                    @elseif($jobPost->min_salary)
                        {{ number_format($jobPost->min_salary) }}円〜
                    @else
                        応相談
                    @endif
                </span>
            </div>
            <div class="job-post-card-row">
                <span class="job-post-card-label">作成日</span>
                <span class="job-post-card-value">{{ $jobPost->created_at->format('Y年m月d日') }}</span>
            </div>
        </div>
        <div class="job-post-card-actions">
            <a href="{{ route('jobs.show', $jobPost) }}" target="_blank" class="job-post-card-btn job-post-card-btn-view">
                表示
            </a>
            <a href="{{ route('company.job-posts.edit', $jobPost) }}" class="job-post-card-btn job-post-card-btn-edit">
                編集
            </a>
            <form action="{{ route('company.job-posts.destroy', $jobPost) }}" method="POST" class="job-post-card-form" onsubmit="return confirm('この求人を削除してもよろしいですか？');">
                @csrf
                @method('DELETE')
                <button type="submit" class="job-post-card-btn job-post-card-btn-delete">
                    削除
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="job-post-card-empty">
        <p>求人が登録されていません。</p>
    </div>
    @endforelse
</div>

<style>
/* スマホ用カードレイアウト（デフォルトは非表示） */
.job-posts-cards {
    display: none;
}

.job-post-card {
    background: #ffffff;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
}

.job-post-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e8e8e8;
    gap: 12px;
}

.job-post-card-title {
    font-size: 18px;
    font-weight: 700;
    color: #5D535E;
    flex: 1;
}

.job-post-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
}

.job-post-badge-draft {
    background: #999999;
    color: #ffffff;
}

.job-post-badge-public {
    background: #336B87;
    color: #ffffff;
}

.job-post-badge-stopped {
    background: #763626;
    color: #ffffff;
}

.job-post-card-body {
    display: grid;
    gap: 10px;
    margin-bottom: 12px;
}

.job-post-card-row {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    padding: 4px 0;
}

.job-post-card-label {
    color: #6b7280;
    font-weight: 500;
}

.job-post-card-value {
    color: #111827;
    font-weight: 600;
    text-align: right;
    flex: 1;
    margin-left: 12px;
}

.job-post-card-actions {
    display: flex;
    gap: 8px;
    padding-top: 12px;
    border-top: 1px solid #e8e8e8;
    flex-wrap: wrap;
}

.job-post-card-btn {
    flex: 1;
    min-width: calc(33.333% - 6px);
    padding: 10px 16px;
    border-radius: 16px;
    font-size: 13px;
    font-weight: 700;
    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
    text-decoration: none;
    text-align: center;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.job-post-card-btn-view {
    background: transparent;
    color: #5D535E;
    border: 1px solid #5D535E;
}

.job-post-card-btn-edit {
    background: transparent;
    color: #5D535E;
    border: 1px solid #5D535E;
}

.job-post-card-btn-delete {
    background: #763626;
    color: #ffffff;
}

.job-post-card-form {
    flex: 1;
    min-width: calc(33.333% - 6px);
    margin: 0;
}

.job-post-card-empty {
    background: #ffffff;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    padding: 40px 16px;
    text-align: center;
    color: #999999;
    font-size: 14px;
}

/* スマホ用レスポンシブデザイン */
@media (max-width: 768px) {
    div[style*="margin-top: 48px"] {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 12px !important;
    }

    div[style*="margin-top: 48px"] h1 {
        font-size: 20px !important;
        margin-bottom: 0 !important;
    }

    div[style*="margin-top: 48px"] > a {
        width: 100%;
        text-align: center;
        font-size: 13px;
        padding: 10px 16px;
    }

    div[style*="overflow-x: auto"] {
        display: none;
    }

    .job-posts-cards {
        display: block;
    }

    .job-post-card {
        margin-bottom: 16px;
    }

    .job-post-card-title {
        font-size: 20px;
    }

    .job-post-card-row {
        font-size: 15px;
    }

    .job-post-card-btn {
        font-size: 14px;
        padding: 12px 16px;
        min-width: calc(50% - 4px);
    }

    .job-post-card-form {
        min-width: calc(50% - 4px);
    }
}

@media (max-width: 480px) {
    .job-post-card {
        padding: 12px;
    }

    .job-post-card-title {
        font-size: 18px;
    }

    .job-post-card-row {
        font-size: 14px;
    }

    .job-post-card-btn {
        font-size: 13px;
        padding: 10px 12px;
        min-width: 100%;
    }

    .job-post-card-form {
        min-width: 100%;
    }
}
</style>
@endsection

