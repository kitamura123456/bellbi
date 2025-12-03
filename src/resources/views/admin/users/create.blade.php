@extends('layouts.admin')

@section('title', 'ユーザー新規作成')

@section('content')
<div class="admin-header">
    <h1 class="admin-title">ユーザー新規作成</h1>
    <a href="{{ route('admin.users.index') }}" class="btn-secondary">一覧に戻る</a>
</div>

<div class="admin-card">
    <form action="{{ route('admin.users.store') }}" method="POST" class="admin-form">
        @csrf

        <div class="form-group">
            <label for="name">名前 <span class="required">必須</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">メールアドレス <span class="required">必須</span></label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">パスワード <span class="required">必須</span></label>
            <input type="password" id="password" name="password" required>
            <small>8文字以上で入力してください</small>
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="role">ロール <span class="required">必須</span></label>
            <select id="role" name="role" required>
                <option value="">選択してください</option>
                <option value="1" {{ old('role') == 1 ? 'selected' : '' }}>求職者（エンドユーザー）</option>
                <option value="2" {{ old('role') == 2 ? 'selected' : '' }}>事業者（店舗アカウント）</option>
                <option value="9" {{ old('role') == 9 ? 'selected' : '' }}>管理者</option>
            </select>
            @error('role')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">作成する</button>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection

