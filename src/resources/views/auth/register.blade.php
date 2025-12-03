@extends('layouts.app')

@section('title', '会員登録 | Bellbi')

@section('content')
    <div class="auth-card">
        <h2 class="auth-title">会員登録（求職者向け）</h2>
        <p class="auth-lead">
            美容・医療・歯科のお仕事情報に応募するためのアカウントを作成します。
        </p>

        <form method="post" action="{{ route('register.post') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="name">お名前</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">パスワード（8文字以上）</label>
                <input id="password" type="password" name="password" required>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">パスワード（確認）</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">登録する</button>
            </div>
        </form>

        <p class="auth-footnote">
            すでにアカウントをお持ちの方は
            <a href="{{ route('login') }}">こちらからログイン</a>
        </p>
    </div>
@endsection


