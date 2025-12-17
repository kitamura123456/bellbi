@extends('layouts.app')

@section('title', 'ログイン | Bellbi')

@section('content')
    <div class="auth-card">
        <h2 class="auth-title">ログイン</h2>
        <p class="auth-lead">登録済みのメールアドレス（またはユーザーID）とパスワードでログインしてください。</p>

        <form method="post" action="{{ route('login.post') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email">メールアドレス / ユーザーID</label>
                <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input id="password" type="password" name="password" required>
            </div>

            <div class="form-group form-inline">
                <label>
                    <input type="checkbox" name="remember" value="1">
                    ログイン状態を保持する
                </label>
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
                    width: 100%;
                " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                    ログイン
                </button>
            </div>
        </form>

        <p class="auth-footnote">
            はじめての方は
            <a href="{{ route('register') }}">こちらから会員登録</a>
        </p>
    </div>
@endsection


