<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>@yield('title', '事業者ダッシュボード') - Bellbi</title>
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
            background: #2271b1;
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
            background: #2271b1;
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
            background: #2271b1;
            border-left-color: #135e96;
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
            background: #2271b1;
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
                gap: 10px;
            }
            
            .wp-header-link {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .wp-header-user {
                font-size: 14px;
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
            
            .wp-badge {
                min-width: 22px;
                height: 22px;
                font-size: 13px;
                line-height: 22px;
                padding: 0 7px;
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
                font-size: 12px;
                padding: 8px 12px;
            }
            
            .wp-header-user-avatar {
                width: 36px;
                height: 36px;
                font-size: 16px;
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
            
            .wp-badge {
                min-width: 24px;
                height: 24px;
                font-size: 14px;
                line-height: 24px;
                padding: 0 8px;
            }
        }
        
        /* 通知バッジ */
        .wp-badge {
            display: inline-block;
            min-width: 18px;
            height: 18px;
            padding: 0 6px;
            background: #d63638;
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            line-height: 18px;
            text-align: center;
            border-radius: 9px;
            margin-left: 4px;
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
                <a href="{{ route('company.dashboard') }}" class="wp-header-logo">
                    <span class="wp-header-logo-icon">B</span>
                    <span>Bellbi</span>
                </a>
            </div>
            <div class="wp-header-right">
                <a href="{{ route('jobs.index') }}" target="_blank" class="wp-header-link">サイトを見る</a>
                <div class="wp-header-user">
                    <span>{{ Auth::user()->name ?? 'ユーザー' }}</span>
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
                        <a href="{{ route('company.dashboard') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.dashboard') ? 'active' : '' }}">
                            <span class="wp-sidebar-menu-link-icon">📊</span>
                            <span>ダッシュボード</span>
                        </a>
                    </li>
                    
                    <li class="wp-sidebar-menu-item">
                        <div class="wp-sidebar-menu-separator"></div>
                    </li>
                    
                    <li class="wp-sidebar-menu-item">
                        <div class="wp-sidebar-menu-title" onclick="toggleMenuGroup(this)">売上・注文</div>
                        <ul class="wp-sidebar-menu-group">
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('company.transactions.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.transactions*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon">💰</span>
                                    <span>売上・経費</span>
                                </a>
                            </li>
                            
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('company.transactions.report') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.transactions.report') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon">📈</span>
                                    <span>月次レポート</span>
                                </a>
                            </li>
                            
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('company.account-items.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.account-items*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon">📋</span>
                                    <span>科目マスタ</span>
                                </a>
                            </li>
                            
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('company.orders.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.orders*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon">📦</span>
                                    <span>受注管理</span>
                                    <span id="sidebar-orders-badge" class="wp-badge" style="display: {{ isset($sidebarStats) && $sidebarStats['newOrdersCount'] > 0 ? 'inline-block' : 'none' }};">
                                        {{ isset($sidebarStats) ? $sidebarStats['newOrdersCount'] : 0 }}
                                    </span>
                                </a>
                            </li>
                            
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('company.shops.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.shops*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon">🛒</span>
                                    <span>ECショップ管理</span>
                                </a>
                            </li>
                            
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('company.products.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.products*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon">📦</span>
                                    <span>商品管理</span>
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
                                <a href="{{ route('company.job-posts.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.job-posts*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon">💼</span>
                                    <span>求人管理</span>
                                </a>
                            </li>
                            
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('company.applications.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.applications*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon">📝</span>
                                    <span>応募管理</span>
                                    <span id="sidebar-applications-badge" class="wp-badge" style="display: {{ isset($sidebarStats) && $sidebarStats['newApplicationsCount'] > 0 ? 'inline-block' : 'none' }};">
                                        {{ isset($sidebarStats) ? $sidebarStats['newApplicationsCount'] : 0 }}
                                    </span>
                                </a>
                            </li>
                            
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('company.scouts.search') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.scouts*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon">🔍</span>
                                    <span>スカウト</span>
                                </a>
                            </li>
                            
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('company.messages.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.messages*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon">💬</span>
                                    <span>メッセージ</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="wp-sidebar-menu-item">
                        <div class="wp-sidebar-menu-separator"></div>
                    </li>
                    
                    <li class="wp-sidebar-menu-item">
                        <div class="wp-sidebar-menu-title" onclick="toggleMenuGroup(this)">店舗・予約</div>
                        <ul class="wp-sidebar-menu-group">
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('company.stores.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.stores*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon">🏪</span>
                                    <span>店舗管理</span>
                                </a>
                            </li>
                            
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('company.staffs.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.staffs*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon">👥</span>
                                    <span>スタッフ管理</span>
                                </a>
                            </li>
                            
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('company.menus.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.menus*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon">📋</span>
                                    <span>メニュー管理</span>
                                </a>
                            </li>
                            
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('company.schedules.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.schedules*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon">📅</span>
                                    <span>営業スケジュール</span>
                                </a>
                            </li>
                            
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('company.reservations.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.reservations*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon">📌</span>
                                    <span>予約管理</span>
                                    <span id="sidebar-reservations-badge" class="wp-badge" style="display: {{ isset($sidebarStats) && $sidebarStats['upcomingReservationsCount'] > 0 ? 'inline-block' : 'none' }};">
                                        {{ isset($sidebarStats) ? $sidebarStats['upcomingReservationsCount'] : 0 }}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="wp-sidebar-menu-item">
                        <div class="wp-sidebar-menu-separator"></div>
                    </li>
                    
                    <li class="wp-sidebar-menu-item">
                        <div class="wp-sidebar-menu-title" onclick="toggleMenuGroup(this)">設定</div>
                        <ul class="wp-sidebar-menu-group">
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('company.info') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.info*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon">🏢</span>
                                    <span>会社情報</span>
                                </a>
                            </li>
                            
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('company.plans.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.plans*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon">💳</span>
                                    <span>プラン管理</span>
                                </a>
                            </li>
                            
                            <li class="wp-sidebar-menu-item">
                                <a href="{{ route('company.subsidies.index') }}" class="wp-sidebar-menu-link {{ request()->routeIs('company.subsidies*') ? 'active' : '' }}">
                                    <span class="wp-sidebar-menu-link-icon">💵</span>
                                    <span>補助金情報</span>
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
