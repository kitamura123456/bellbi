<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>@yield('title', '管理画面') - Bellbi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            background: #f0f0f1;
            color: #1a1a1a;
        }
        
        .page-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        /* ヘッダー */
        .wp-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 32px;
            background: #23282d;
            border-bottom: 1px solid #32373c;
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 16px;
        }
        
        .wp-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
        }
        
        .wp-header-logo {
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .wp-header-logo-icon {
            width: 20px;
            height: 20px;
            background: #d63638;
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 12px;
        }
        
        .wp-header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .wp-header-link {
            color: #a7aaad;
            text-decoration: none;
            font-size: 13px;
            padding: 4px 8px;
            border-radius: 3px;
            transition: all 0.2s;
        }
        
        .wp-header-link:hover {
            color: #fff;
            background: #32373c;
        }
        
        .wp-header-user {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #a7aaad;
            font-size: 13px;
        }
        
        .wp-header-user-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #d63638;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 11px;
            font-weight: 600;
        }
        
        /* メインコンテナ */
        .wp-body {
            display: flex;
            margin-top: 32px;
            min-height: calc(100vh - 32px);
        }
        
        @media (max-width: 782px) {
            .wp-body {
                margin-top: 56px;
                min-height: calc(100vh - 56px);
            }
        }
        
        @media (max-width: 480px) {
            .wp-body {
                margin-top: 60px;
                min-height: calc(100vh - 60px);
            }
        }
        
        /* サイドバー */
        .wp-sidebar {
            position: fixed;
            left: 0;
            top: 32px;
            bottom: 0;
            width: 160px;
            background: #23282d !important;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 999;
            transition: transform 0.3s ease;
            opacity: 1 !important;
        }
        
        .wp-sidebar::-webkit-scrollbar {
            width: 4px;
        }
        
        .wp-sidebar::-webkit-scrollbar-track {
            background: #1d2327;
        }
        
        .wp-sidebar::-webkit-scrollbar-thumb {
            background: #3c434a;
            border-radius: 2px;
        }
        
        .wp-sidebar-menu {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .wp-sidebar-menu-item {
            margin: 0;
        }
        
        .wp-sidebar-menu-link {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            color: #b4b9be;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.2s;
            border-left: 4px solid transparent;
            background: transparent;
            opacity: 1;
        }
        
        .wp-sidebar-menu-link:hover {
            color: #fff;
            background: #32373c;
            opacity: 1;
        }
        
        .wp-sidebar-menu-link.active {
            color: #fff;
            background: #d63638;
            border-left-color: #b32d2e;
            opacity: 1;
        }
        
        .wp-sidebar-menu-link-icon {
            width: 20px;
            height: 20px;
            margin-right: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }
        
        .wp-sidebar-menu-link-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }
        
        .wp-sidebar-menu-separator {
            height: 1px;
            background: #32373c;
            margin: 8px 0;
        }
        
        .wp-sidebar-menu-title {
            padding: 8px 12px;
            color: #8c8f94;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            user-select: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: transparent;
            opacity: 1;
        }
        
        .wp-sidebar-menu-title:hover {
            color: #b4b9be;
        }
        
        .wp-sidebar-menu-title::after {
            content: '▼';
            font-size: 8px;
            transition: transform 0.2s;
        }
        
        .wp-sidebar-menu-title.collapsed::after {
            transform: rotate(-90deg);
        }
        
        .wp-sidebar-menu-group {
            list-style: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            max-height: 2000px;
        }
        
        .wp-sidebar-menu-group.collapsed {
            max-height: 0;
        }
        
        @media (max-width: 782px) {
            .wp-sidebar-menu-group {
                max-height: 0;
            }
            
            .wp-sidebar-menu-group:not(.collapsed) {
                max-height: 2000px;
            }
            
            .wp-sidebar-menu-title.collapsed + .wp-sidebar-menu-group {
                max-height: 0;
            }
        }
        
        /* メインコンテンツ */
        .wp-content {
            margin-left: 160px;
            flex: 1;
            padding: 20px;
            padding-top: 52px;
            background: #f0f0f1;
            min-height: calc(100vh - 32px);
        }
        
        .wp-content-header {
            margin-bottom: 20px;
        }
        
        .wp-content-header h1 {
            margin: 0;
            font-size: 23px;
            font-weight: 400;
            color: #1a1a1a;
            padding: 0;
        }
        
        .wp-content-body {
            background: #fff;
            border: 1px solid #c3c4c7;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
            padding: 20px;
        }
        
        /* モバイル対応 */
        .wp-sidebar-toggle {
            display: none;
            position: fixed;
            top: 4px;
            left: 8px;
            z-index: 1001;
            background: #d63638;
            border: none;
            color: #fff;
            width: 24px;
            height: 24px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
        }
        
        @media (max-width: 782px) {
            .wp-sidebar-toggle {
                display: block;
                top: 8px;
                left: 12px;
                width: 40px;
                height: 40px;
                font-size: 20px;
                padding: 0;
            }
            
            .wp-sidebar {
                transform: translateX(-100%);
                width: 300px;
                top: 56px;
                background: #23282d !important;
                box-shadow: 2px 0 8px rgba(0,0,0,0.3);
                opacity: 1 !important;
            }
            
            .wp-sidebar.open {
                transform: translateX(0);
            }
            
            .wp-sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 998;
            }
            
            .wp-sidebar-overlay.open {
                display: block;
            }
            
            .wp-content {
                margin-left: 0;
                padding: 12px;
                padding-top: 68px;
            }
            
            .wp-header {
                height: 56px;
                padding: 0 16px;
            }
            
            .wp-header-logo {
                font-size: 16px;
            }
            
            .wp-header-logo-icon {
                width: 28px;
                height: 28px;
                font-size: 16px;
            }
            
            .wp-header-logo span:not(.wp-header-logo-icon) {
                display: none;
            }
            
            .wp-header-right {
                gap: 8px;
            }
            
            .wp-header-link {
                font-size: 12px;
                padding: 6px 8px;
            }
            
            .wp-header-link:first-of-type {
                display: none;
            }
            
            .wp-header-user {
                font-size: 13px;
            }
            
            .wp-header-user-avatar {
                width: 32px;
                height: 32px;
                font-size: 14px;
            }
            
            .wp-header-user span {
                display: none;
            }
            
            .wp-content-header h1 {
                font-size: 20px;
            }
            
            .wp-content-body {
                padding: 16px;
            }
            
            .wp-sidebar-menu-link {
                padding: 16px 20px;
                font-size: 16px;
            }
            
            .wp-sidebar-menu-link-icon {
                width: 28px;
                height: 28px;
                font-size: 20px;
                margin-right: 14px;
            }
            
            .wp-sidebar-menu-title {
                padding: 16px 20px;
                font-size: 15px;
                font-weight: 700;
            }
        }
        
        @media (max-width: 480px) {
            .wp-sidebar-toggle {
                top: 10px;
                left: 12px;
                width: 44px;
                height: 44px;
                font-size: 22px;
            }
            
            .wp-content {
                padding: 8px;
                padding-top: 72px;
            }
            
            .wp-header {
                height: 60px;
                padding: 0 12px;
            }
            
            .wp-header-logo {
                font-size: 18px;
            }
            
            .wp-header-logo-icon {
                width: 32px;
                height: 32px;
                font-size: 18px;
            }
            
            .wp-header-link {
                font-size: 11px;
                padding: 6px 10px;
            }
            
            .wp-header-link:first-of-type {
                display: none;
            }
            
            .wp-header-user-avatar {
                width: 36px;
                height: 36px;
                font-size: 16px;
            }
            
            .wp-header-user span {
                display: none;
            }
            
            .wp-content-body {
                padding: 12px;
            }
            
            .wp-sidebar {
                width: 100%;
                top: 60px;
                background: #23282d !important;
                opacity: 1 !important;
            }
            
            .wp-sidebar-menu-link {
                padding: 18px 20px;
                font-size: 17px;
            }
            
            .wp-sidebar-menu-link-icon {
                width: 30px;
                height: 30px;
                font-size: 22px;
                margin-right: 16px;
            }
            
            .wp-sidebar-menu-title {
                padding: 18px 20px;
                font-size: 16px;
            }
        }
        
        /* アラート */
        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-left: 4px solid;
            background: #fff;
        }
        
        .alert-success {
            border-left-color: #00a32a;
            background: #f0f6fc;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <!-- ヘッダー -->
        <header class="wp-header">
            <button class="wp-sidebar-toggle" onclick="toggleSidebar()">☰</button>
            <div class="wp-header-left">
                <a href="{{ route('admin.index') }}" class="wp-header-logo">
                    <span class="wp-header-logo-icon">B</span>
                    <span>Bellbi 管理画面</span>
                </a>
            </div>
            <div class="wp-header-right">
                <a href="{{ route('jobs.index') }}" target="_blank" class="wp-header-link">サイトを見る</a>
                <div class="wp-header-user">
                    <span>{{ Auth::user()->name ?? '管理者' }}</span>
                </div>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="wp-header-link">ログアウト</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </header>

        <!-- メインコンテナ -->
        <div class="wp-body">
            <!-- サイドバーオーバーレイ（モバイル用） -->
            <div class="wp-sidebar-overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>
            
            <!-- サイドバー -->
            <aside class="wp-sidebar" id="sidebar">
                <ul class="wp-sidebar-menu">
                    <li class="wp-sidebar-menu-item">
                        <a href="{{ route('admin.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                            <span class="wp-sidebar-menu-link-icon"><img src="{{ asset('images/logos/ダッシュボード.svg') }}" alt="ダッシュボード"></span>
                            <span>ダッシュボード</span>
                        </a>
                    </li>
                    
                    <li class="wp-sidebar-menu-item">
                        <div class="wp-sidebar-menu-separator"></div>
                    </li>
                    
                    <li class="wp-sidebar-menu-item">
                        <div class="wp-sidebar-menu-title" onclick="toggleMenuGroup(this)">ユーザー・事業者</div>
                        <ul class="wp-sidebar-menu-group">
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('admin.users.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon"><img src="{{ asset('images/logos/スタッフ管理.svg') }}" alt="ユーザー管理"></span>
                                    <span>ユーザー管理</span>
                                </a>
                            </li>
                            
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('admin.companies.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('admin.companies*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon"><img src="{{ asset('images/logos/会社情報.svg') }}" alt="事業者管理"></span>
                                    <span>事業者管理</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="wp-sidebar-menu-item">
                        <div class="wp-sidebar-menu-separator"></div>
                    </li>
                    
                    <li class="wp-sidebar-menu-item">
                        <div class="wp-sidebar-menu-title" onclick="toggleMenuGroup(this)">求人・応募</div>
                        <ul class="wp-sidebar-menu-group">
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('admin.job-posts.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('admin.job-posts*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon"><img src="{{ asset('images/logos/求人管理.svg') }}" alt="求人管理"></span>
                                    <span>求人管理</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="wp-sidebar-menu-item">
                        <div class="wp-sidebar-menu-separator"></div>
                    </li>
                    
                    <li class="wp-sidebar-menu-item">
                        <div class="wp-sidebar-menu-title" onclick="toggleMenuGroup(this)">システム設定</div>
                        <ul class="wp-sidebar-menu-group">
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('admin.plans.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('admin.plans*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon"><img src="{{ asset('images/logos/プラン管理.svg') }}" alt="プラン管理"></span>
                                    <span>プラン管理</span>
                                </a>
                            </li>
                            
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('admin.subsidies.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('admin.subsidies*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon"><img src="{{ asset('images/logos/補助金情報.svg') }}" alt="補助金情報管理"></span>
                                    <span>補助金情報管理</span>
                                </a>
                            </li>

                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('admin.logs.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('admin.logs*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon"><img src="{{ asset('images/logos/プログラムアイコン.svg') }}" alt="ログ管理"></span>
                                    <span>ログ管理</span>
                                </a>
                            </li>
                            
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('admin.system-settings.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('admin.system-settings*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon"><img src="{{ asset('images/logos/システム設定.svg') }}" alt="システム設定"></span>
                                    <span>システム設定</span>
                                </a>
                            </li>
                            
                        </ul>
                    </li>
                </ul>
            </aside>

            <!-- メインコンテンツ -->
            <main class="wp-content">
                <div class="wp-content-header">
                    <h1>@yield('title', 'ダッシュボード')</h1>
                </div>
                
                <div class="wp-content-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('open');
            if (overlay) {
                overlay.classList.toggle('open');
            }
        }
        
        function toggleMenuGroup(element) {
            const group = element.nextElementSibling;
            if (group && group.classList.contains('wp-sidebar-menu-group')) {
                element.classList.toggle('collapsed');
                group.classList.toggle('collapsed');
            }
        }
        
        // モバイルでサイドバー外をクリックしたら閉じる
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const toggle = document.querySelector('.wp-sidebar-toggle');
            
            if (window.innerWidth <= 782) {
                if (sidebar.classList.contains('open') && 
                    !sidebar.contains(event.target) && 
                    !toggle.contains(event.target)) {
                    sidebar.classList.remove('open');
                    if (overlay) {
                        overlay.classList.remove('open');
                    }
                }
            }
        });
        
        // アクティブなメニューグループを自動展開
        document.addEventListener('DOMContentLoaded', function() {
            const activeLink = document.querySelector('.wp-sidebar-menu-link.active');
            if (activeLink) {
                const menuGroup = activeLink.closest('.wp-sidebar-menu-group');
                if (menuGroup) {
                    const title = menuGroup.previousElementSibling;
                    if (title && title.classList.contains('wp-sidebar-menu-title')) {
                        title.classList.remove('collapsed');
                        menuGroup.classList.remove('collapsed');
                    }
                }
            }
        });
    </script>
</body>
</html>
