@extends('layouts.admin')

@section('title', 'ユーザー管理')

@section('content')
<div class="admin-header">
    <h1 class="admin-title">ユーザー管理</h1>
    <a href="{{ route('admin.users.create') }}" class="btn-primary">新規ユーザー追加</a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>ロール</th>
                <th>登録日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->role === 1)
                        <span class="badge badge-primary">求職者</span>
                    @elseif($user->role === 2)
                        <span class="badge badge-success">事業者</span>
                    @elseif($user->role === 9)
                        <span class="badge badge-danger">管理者</span>
                    @endif
                </td>
                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                <td class="admin-actions">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary btn-sm">編集</a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('このユーザーを削除してもよろしいですか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">削除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="empty-message">ユーザーが登録されていません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrapper">
        {{ $users->links() }}
    </div>
</div>
@endsection

