<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>@yield('title', '管理画面') - Bellbi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <div class="page-wrapper">
        <header class="site-header admin-header-bg">
            <div class="container header-inner">
                <div class="branding">
                    <a href="{{ route('admin.index') }}" class="brand-link">
                        <span class="brand-mark">B</span>
                        <span class="brand-text">
                            <span class="brand-name">Bellbi 管理画面</span>
                            <span class="brand-sub">Admin Dashboard</span>
                        </span>
                    </a>
                </div>
                <nav class="main-nav">
                    <a href="{{ route('admin.index') }}">ダッシュボード</a>
                    <a href="{{ route('admin.users.index') }}">ユーザー管理</a>
                    <a href="{{ route('admin.plans.index') }}">プラン管理</a>
                    <a href="{{ route('jobs.index') }}">フロントを見る</a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </nav>
            </div>
        </header>

        <main class="site-main admin-main">
            <div class="container admin-container">
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

