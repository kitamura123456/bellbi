@extends('layouts.admin')

@section('title', '補助金情報管理')

@section('content')
<div class="admin-header">
    <h1 class="admin-title">補助金情報管理</h1>
    <a href="{{ route('admin.subsidies.create') }}" class="btn-primary">新規補助金追加</a>
</div>

@if (session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
@endif

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>タイトル</th>
                <th>カテゴリ</th>
                <th>対象地域</th>
                <th>対象業種</th>
                <th>申請期間</th>
                <th>ステータス</th>
                <th>登録日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subsidies as $subsidy)
            <tr>
                <td>{{ $subsidy->id }}</td>
                <td style="max-width: 250px;">
                    <span style="display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $subsidy->title }}">
                        {{ $subsidy->title }}
                    </span>
                </td>
                <td>
                    @if($subsidy->category)
                        <span class="badge badge-info">{{ $subsidy->category_name }}</span>
                    @else
                        <span style="color: #999;">未設定</span>
                    @endif
                </td>
                <td>
                    @if($subsidy->target_region)
                        @php
                            $region = \App\Enums\Todofuken::tryFrom($subsidy->target_region);
                        @endphp
                        @if($region)
                            {{ $region->label() }}
                        @else
                            全国
                        @endif
                    @else
                        <span style="color: #999;">全国</span>
                    @endif
                </td>
                <td>
                    @if($subsidy->applicable_industry_type)
                        @php
                            $industryTypes = \App\Services\BusinessCategoryService::getIndustryTypes();
                        @endphp
                        {{ $industryTypes[$subsidy->applicable_industry_type] ?? '全業種' }}
                    @else
                        <span style="color: #999;">全業種</span>
                    @endif
                </td>
                <td style="font-size: 12px;">
                    @if($subsidy->application_start_at && $subsidy->application_end_at)
                        {{ $subsidy->application_start_at->format('Y/m/d') }}<br>
                        ～ {{ $subsidy->application_end_at->format('Y/m/d') }}
                    @elseif($subsidy->application_start_at)
                        {{ $subsidy->application_start_at->format('Y/m/d') }} 以降
                    @elseif($subsidy->application_end_at)
                        {{ $subsidy->application_end_at->format('Y/m/d') }} まで
                    @else
                        <span style="color: #999;">未設定</span>
                    @endif
                </td>
                <td>
                    @if($subsidy->status == \App\Models\Subsidy::STATUS_RECRUITING)
                        <span class="badge badge-success">募集中</span>
                    @elseif($subsidy->status == \App\Models\Subsidy::STATUS_CLOSED)
                        <span class="badge badge-secondary">締切</span>
                    @elseif($subsidy->status == \App\Models\Subsidy::STATUS_NOT_STARTED)
                        <span class="badge badge-warning">未開始</span>
                    @else
                        <span class="badge badge-secondary">不明</span>
                    @endif
                </td>
                <td>{{ $subsidy->created_at->format('Y-m-d') }}</td>
                <td class="admin-actions">
                    <a href="{{ route('admin.subsidies.edit', $subsidy) }}" class="btn-secondary btn-sm">編集</a>
                    <form action="{{ route('admin.subsidies.destroy', $subsidy) }}" method="POST" style="display:inline;" onsubmit="return confirm('この補助金情報を削除してもよろしいですか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">削除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="empty-message">補助金情報が登録されていません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($subsidies->hasPages())
    <div style="margin-top: 24px;">
        {{ $subsidies->links() }}
    </div>
@endif
@endsection

