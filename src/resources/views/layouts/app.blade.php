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
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f3f4f6;
            color: #111827;
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
            height: 2px;
            background: #ffffff;
            transition: width 0.18s ease;
        }
        .nav-link-hover:hover::after {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <header style="
            background: #5D535E;
            border-bottom: 1px solid #2A3132;
            position: sticky;
            top: 0;
            z-index: 100;
            color: #ffffff;
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
                        color: #ffffff;
                    ">
                        <span style="
                            width: 40px;
                            height: 40px;
                            border-radius: 6px;
                            background: #2A3132;
                            color: #ffffff;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-weight: 700;
                            font-size: 18px;
                            margin-right: 12px;
                            border: 1px solid #90AFC5;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        ">B</span>
                        <span style="display: flex; flex-direction: column;">
                            <span style="
                                font-size: 20px;
                                font-weight: 700;
                                color: #ffffff;
                                line-height: 1.2;
                                letter-spacing: 0.04em;
                                font-family: 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            ">Bellbi</span>
                            <span style="
                                font-size: 10px;
                                color: #E5E7EB;
                                margin-top: 2px;
                                letter-spacing: 0.12em;
                                text-transform: uppercase;
                                font-family: 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            ">Beauty Career & Salon Support</span>
                        </span>
                    </a>
                </div>
                <nav style="
                    display: flex;
                    align-items: center;
                    gap: 24px;
                ">
                    <a href="{{ route('jobs.index') }}" class="nav-link-hover" style="
                        color: #ffffff;
                        text-decoration: none;
                        font-size: 14px;
                        font-weight: 700;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        padding: 4px 0;
                        transition: color 0.18s ease;
                    ">
                        求人を探す
                    </a>
                    <a href="{{ route('reservations.search') }}" class="nav-link-hover" style="
                        color: #ffffff;
                        text-decoration: none;
                        font-size: 14px;
                        font-weight: 700;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        padding: 4px 0;
                        transition: color 0.18s ease;
                    ">
                        予約する
                    </a>

                    @auth
                        @php
                            /** @var \App\Models\User $user */
                            $user = auth()->user();
                        @endphp

                        @if ($user->role === \App\Models\User::ROLE_PERSONAL)
                            <a href="{{ route('mypage') }}" class="nav-link-hover" style="
                                color: #E5E7EB;
                                text-decoration: none;
                                font-size: 14px;
                                font-weight: 600;
                                font-family: 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                                padding: 4px 0;
                                transition: color 0.18s ease;
                            ">
                                マイページ
                            </a>
                        @elseif ($user->role === \App\Models\User::ROLE_COMPANY)
                            <a href="{{ route('company.dashboard') }}" class="nav-link-hover" style="
                                color: #E5E7EB;
                                text-decoration: none;
                                font-size: 14px;
                                font-weight: 600;
                                font-family: 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                                padding: 4px 0;
                                transition: color 0.18s ease;
                            ">
                                事業者ダッシュボード
                            </a>
                        @elseif ($user->role === \App\Models\User::ROLE_ADMIN)
                            <a href="{{ route('admin.index') }}" class="nav-link-hover" style="
                                color: #E5E7EB;
                                text-decoration: none;
                                font-size: 14px;
                                font-weight: 600;
                                font-family: 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                                padding: 4px 0;
                                transition: color 0.18s ease;
                            ">
                                管理画面
                            </a>
                        @endif

                        <form method="post" action="{{ route('logout') }}" style="display: inline-block;">
                            @csrf
                            <button type="submit" class="nav-link-hover" style="
                                background: transparent;
                                border: none;
                                color: #D1D5DB;
                                font-size: 14px;
                                font-weight: 600;
                                font-family: 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                                cursor: pointer;
                                padding: 4px 0;
                                transition: color 0.18s ease;
                            ">
                                ログアウト
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav-link-hover" style="
                            color: #D1D5DB;
                            text-decoration: none;
                            font-size: 14px;
                            font-weight: 600;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            padding: 4px 0;
                            transition: color 0.18s ease;
                        ">
                            ログイン
                        </a>
                        <a href="{{ route('register') }}" style="
                            background: #90AFC5;
                            color: #2A3132;
                            text-decoration: none;
                            font-size: 14px;
                            font-weight: 700;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                            padding: 8px 20px;
                            border-radius: 999px;
                            border: 1px solid transparent;
                            transition: all 0.18s ease;
                        " onmouseover="this.style.borderColor='#ffffff'; this.style.boxShadow='0 0 0 1px rgba(255,255,255,0.6)';" onmouseout="this.style.borderColor='transparent'; this.style.boxShadow='none';">
                            会員登録
                        </a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="site-main">
            <div class="container main-inner">
                <aside class="sidebar">
                    @yield('sidebar')
                </aside>

                    <section class="content">
                        @if (session('status'))
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
            border-top: 1px solid #e2e8f0;
            background-color: #ffffff;
            margin-top: auto;
            padding: 24px 0;
        ">
            <div style="
                max-width: 1080px;
                margin: 0 auto;
                padding: 0 24px;
                text-align: center;
            ">
                <p style="
                    margin: 0;
                    font-size: 12px;
                    color: #64748b;
                    font-weight: 500;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">&copy; {{ date('Y') }} Bellbi. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>


