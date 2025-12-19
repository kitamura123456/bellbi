@extends('layouts.admin')

@section('title', '事業者管理')

@section('content')
<div class="admin-header">
    <h1 class="admin-title">事業者管理</h1>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>事業者名</th>
                <th>担当者名</th>
                <th>業種</th>
                <th>プラン</th>
                <th>プランステータス</th>
                <th>登録日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($companies as $company)
            <tr>
                <td>{{ $company->id }}</td>
                <td>{{ $company->name }}</td>
                <td>{{ $company->contact_name ?? '-' }}</td>
                <td>
                    @if($company->industry_type === 1)
                        <span class="badge badge-primary">美容</span>
                    @elseif($company->industry_type === 2)
                        <span class="badge badge-info">医療</span>
                    @elseif($company->industry_type === 3)
                        <span class="badge badge-success">歯科</span>
                    @else
                        <span class="badge">その他</span>
                    @endif
                    @if($company->business_category)
                        <br><small style="color: #64748b; font-size: 11px;">
                            {{ \App\Services\BusinessCategoryService::getCategoryName($company->business_category) }}
                        </small>
                    @endif
                </td>
                <td>
                    @if($company->plan)
                        {{ $company->plan->name }}
                    @else
                        <span style="color: #94a3b8;">未設定</span>
                    @endif
                </td>
                <td>
                    @if($company->plan_status === 1)
                        <span class="badge badge-primary">トライアル</span>
                    @elseif($company->plan_status === 2)
                        <span class="badge badge-success">有効</span>
                    @elseif($company->plan_status === 3)
                        <span class="badge badge-danger">遅延</span>
                    @elseif($company->plan_status === 9)
                        <span class="badge" style="background-color: #e2e8f0; color: #64748b;">解約</span>
                    @endif
                </td>
                <td>{{ $company->created_at->format('Y-m-d') }}</td>
                <td class="admin-actions">
                    <a href="{{ route('admin.companies.edit', $company) }}" class="btn-secondary btn-sm">編集</a>
                    <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" style="display:inline;" onsubmit="return confirm('この事業者を削除してもよろしいですか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">削除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="empty-message">事業者が登録されていません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrapper">
        {{ $companies->links() }}
    </div>
</div>
@endsection

