<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>@yield('title', '事業者ダッシュボード') - Bellbi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/bellbi/css/app.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <div class="page-wrapper">
        <header class="site-header company-header-bg">
            <div class="container header-inner">
                <div class="branding">
                    <a href="{{ route('company.dashboard') }}" class="brand-link">
                        <span class="brand-mark">B</span>
                        <span class="brand-text">
                            <span class="brand-name">Bellbi 事業者</span>
                            <span class="brand-sub">Business Dashboard</span>
                        </span>
                    </a>
                </div>
                <nav class="main-nav">
                    <a href="{{ route('company.dashboard') }}">ダッシュボード</a>
                    <a href="{{ route('company.info') }}">会社情報</a>
                    <a href="{{ route('company.stores.index') }}">店舗管理</a>
                    <a href="{{ route('company.job-posts.index') }}">求人管理</a>
                    <a href="{{ route('company.applications.index') }}">応募管理</a>
                    <a href="{{ route('company.scouts.search') }}">スカウト</a>
                    <a href="{{ route('company.reservations.index') }}">予約管理</a>
                    <a href="{{ route('jobs.index') }}">フロントを見る</a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
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

