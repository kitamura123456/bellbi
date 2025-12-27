@extends('layouts.app')

@section('title', 'ログイン | Bellbi')

@section('sidebar')
    {{-- サイドバーなし --}}
@endsection

@section('content')
    <style>
        /* ログインページのみ：サイドバーを非表示にしてコンテンツを中央寄せ */
        @media (min-width: 769px) {
            .auth-login-page-wrapper .main-inner {
                justify-content: center !important;
            }
            .auth-login-page-wrapper .sidebar {
                display: none !important;
            }
            .auth-login-page-wrapper .content {
                max-width: 800px !important;
                width: 100% !important;
                margin: 0 auto !important;
            }
        }
    </style>
    <script>
        // ページ読み込み時にbodyにクラスを追加
        (function() {
            document.body.classList.add('auth-login-page-wrapper');
        })();
    </script>
    <div class="auth-login-page">
    <div class="auth-card" style="background: #ffffff; border-radius: 0; padding: 48px 40px; box-shadow: none; border: none; max-width: 480px; margin: 0 auto;">
        <h2 class="auth-title" style="font-size: 32px; font-weight: 400; color: #1a1a1a; margin: 0 0 16px 0; letter-spacing: -0.02em; line-height: 1.3; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">ログイン</h2>
        <p class="auth-lead" style="font-size: 14px; color: #666; line-height: 1.7; margin: 0 0 32px 0; font-weight: 400; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">登録済みのメールアドレス（またはユーザーID）とパスワードでログインしてください。</p>

        <form method="post" action="{{ route('login.post') }}" class="auth-form">
            @csrf

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="email" style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.02em;">メールアドレス / ユーザー名</label>
                <input id="email" type="text" name="email" value="{{ old('email') }}" placeholder="メールアドレスまたはユーザー名" style="width: 100%; padding: 12px 16px; border: 1px solid #e0e0e0; border-radius: 0; font-size: 14px; background: #ffffff; color: #1a1a1a; transition: all 0.3s ease; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; box-sizing: border-box;" onfocus="this.style.borderColor='#1a1a1a'; this.style.outline='none';" onblur="this.style.borderColor='#e0e0e0';">
                <small style="display: block; margin-top: 4px; color: #999; font-size: 11px;">メールアドレスまたはユーザー名を入力してください</small>
                @error('email')
                    <div class="error" style="color: #dc2626; font-size: 12px; margin-top: 4px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="password" style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.02em;">パスワード</label>
                <input id="password" type="password" name="password" required style="width: 100%; padding: 12px 16px; border: 1px solid #e0e0e0; border-radius: 0; font-size: 14px; background: #ffffff; color: #1a1a1a; transition: all 0.3s ease; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; box-sizing: border-box;" onfocus="this.style.borderColor='#1a1a1a'; this.style.outline='none';" onblur="this.style.borderColor='#e0e0e0';">
            </div>

            <div class="form-group form-inline" style="margin-bottom: 32px;">
                <label style="display: flex; align-items: center; font-size: 13px; color: #666; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; cursor: pointer;">
                    <input type="checkbox" name="remember" value="1" style="margin-right: 8px; cursor: pointer; accent-color: #1a1a1a;">
                    ログイン状態を保持する
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" style="
                    padding: 14px 32px;
                    background: #1a1a1a;
                    color: #ffffff;
                    border: 1px solid #1a1a1a;
                    border-radius: 0;
                    font-size: 13px;
                    font-weight: 500;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    position: relative;
                    width: 100%;
                    letter-spacing: 0.05em;
                    text-transform: uppercase;
                " onmouseover="this.style.background='#000000'; this.style.borderColor='#000000';" onmouseout="this.style.background='#1a1a1a'; this.style.borderColor='#1a1a1a';">
                    ログイン
                </button>
            </div>
        </form>

        <p class="auth-footnote" style="margin-top: 24px; font-size: 13px; color: #666; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
            はじめての方は
            <a href="{{ route('register') }}" style="color: #1a1a1a; text-decoration: none; border-bottom: 1px solid #1a1a1a; transition: opacity 0.3s ease;" onmouseover="this.style.opacity='0.7';" onmouseout="this.style.opacity='1';">こちらから会員登録</a>
        </p>
    </div>
    </div>
@endsection


