<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Bellbi')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            color: #1a1a1a !important;
        }
        .nav-link-hover:hover::after {
            width: 100%;
        }
        .main-nav a::after {
            background-color: #1a1a1a !important;
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
                padding: 16px 48px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            " class="header-inner">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <button class="hamburger-menu-btn" style="
                        display: none;
                        background: transparent;
                        border: none;
                        cursor: pointer;
                        padding: 8px;
                        flex-direction: column;
                        gap: 4px;
                        align-items: center;
                        justify-content: center;
                    " onclick="toggleMobileMenu()" aria-label="メニュー">
                        <span class="hamburger-line" style="
                            display: block;
                            width: 24px;
                            height: 2px;
                            background: #1a1a1a;
                            transition: all 0.3s ease;
                        "></span>
                        <span class="hamburger-line" style="
                            display: block;
                            width: 24px;
                            height: 2px;
                            background: #1a1a1a;
                            transition: all 0.3s ease;
                        "></span>
                        <span class="hamburger-line" style="
                            display: block;
                            width: 24px;
                            height: 2px;
                            background: #1a1a1a;
                            transition: all 0.3s ease;
                        "></span>
                    </button>
                    <a href="{{ route('jobs.index') }}" style="
                        display: flex;
                        align-items: center;
                        text-decoration: none;
                        color: #1a1a1a;
                    ">
                        <img src="{{ asset('images/Black Beige Minimal Simple Elegant   Art Design  Creative Studio Gaze Logo (1).png') }}" alt="Bellbi" style="height: 50px; width: auto;">
                    </a>
                </div>
                <nav class="main-nav" style="
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
            <div class="container main-inner" style="padding: 48px 48px; display: flex; gap: 48px; justify-content: flex-start; align-items: flex-start; max-width: none; margin: 0;">
                <aside class="sidebar" style="min-width: 320px; flex-shrink: 0; position: relative;">
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
        <style>
            /* モバイルメニューのオーバーレイ */
            .mobile-menu-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 998;
            }
            .mobile-menu-overlay.active {
                display: block;
            }
            
            /* モバイルメニュー */
            .mobile-menu {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 280px;
                height: 100vh;
                background: #ffffff;
                z-index: 999;
                overflow-y: auto;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            }
            .mobile-menu.active {
                transform: translateX(0);
            }
            .mobile-menu-header {
                padding: 16px;
                border-bottom: 1px solid #f0f0f0;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .mobile-menu-close {
                background: transparent;
                border: none;
                font-size: 24px;
                cursor: pointer;
                color: #1a1a1a;
                padding: 0;
                width: 32px;
                height: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .mobile-menu-content {
                padding: 16px;
            }
            .mobile-menu-content a {
                display: block;
                padding: 12px 0;
                color: #1a1a1a;
                text-decoration: none;
                font-size: 14px;
                border-bottom: 1px solid #f0f0f0;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            }
            .mobile-menu-content a:last-child {
                border-bottom: none;
            }
            .mobile-menu-content form {
                margin-top: 12px;
            }
            .mobile-menu-content button {
                width: 100%;
                padding: 12px;
                background: #1a1a1a;
                color: #ffffff;
                border: none;
                border-radius: 0;
                font-size: 12px;
                font-weight: 500;
                cursor: pointer;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            
            @media (max-width: 1024px) {
                .header-inner {
                    padding: 16px 24px !important;
                }
                .container.main-inner {
                    flex-direction: column !important;
                    gap: 32px !important;
                    padding: 32px 20px !important;
                }
                .sidebar {
                    min-width: 100% !important;
                    width: 100% !important;
                }
            }
            @media (max-width: 768px) {
                .container.main-inner {
                    padding: 24px 16px !important;
                    gap: 0 !important;
                }
                .hamburger-menu-btn {
                    display: flex !important;
                }
                .main-nav {
                    display: none !important;
                }
                .header-inner {
                    padding: 12px 16px !important;
                }
                .header-inner img {
                    height: 40px !important;
                }
                .sidebar {
                    order: -1 !important;
                    margin-bottom: 0 !important;
                }
            }
            @media (max-width: 480px) {
                .container.main-inner {
                    padding: 16px 12px !important;
                }
                .header-inner {
                    padding: 10px 12px !important;
                }
                .header-inner img {
                    height: 36px !important;
                }
                .mobile-menu {
                    width: 260px;
                }
            }
            
            /* ハンバーガーメニューアニメーション */
            .hamburger-menu-btn.active .hamburger-line:nth-child(1) {
                transform: rotate(45deg) translate(6px, 6px);
            }
            .hamburger-menu-btn.active .hamburger-line:nth-child(2) {
                opacity: 0;
            }
            .hamburger-menu-btn.active .hamburger-line:nth-child(3) {
                transform: rotate(-45deg) translate(6px, -6px);
            }
        </style>
        
        <!-- モバイルメニューオーバーレイ -->
        <div class="mobile-menu-overlay" onclick="closeMobileMenu()"></div>
        
        <!-- モバイルメニュー -->
        <div class="mobile-menu" id="mobileMenu">
            <div class="mobile-menu-header">
                <span style="font-size: 14px; font-weight: 600; color: #1a1a1a;">メニュー</span>
                <button class="mobile-menu-close" onclick="closeMobileMenu()" aria-label="閉じる">×</button>
            </div>
            <div class="mobile-menu-content">
                <a href="{{ route('jobs.index') }}">求人を探す</a>
                <a href="{{ route('reservations.search') }}">予約する</a>
                <a href="{{ route('shops.index') }}">ショップ</a>
                <a href="{{ route('cart.index') }}">カート</a>
                @auth
                    @php
                        /** @var \App\Models\User $user */
                        $user = auth()->user();
                    @endphp
                    @if ($user->role === \App\Models\User::ROLE_PERSONAL)
                        <a href="{{ route('mypage') }}">マイページ</a>
                    @elseif ($user->role === \App\Models\User::ROLE_COMPANY)
                        <a href="{{ route('company.dashboard') }}">事業者ダッシュボード</a>
                    @elseif ($user->role === \App\Models\User::ROLE_ADMIN)
                        <a href="{{ route('admin.index') }}">管理画面</a>
                    @endif
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">ログアウト</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">ログイン</a>
                    <a href="{{ route('register') }}">会員登録</a>
                @endauth
            </div>
        </div>
        
        <script>
            function toggleMobileMenu() {
                const menu = document.getElementById('mobileMenu');
                const overlay = document.querySelector('.mobile-menu-overlay');
                const btn = document.querySelector('.hamburger-menu-btn');
                
                if (menu && overlay && btn) {
                    const isActive = menu.classList.contains('active');
                    if (isActive) {
                        menu.classList.remove('active');
                        overlay.classList.remove('active');
                        btn.classList.remove('active');
                        document.body.style.overflow = '';
                    } else {
                        menu.style.display = 'block';
                        menu.style.visibility = 'visible';
                        setTimeout(() => {
                            menu.classList.add('active');
                            overlay.classList.add('active');
                            btn.classList.add('active');
                        }, 10);
                        document.body.style.overflow = 'hidden';
                    }
                }
            }
            
            function closeMobileMenu() {
                const menu = document.getElementById('mobileMenu');
                const overlay = document.querySelector('.mobile-menu-overlay');
                const btn = document.querySelector('.hamburger-menu-btn');
                
                if (menu && overlay && btn) {
                    menu.classList.remove('active');
                    overlay.classList.remove('active');
                    btn.classList.remove('active');
                    document.body.style.overflow = '';
                    
                    // アニメーション後に非表示にする
                    setTimeout(() => {
                        menu.style.display = 'none';
                        menu.style.visibility = 'hidden';
                    }, 300);
                }
            }
            
            // メニュー内のリンクをクリックしたらメニューを閉じる
            document.addEventListener('DOMContentLoaded', function() {
                const menuLinks = document.querySelectorAll('.mobile-menu-content a');
                menuLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        setTimeout(closeMobileMenu, 100);
                    });
                });
            });
        </script>

        <footer style="
            border-top: 1px solid #f0f0f0;
            background-color: #ffffff;
            margin-top: auto;
            padding: 32px 0;
        ">
            <div style="
                padding: 0 48px;
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


