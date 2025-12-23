<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Bellbi')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- MAMP 環境で /bellbi/ 配下から確実に読み込めるように、パスを明示的に指定 --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            background: #ffffff;
            color: #1a1a1a;
        }
        .nav-link-hover {
            position: relative;
        }
        .nav-link-hover::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: #1a1a1a;
            transition: width 0.3s ease;
        }
        .nav-link-hover:hover {
            color: #666 !important;
        }
        .nav-link-hover:hover::after {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <header style="
            background: #ffffff;
            border-bottom: 1px solid #f0f0f0;
            position: sticky;
            top: 0;
            z-index: 100;
            color: #1a1a1a;
        ">
            <div style="
                max-width: 1080px;
                margin: 0 auto;
                padding: 16px 24px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            ">
                <div style="display: flex; align-items: center;">
                    <a href="{{ route('jobs.index') }}" style="
                        display: flex;
                        align-items: center;
                        text-decoration: none;
                        color: #1a1a1a;
                    ">
                        <img src="{{ asset('images/Black Beige Minimal Simple Elegant   Art Design  Creative Studio Gaze Logo (1).png') }}" alt="Bellbi" style="height: 50px; width: auto;">
                    </a>
                </div>
                <nav style="
                    display: flex;
                    align-items: center;
                    gap: 24px;
                ">
                    <a href="{{ route('jobs.index') }}" class="nav-link-hover" style="
                        color: #1a1a1a;
                        text-decoration: none;
                        font-size: 13px;
                        font-weight: 400;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        padding: 4px 0;
                        transition: color 0.3s ease;
                        letter-spacing: 0.02em;
                    ">
                        求人を探す
                    </a>
                    <a href="{{ route('reservations.search') }}" class="nav-link-hover" style="
                        color: #1a1a1a;
                        text-decoration: none;
                        font-size: 13px;
                        font-weight: 400;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        padding: 4px 0;
                        transition: color 0.3s ease;
                        letter-spacing: 0.02em;
                    ">
                        予約する
                    </a>
                    <a href="{{ route('shops.index') }}" class="nav-link-hover" style="
                        color: #1a1a1a;
                        text-decoration: none;
                        font-size: 13px;
                        font-weight: 400;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        padding: 4px 0;
                        transition: color 0.3s ease;
                        letter-spacing: 0.02em;
                    ">
                        ショップ
                    </a>
                    <a href="{{ route('cart.index') }}" class="nav-link-hover" style="
                        color: #1a1a1a;
                        text-decoration: none;
                        font-size: 13px;
                        font-weight: 400;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        padding: 4px 0;
                        transition: color 0.3s ease;
                        letter-spacing: 0.02em;
                    ">
                        カート
                    </a>

                    @auth
                        @php
                            /** @var \App\Models\User $user */
                            $user = auth()->user();
                        @endphp

                        @if ($user->role === \App\Models\User::ROLE_PERSONAL)
                            <a href="{{ route('mypage') }}" class="nav-link-hover" style="
                                color: #1a1a1a;
                                text-decoration: none;
                                font-size: 13px;
                                font-weight: 400;
                                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                                padding: 4px 0;
                                transition: color 0.3s ease;
                                letter-spacing: 0.02em;
                            ">
                                マイページ
                            </a>
                        @elseif ($user->role === \App\Models\User::ROLE_COMPANY)
                            <a href="{{ route('company.dashboard') }}" class="nav-link-hover" style="
                                color: #1a1a1a;
                                text-decoration: none;
                                font-size: 13px;
                                font-weight: 400;
                                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                                padding: 4px 0;
                                transition: color 0.3s ease;
                                letter-spacing: 0.02em;
                            ">
                                事業者ダッシュボード
                            </a>
                        @elseif ($user->role === \App\Models\User::ROLE_ADMIN)
                            <a href="{{ route('admin.index') }}" class="nav-link-hover" style="
                                color: #1a1a1a;
                                text-decoration: none;
                                font-size: 13px;
                                font-weight: 400;
                                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                                padding: 4px 0;
                                transition: color 0.3s ease;
                                letter-spacing: 0.02em;
                            ">
                                管理画面
                            </a>
                        @endif

                        <form method="post" action="{{ route('logout') }}" style="display: flex; align-items: center; margin: 0;">
                            @csrf
                            <button type="submit" class="nav-link-hover" style="
                                background: transparent;
                                border: none;
                                color: #1a1a1a;
                                font-size: 13px;
                                font-weight: 400;
                                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                                cursor: pointer;
                                padding: 4px 0;
                                transition: color 0.3s ease;
                                letter-spacing: 0.02em;
                            ">
                                ログアウト
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav-link-hover" style="
                            color: #1a1a1a;
                            text-decoration: none;
                            font-size: 13px;
                            font-weight: 400;
                            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            padding: 4px 0;
                            transition: color 0.3s ease;
                            letter-spacing: 0.02em;
                        ">
                            ログイン
                        </a>
                        <a href="{{ route('register') }}" style="
                            background: #1a1a1a;
                            color: #ffffff;
                            text-decoration: none;
                            font-size: 12px;
                            font-weight: 500;
                            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            padding: 10px 24px;
                            border-radius: 0;
                            border: 1px solid #1a1a1a;
                            transition: all 0.3s ease;
                            letter-spacing: 0.05em;
                            text-transform: uppercase;
                        " onmouseover="this.style.background='#000000'; this.style.borderColor='#000000';" onmouseout="this.style.background='#1a1a1a'; this.style.borderColor='#1a1a1a';">
                            会員登録
                        </a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="site-main" style="background: #ffffff; min-height: calc(100vh - 200px);">
            <div class="container main-inner" style="max-width: 1400px; padding: 48px 24px; display: flex; gap: 48px;">
                <aside class="sidebar" style="min-width: 320px; flex-shrink: 0;">
                    @yield('sidebar')
                </aside>

                    <section class="content" style="flex: 1; min-width: 0;">
                        @if (session('status') && !request()->routeIs('cart.index') && !request()->routeIs('orders.complete'))
                            <div style="
                                background-color: #5D535E;
                                border-radius: 8px;
                                border: 1px solid #2A3132;
                                color: #F9FAFB;
                                padding: 12px 16px;
                                margin-bottom: 20px;
                                font-size: 14px;
                                font-weight: 600;
                                font-family: 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
                                display: flex;
                                align-items: center;
                                gap: 8px;
                            ">
                                <span style="
                                    width: 6px;
                                    height: 24px;
                                    border-radius: 999px;
                                    background-color: #90AFC5;
                                    display: inline-block;
                                "></span>
                                {{ session('status') }}
                            </div>
                        @endif

                        @yield('content')
                    </section>
            </div>
        </main>

        <footer style="
            border-top: 1px solid #f0f0f0;
            background-color: #ffffff;
            margin-top: auto;
            padding: 32px 0;
        ">
            <div style="
                max-width: 1400px;
                margin: 0 auto;
                padding: 0 24px;
                text-align: center;
            ">
                <p style="
                    margin: 0;
                    font-size: 11px;
                    color: #999;
                    font-weight: 400;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    letter-spacing: 0.05em;
                ">&copy; {{ date('Y') }} Bellbi. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>


