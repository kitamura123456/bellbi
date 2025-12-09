<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Bellbi')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- MAMP 環境で /bellbi/ 配下から確実に読み込めるように、パスを明示的に指定 --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <div class="page-wrapper">
        <header class="site-header">
            <div class="container header-inner">
                <div class="branding">
                    <a href="{{ route('jobs.index') }}" class="brand-link">
                        <span class="brand-mark">B</span>
                        <span class="brand-text">
                            <span class="brand-name">Bellbi</span>
                            <span class="brand-sub">Beauty Career & Salon Support</span>
                        </span>
                    </a>
                </div>
                <nav class="main-nav">
                    <a href="{{ route('jobs.index') }}">求人を探す</a>
                    <a href="{{ route('reservations.search') }}">予約する</a>

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

                        <form method="post" action="{{ route('logout') }}" class="nav-logout-form">
                            @csrf
                            <button type="submit" class="nav-link-button">ログアウト</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}">ログイン</a>
                        <a href="{{ route('register') }}">会員登録</a>
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
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @yield('content')
                    </section>
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


