@extends('layouts.app')

@section('title', '会員登録 | Bellbi')

@section('sidebar')
    {{-- サイドバーなし --}}
@endsection

@section('content')
    <style>
        /* 登録ページのみ：サイドバーを非表示にしてコンテンツを中央寄せ */
        @media (min-width: 769px) {
            .auth-register-page-wrapper .main-inner {
                justify-content: center !important;
            }
            .auth-register-page-wrapper .sidebar {
                display: none !important;
            }
            .auth-register-page-wrapper .content {
                max-width: 800px !important;
                width: 100% !important;
                margin: 0 auto !important;
            }
        }
    </style>
    <script>
        // ページ読み込み時にbodyにクラスを追加
        (function() {
            document.body.classList.add('auth-register-page-wrapper');
        })();
    </script>
    <div class="auth-register-page">
    <div class="auth-card" style="background: #ffffff; border-radius: 0; padding: 48px 40px; box-shadow: none; border: none; max-width: 480px; margin: 0 auto;">
        <h2 class="auth-title" style="font-size: 32px; font-weight: 400; color: #1a1a1a; margin: 0 0 16px 0; letter-spacing: -0.02em; line-height: 1.3; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">会員登録（求職者向け）</h2>
        <p class="auth-lead" style="font-size: 14px; color: #666; line-height: 1.7; margin: 0 0 32px 0; font-weight: 400; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
            美容・医療・歯科のお仕事情報に応募するためのアカウントを作成します。
        </p>

        <form method="post" action="{{ route('register.post') }}" class="auth-form">
            @csrf

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="name" style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.02em;">お名前</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 12px 16px; border: 1px solid #e0e0e0; border-radius: 0; font-size: 14px; background: #ffffff; color: #1a1a1a; transition: all 0.3s ease; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; box-sizing: border-box;" onfocus="this.style.borderColor='#1a1a1a'; this.style.outline='none';" onblur="this.style.borderColor='#e0e0e0';">
                @error('name')
                    <div class="error" style="color: #dc2626; font-size: 12px; margin-top: 4px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="email" style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.02em;">メールアドレス <span style="color: #999; font-size: 11px; font-weight: 400;">（任意）</span></label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" style="width: 100%; padding: 12px 16px; border: 1px solid #e0e0e0; border-radius: 0; font-size: 14px; background: #ffffff; color: #1a1a1a; transition: all 0.3s ease; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; box-sizing: border-box;" onfocus="this.style.borderColor='#1a1a1a'; this.style.outline='none';" onblur="this.style.borderColor='#e0e0e0';">
                <small style="display: block; margin-top: 4px; color: #999; font-size: 11px;">メールアドレスは任意です。入力しない場合はユーザー名でログインできます。</small>
                @error('email')
                    <div class="error" style="color: #dc2626; font-size: 12px; margin-top: 4px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="password" style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.02em;">パスワード（8文字以上）</label>
                <input id="password" type="password" name="password" required style="width: 100%; padding: 12px 16px; border: 1px solid #e0e0e0; border-radius: 0; font-size: 14px; background: #ffffff; color: #1a1a1a; transition: all 0.3s ease; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; box-sizing: border-box;" onfocus="this.style.borderColor='#1a1a1a'; this.style.outline='none';" onblur="this.style.borderColor='#e0e0e0';">
                @error('password')
                    <div class="error" style="color: #dc2626; font-size: 12px; margin-top: 4px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 32px;">
                <label for="password_confirmation" style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.02em;">パスワード（確認）</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required style="width: 100%; padding: 12px 16px; border: 1px solid #e0e0e0; border-radius: 0; font-size: 14px; background: #ffffff; color: #1a1a1a; transition: all 0.3s ease; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; box-sizing: border-box;" onfocus="this.style.borderColor='#1a1a1a'; this.style.outline='none';" onblur="this.style.borderColor='#e0e0e0';">
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
                    登録する
                </button>
            </div>
        </form>

        <p class="auth-footnote" style="margin-top: 24px; font-size: 13px; color: #666; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
            すでにアカウントをお持ちの方は
            <a href="{{ route('login') }}" style="color: #1a1a1a; text-decoration: none; border-bottom: 1px solid #1a1a1a; transition: opacity 0.3s ease;" onmouseover="this.style.opacity='0.7';" onmouseout="this.style.opacity='1';">こちらからログイン</a>
        </p>
    </div>
    </div>
@endsection


