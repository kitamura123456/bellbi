@extends('layouts.admin')

@section('title', 'プラン管理')

@section('content')
<div class="admin-header">
    <h1 class="admin-title">プラン管理</h1>
    <a href="{{ route('admin.plans.create') }}" class="btn-primary">新規プラン追加</a>
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
                <th>プラン名</th>
                <th>説明</th>
                <th>月額料金</th>
                <th>機能ビットマスク</th>
                <th>ステータス</th>
                <th>登録日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($plans as $plan)
            <tr>
                <td>{{ $plan->id }}</td>
                <td>{{ $plan->name }}</td>
                <td style="max-width: 300px;">
                    @if($plan->description)
                        <span style="display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $plan->description }}">
                            {{ Str::limit($plan->description, 50) }}
                        </span>
                    @else
                        <span style="color: #999;">未設定</span>
                    @endif
                </td>
                <td>¥{{ number_format($plan->price_monthly) }}/月</td>
                <td>{{ $plan->features_bitmask }}</td>
                <td>
                    @if($plan->status === \App\Models\Plan::STATUS_ACTIVE)
                        <span class="badge badge-success">有効</span>
                    @else
                        <span class="badge badge-secondary">無効</span>
                    @endif
                </td>
                <td>{{ $plan->created_at->format('Y-m-d') }}</td>
                <td class="admin-actions">
                    <a href="{{ route('admin.plans.edit', $plan) }}" class="btn-secondary btn-sm">編集</a>
                    <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" style="display:inline;" onsubmit="return confirm('このプランを削除してもよろしいですか？\n\n注意: 有効な契約が存在する場合は削除できません。');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">削除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="empty-message">プランが登録されていません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

