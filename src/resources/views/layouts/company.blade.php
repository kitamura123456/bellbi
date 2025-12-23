<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>@yield('title', '事業者ダッシュボード') - Bellbi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        .site-header {
            background: #5D535E !important;
            border-bottom: 1px solid #4a444d;
            box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
        }
        .brand-link {
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 700;
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
        }
        .brand-mark {
            color: #ffffff !important;
            font-weight: 700;
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            background: linear-gradient(135deg, #5D535E, #6B5F6E) !important;
            box-shadow: 0 2px 8px rgba(93, 83, 94, 0.3) !important;
        }
        .brand-name, .brand-sub {
            color: #ffffff !important;
            font-weight: 700;
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
        }
        .main-nav {
            display: flex;
            align-items: center;
            gap: 24px;
            flex-wrap: wrap;
        }
        .main-nav .nav-link {
            color: #ffffff !important;
            text-decoration: none;
            font-size: 13px;
            font-weight: 400;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            padding: 4px 0;
            transition: color 0.3s ease;
            letter-spacing: 0.02em;
            position: relative;
        }
        .main-nav .nav-link:not(.active):hover {
            color: #90AFC5 !important;
        }
        .main-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: #ffffff;
            transition: width 0.3s ease;
        }
        .main-nav .nav-link:not(.active):hover::after {
            width: 100%;
        }
        .main-nav .nav-link.active {
            color: #90AFC5 !important;
        }
        .main-nav .nav-link.active::after {
            width: 100%;
        }
        .main-nav .nav-link-external:hover {
            color: #90AFC5 !important;
        }
        .main-nav .nav-link-external:hover::after {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <header class="site-header company-header-bg" style="background: #5D535E; border-bottom: 1px solid #4a444d; box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);">
            <div class="container header-inner">
                <div class="branding">
                    <a href="{{ route('company.dashboard') }}" class="brand-link" style="color: #ffffff; text-decoration: none; font-weight: 700; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
                        <span class="brand-mark" style="color: #ffffff; font-weight: 700; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">B</span>
                        <span class="brand-text">
                            <span class="brand-name" style="color: #ffffff; font-weight: 700; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">Bellbi 店舗版</span>
                            <span class="brand-sub" style="color: #ffffff; font-weight: 700; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">Business Dashboard</span>
                        </span>
                    </a>
                </div>
                <nav class="main-nav">
                    <a href="{{ route('company.dashboard') }}" class="nav-link {{ request()->routeIs('company.dashboard') ? 'active' : '' }}">
                        ダッシュボード
                    </a>
                    <a href="{{ route('company.info') }}" class="nav-link {{ request()->routeIs('company.info*') ? 'active' : '' }}">
                        会社情報
                    </a>
                    <a href="{{ route('company.stores.index') }}" class="nav-link {{ request()->routeIs('company.stores*') ? 'active' : '' }}">
                        店舗管理
                    </a>
                    <a href="{{ route('company.staffs.index') }}" class="nav-link {{ request()->routeIs('company.staffs*') ? 'active' : '' }}">
                        スタッフ管理
                    </a>
                    <a href="{{ route('company.menus.index') }}" class="nav-link {{ request()->routeIs('company.menus*') ? 'active' : '' }}">
                        メニュー管理
                    </a>
                    <a href="{{ route('company.schedules.index') }}" class="nav-link {{ request()->routeIs('company.schedules*') ? 'active' : '' }}">
                        営業スケジュール
                    </a>
                    <a href="{{ route('company.job-posts.index') }}" class="nav-link {{ request()->routeIs('company.job-posts*') ? 'active' : '' }}">
                        求人管理
                    </a>
                    <a href="{{ route('company.applications.index') }}" class="nav-link {{ request()->routeIs('company.applications*') ? 'active' : '' }}">
                        応募管理
                    </a>
                    <a href="{{ route('company.scouts.search') }}" class="nav-link {{ request()->routeIs('company.scouts*') ? 'active' : '' }}">
                        スカウト
                    </a>
                    <a href="{{ route('company.messages.index') }}" class="nav-link {{ request()->routeIs('company.messages*') ? 'active' : '' }}">
                        メッセージ
                    </a>
                    <a href="{{ route('company.reservations.index') }}" class="nav-link {{ request()->routeIs('company.reservations*') ? 'active' : '' }}">
                        予約管理
                    </a>
                    <a href="{{ route('company.transactions.index') }}" class="nav-link {{ request()->routeIs('company.transactions*') || request()->routeIs('company.account-items*') ? 'active' : '' }}">
                        売上・経費
                    </a>
                    <a href="{{ route('company.plans.index') }}" class="nav-link {{ request()->routeIs('company.plans*') ? 'active' : '' }}">
                        プラン管理
                    </a>
                    <a href="{{ route('company.shops.index') }}" class="nav-link {{ request()->routeIs('company.shops*') || request()->routeIs('company.products*') || request()->routeIs('company.orders*') ? 'active' : '' }}">
                        ECショップ
                    </a>
                    <a href="{{ route('jobs.index') }}" target="_blank" class="nav-link nav-link-external">
                        フロントを見る
                    </a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link nav-link-external">
                        ログアウト
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </nav>
            </div>
        </header>

        <main class="site-main company-main">
            <div class="container company-container">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <footer class="site-footer">
            <div class="container footer-inner">
                <p class="footer-copy">&copy; {{ date('Y') }} Bellbi. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>

