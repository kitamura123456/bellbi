@extends('layouts.admin')

@section('title', 'ユーザー編集')

@section('content')
<div class="admin-header">
    <h1 class="admin-title">ユーザー編集</h1>
    <a href="{{ route('admin.users.index') }}" class="btn-secondary">一覧に戻る</a>
</div>

<div class="admin-card">
    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="admin-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">名前 <span class="required">必須</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">メールアドレス <span class="required">必須</span></label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password">
            <small>変更する場合のみ入力してください（8文字以上）</small>
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="role">ロール <span class="required">必須</span></label>
            <select id="role" name="role" required>
                <option value="1" {{ old('role', $user->role) == 1 ? 'selected' : '' }}>求職者（エンドユーザー）</option>
                <option value="2" {{ old('role', $user->role) == 2 ? 'selected' : '' }}>事業者（店舗アカウント）</option>
                <option value="9" {{ old('role', $user->role) == 9 ? 'selected' : '' }}>管理者</option>
            </select>
            @error('role')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">更新する</button>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection

