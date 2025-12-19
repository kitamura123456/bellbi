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
        }
        .brand-name, .brand-sub {
            color: #ffffff !important;
            font-weight: 700;
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
        }
        .main-nav a {
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 500;
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            position: relative;
            transition: all 0.2s ease;
        }
        .main-nav a:hover {
            color: #90AFC5 !important;
        }
        .main-nav a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 1px;
            background: #ffffff;
            transition: width 0.2s ease;
        }
        .main-nav a:hover::after {
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
                    <a href="{{ route('company.dashboard') }}" style="color: #ffffff; text-decoration: none; font-weight: 500; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif; position: relative; transition: all 0.2s ease;" onmouseover="this.style.color='#90AFC5';" onmouseout="this.style.color='#ffffff';">
                        ダッシュボード
                    </a>
                    <a href="{{ route('company.info') }}" style="color: #ffffff; text-decoration: none; font-weight: 500; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif; position: relative; transition: all 0.2s ease;" onmouseover="this.style.color='#90AFC5';" onmouseout="this.style.color='#ffffff';">
                        会社情報
                    </a>
                    <a href="{{ route('company.stores.index') }}" style="color: #ffffff; text-decoration: none; font-weight: 500; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif; position: relative; transition: all 0.2s ease;" onmouseover="this.style.color='#90AFC5';" onmouseout="this.style.color='#ffffff';">
                        店舗管理
                    </a>
                    <a href="{{ route('company.job-posts.index') }}" style="color: #ffffff; text-decoration: none; font-weight: 500; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif; position: relative; transition: all 0.2s ease;" onmouseover="this.style.color='#90AFC5';" onmouseout="this.style.color='#ffffff';">
                        求人管理
                    </a>
                    <a href="{{ route('company.applications.index') }}" style="color: #ffffff; text-decoration: none; font-weight: 500; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif; position: relative; transition: all 0.2s ease;" onmouseover="this.style.color='#90AFC5';" onmouseout="this.style.color='#ffffff';">
                        応募管理
                    </a>
                    <a href="{{ route('company.scouts.search') }}" style="color: #ffffff; text-decoration: none; font-weight: 500; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif; position: relative; transition: all 0.2s ease;" onmouseover="this.style.color='#90AFC5';" onmouseout="this.style.color='#ffffff';">
                        スカウト
                    </a>
                    <a href="{{ route('company.messages.index') }}" style="color: #ffffff; text-decoration: none; font-weight: 500; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif; position: relative; transition: all 0.2s ease;" onmouseover="this.style.color='#90AFC5';" onmouseout="this.style.color='#ffffff';">
                        メッセージ
                    </a>
                    <a href="{{ route('company.reservations.index') }}" style="color: #ffffff; text-decoration: none; font-weight: 500; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif; position: relative; transition: all 0.2s ease;" onmouseover="this.style.color='#90AFC5';" onmouseout="this.style.color='#ffffff';">
                        予約管理
                    </a>
                    <a href="{{ route('company.transactions.index') }}" style="color: #ffffff; text-decoration: none; font-weight: 500; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif; position: relative; transition: all 0.2s ease;" onmouseover="this.style.color='#90AFC5';" onmouseout="this.style.color='#ffffff';">
                        売上・経費
                    </a>
                    <a href="{{ route('company.shops.index') }}" style="color: #ffffff; text-decoration: none; font-weight: 500; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif; position: relative; transition: all 0.2s ease;" onmouseover="this.style.color='#90AFC5';" onmouseout="this.style.color='#ffffff';">
                        ECショップ
                    </a>
                    <a href="{{ route('company.products.index') }}" style="color: #ffffff; text-decoration: none; font-weight: 500; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif; position: relative; transition: all 0.2s ease;" onmouseover="this.style.color='#90AFC5';" onmouseout="this.style.color='#ffffff';">
                        商品管理
                    </a>
                    <a href="{{ route('company.orders.index') }}" style="color: #ffffff; text-decoration: none; font-weight: 500; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif; position: relative; transition: all 0.2s ease;" onmouseover="this.style.color='#90AFC5';" onmouseout="this.style.color='#ffffff';">
                        受注管理
                    </a>
                    <a href="{{ route('jobs.index') }}" style="color: #ffffff; text-decoration: none; font-weight: 500; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif; position: relative; transition: all 0.2s ease;" onmouseover="this.style.color='#90AFC5';" onmouseout="this.style.color='#ffffff';">
                        フロントを見る
                    </a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #ffffff; text-decoration: none; font-weight: 500; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif; position: relative; transition: all 0.2s ease;" onmouseover="this.style.color='#90AFC5';" onmouseout="this.style.color='#ffffff';">
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

