@extends('layouts.admin')

@section('title', 'ダッシュボード')

@section('content')
    <style>
        .dashboard-welcome {
            padding: 24px;
            background: #ffffff;
            border: none;
            box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
            border-radius: 0;
            margin-bottom: 24px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }
        
        .stat-card {
            padding: 20px;
            background: #ffffff;
            border: 1px solid #e8e8e8;
            box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
            border-radius: 0;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: #5D535E;
        }
        
        .stat-card.users::before {
            background: #2271b1;
        }
        
        .stat-card.companies::before {
            background: #00a32a;
        }
        
        .stat-card.job-posts::before {
            background: #d63638;
        }
        
        .stat-card.applications::before {
            background: #826eb4;
        }
        
        .stat-card.orders::before {
            background: #f56e28;
        }
        
        .stat-card.revenue::before {
            background: #f0b849;
        }
        
        .stat-card-header {
            margin-bottom: 16px;
        }
        
        .stat-card-title {
            margin: 0 0 8px 0;
            font-size: 13px;
            font-weight: 400;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-card-value {
            margin: 0 0 4px 0;
            font-size: 28px;
            font-weight: 600;
            color: #1a1a1a;
            line-height: 1.2;
        }
        
        .stat-card-change {
            margin: 0;
            font-size: 12px;
            color: #666;
        }
        
        .stat-card-change.positive {
            color: #00a32a;
        }
        
        .stat-card-change.negative {
            color: #d63638;
        }
        
        .stat-card-link {
            display: inline-block;
            margin-top: 8px;
            font-size: 13px;
            color: #2271b1;
            text-decoration: none;
        }
        
        .stat-card-link:hover {
            text-decoration: underline;
        }
        
        .stat-card-chart {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid #e8e8e8;
        }
        
        .stat-card-chart-title {
            margin: 0 0 12px 0;
            font-size: 12px;
            font-weight: 600;
            color: #666;
        }
        
        .stat-card-chart-container {
            position: relative;
            height: 200px;
        }
        
        .dashboard-section {
            margin-bottom: 32px;
        }
        
        .dashboard-section-title {
            margin: 0 0 16px 0;
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
            padding-bottom: 8px;
            border-bottom: 2px solid #e8e8e8;
        }
        
        .dashboard-menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }
        
        .dashboard-menu-card {
            padding: 20px;
            background: #ffffff;
            border: 1px solid #e8e8e8;
            box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
            border-radius: 0;
            text-decoration: none;
            transition: all 0.3s ease;
            display: block;
        }
        
        .dashboard-menu-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(93, 83, 94, 0.2);
            border-color: #5D535E;
            background: #fafafa;
        }
        
        .dashboard-menu-card:hover h3 {
            color: #5D535E;
            font-weight: 500;
        }
        
        .dashboard-menu-card:hover p {
            color: #333;
        }
        
        .dashboard-menu-card h3 {
            margin: 0 0 8px 0;
            font-size: 16px;
            font-weight: 400;
            color: #1a1a1a;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            transition: color 0.3s ease;
        }
        
        .dashboard-menu-card p {
            margin: 0;
            font-size: 13px;
            color: #666;
            line-height: 1.5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            transition: color 0.3s ease;
        }
        
        .number-format {
            font-variant-numeric: tabular-nums;
        }
        
        /* モバイル対応 */
        @media (max-width: 782px) {
            .dashboard-welcome {
                padding: 16px;
                margin-bottom: 16px;
            }
            
            .dashboard-welcome h2 {
                font-size: 18px !important;
            }
            
            .dashboard-welcome p {
                font-size: 13px !important;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 16px;
                margin-bottom: 24px;
            }
            
            .stat-card {
                padding: 16px;
            }
            
            .stat-card-title {
                font-size: 12px;
            }
            
            .stat-card-value {
                font-size: 24px;
            }
            
            .stat-card-change {
                font-size: 11px;
            }
            
            .stat-card-link {
                font-size: 12px;
            }
            
            .stat-card-chart {
                padding-top: 12px;
            }
            
            .stat-card-chart-title {
                font-size: 11px;
                margin-bottom: 8px;
            }
            
            .stat-card-chart-container {
                height: 150px;
            }
            
            .dashboard-section {
                margin-bottom: 24px;
            }
            
            .dashboard-section-title {
                font-size: 16px;
                margin-bottom: 12px;
                padding-bottom: 6px;
            }
            
            .dashboard-menu-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .dashboard-menu-card {
                padding: 16px;
            }
            
            .dashboard-menu-card h3 {
                font-size: 15px;
            }
            
            .dashboard-menu-card p {
                font-size: 12px;
            }
        }
        
        @media (max-width: 480px) {
            .dashboard-welcome {
                padding: 12px;
                margin-bottom: 12px;
            }
            
            .dashboard-welcome h2 {
                font-size: 16px !important;
            }
            
            .dashboard-welcome p {
                font-size: 12px !important;
            }
            
            .stats-grid {
                gap: 12px;
                margin-bottom: 20px;
            }
            
            .stat-card {
                padding: 12px;
            }
            
            .stat-card-title {
                font-size: 11px;
            }
            
            .stat-card-value {
                font-size: 20px;
            }
            
            .stat-card-change {
                font-size: 10px;
            }
            
            .stat-card-link {
                font-size: 11px;
                margin-top: 6px;
            }
            
            .stat-card-chart {
                padding-top: 10px;
            }
            
            .stat-card-chart-title {
                font-size: 10px;
                margin-bottom: 6px;
            }
            
            .stat-card-chart-container {
                height: 120px;
            }
            
            .dashboard-section {
                margin-bottom: 20px;
            }
            
            .dashboard-section-title {
                font-size: 15px;
                margin-bottom: 10px;
                padding-bottom: 5px;
            }
            
            .dashboard-menu-grid {
                gap: 10px;
            }
            
            .dashboard-menu-card {
                padding: 14px;
            }
            
            .dashboard-menu-card h3 {
                font-size: 14px;
            }
            
            .dashboard-menu-card p {
                font-size: 11px;
            }
        }
        
    </style>
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <div class="dashboard-welcome">
        <h2 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">管理画面ダッシュボード</h2>
        <p style="margin: 0; font-size: 14px; color: #666666; line-height: 1.5;">システム全体の統計情報と各種管理機能を確認できます。</p>
    </div>

    @if(isset($stats))
    <!-- 統計情報とグラフ一体化セクション -->
    <div class="stats-grid">
        <!-- ユーザーカード -->
        <div class="stat-card users">
            <div class="stat-card-header">
                <div class="stat-card-title">総ユーザー数</div>
                <div class="stat-card-value number-format">{{ number_format($stats['totalUsersCount']) }}</div>
                @if($stats['lastMonthUsersCount'] > 0)
                    @php
                        $usersChange = (($stats['thisMonthUsersCount'] - $stats['lastMonthUsersCount']) / $stats['lastMonthUsersCount']) * 100;
                    @endphp
                    <div class="stat-card-change {{ $usersChange >= 0 ? 'positive' : 'negative' }}">
                        今月: {{ number_format($stats['thisMonthUsersCount']) }}人
                        @if($usersChange >= 0)+@endif{{ number_format($usersChange, 1) }}% 先月比
                    </div>
                @else
                    <div class="stat-card-change">今月: {{ number_format($stats['thisMonthUsersCount']) }}人</div>
                @endif
                <a href="{{ route('admin.users.index') }}" class="stat-card-link">ユーザー一覧を見る →</a>
            </div>
            @if(isset($stats['chartData']))
            <div class="stat-card-chart">
                <div class="stat-card-chart-title">過去30日間の推移</div>
                <div class="stat-card-chart-container">
                    <canvas id="usersChart"></canvas>
                </div>
            </div>
            @endif
        </div>

        <!-- 事業者カード -->
        <div class="stat-card companies">
            <div class="stat-card-header">
                <div class="stat-card-title">総事業者数</div>
                <div class="stat-card-value number-format">{{ number_format($stats['totalCompaniesCount']) }}</div>
                @if($stats['lastMonthCompaniesCount'] > 0)
                    @php
                        $companiesChange = (($stats['thisMonthCompaniesCount'] - $stats['lastMonthCompaniesCount']) / $stats['lastMonthCompaniesCount']) * 100;
                    @endphp
                    <div class="stat-card-change {{ $companiesChange >= 0 ? 'positive' : 'negative' }}">
                        今月: {{ number_format($stats['thisMonthCompaniesCount']) }}社
                        @if($companiesChange >= 0)+@endif{{ number_format($companiesChange, 1) }}% 先月比
                    </div>
                @else
                    <div class="stat-card-change">今月: {{ number_format($stats['thisMonthCompaniesCount']) }}社</div>
                @endif
                <a href="{{ route('admin.companies.index') }}" class="stat-card-link">事業者一覧を見る →</a>
            </div>
            @if(isset($stats['chartData']))
            <div class="stat-card-chart">
                <div class="stat-card-chart-title">過去30日間の推移</div>
                <div class="stat-card-chart-container">
                    <canvas id="companiesChart"></canvas>
                </div>
            </div>
            @endif
        </div>

        <!-- 求人カード -->
        <div class="stat-card job-posts">
            <div class="stat-card-header">
                <div class="stat-card-title">総求人数</div>
                <div class="stat-card-value">{{ number_format($stats['totalJobPostsCount']) }}</div>
                <div class="stat-card-change">公開中: {{ number_format($stats['activeJobPostsCount']) }}件 | 今月: {{ number_format($stats['thisMonthJobPostsCount']) }}件</div>
                <a href="{{ route('admin.job-posts.index') }}" class="stat-card-link">求人一覧を見る →</a>
            </div>
            @if(isset($stats['chartData']))
            <div class="stat-card-chart">
                <div class="stat-card-chart-title">過去30日間の推移</div>
                <div class="stat-card-chart-container">
                    <canvas id="jobPostsChart"></canvas>
                </div>
            </div>
            @endif
        </div>

        <!-- 応募カード -->
        <div class="stat-card applications">
            <div class="stat-card-header">
                <div class="stat-card-title">総応募数</div>
                <div class="stat-card-value">{{ number_format($stats['totalApplicationsCount']) }}</div>
                <div class="stat-card-change">今月: {{ number_format($stats['thisMonthApplicationsCount']) }}件</div>
            </div>
            @if(isset($stats['chartData']))
            <div class="stat-card-chart">
                <div class="stat-card-chart-title">過去30日間の推移</div>
                <div class="stat-card-chart-container">
                    <canvas id="applicationsChart"></canvas>
                </div>
            </div>
            @endif
        </div>

        <!-- 注文カード -->
        <div class="stat-card orders">
            <div class="stat-card-header">
                <div class="stat-card-title">総注文数</div>
                <div class="stat-card-value">{{ number_format($stats['totalOrdersCount']) }}</div>
                <div class="stat-card-change">今月: {{ number_format($stats['thisMonthOrdersCount']) }}件</div>
            </div>
            @if(isset($stats['chartData']))
            <div class="stat-card-chart">
                <div class="stat-card-chart-title">過去30日間の推移</div>
                <div class="stat-card-chart-container">
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>
            @endif
        </div>

        <!-- 売上カード -->
        <div class="stat-card revenue">
            <div class="stat-card-header">
                <div class="stat-card-title">今月の売上</div>
                <div class="stat-card-value number-format">¥{{ number_format($stats['thisMonthRevenue']) }}</div>
                @if($stats['lastMonthRevenue'] > 0)
                    @php
                        $revenueChange = (($stats['thisMonthRevenue'] - $stats['lastMonthRevenue']) / $stats['lastMonthRevenue']) * 100;
                    @endphp
                    <div class="stat-card-change {{ $revenueChange >= 0 ? 'positive' : 'negative' }}">
                        @if($revenueChange >= 0)+@endif{{ number_format($revenueChange, 1) }}% 先月比
                    </div>
                @else
                    <div class="stat-card-change">先月のデータなし</div>
                @endif
            </div>
            @if(isset($stats['chartData']))
            <div class="stat-card-chart">
                <div class="stat-card-chart-title">過去30日間の推移</div>
                <div class="stat-card-chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- ユーザー・事業者関連 -->
    <div class="dashboard-section">
        <h2 class="dashboard-section-title">ユーザー・事業者</h2>
        <div class="dashboard-menu-grid">
            <a href="{{ route('admin.users.index') }}" class="dashboard-menu-card">
                <h3>ユーザー管理</h3>
                <p>ユーザーの一覧・追加・編集・削除</p>
            </a>

            <a href="{{ route('admin.companies.index') }}" class="dashboard-menu-card">
                <h3>事業者管理</h3>
                <p>事業者の一覧・編集・削除</p>
            </a>
        </div>
    </div>

    <!-- 求人・応募関連 -->
    <div class="dashboard-section">
        <h2 class="dashboard-section-title">求人・応募</h2>
        <div class="dashboard-menu-grid">
            <a href="{{ route('admin.job-posts.index') }}" class="dashboard-menu-card">
                <h3>求人管理</h3>
                <p>求人の一覧・編集・削除</p>
            </a>
        </div>
    </div>

    <!-- システム設定関連 -->
    <div class="dashboard-section">
        <h2 class="dashboard-section-title">システム設定</h2>
        <div class="dashboard-menu-grid">
            <a href="{{ route('admin.plans.index') }}" class="dashboard-menu-card">
                <h3>プラン管理</h3>
                <p>プランの作成・編集・削除</p>
            </a>

            <a href="{{ route('admin.subsidies.index') }}" class="dashboard-menu-card">
                <h3>補助金情報管理</h3>
                <p>補助金情報の追加・編集・削除</p>
            </a>

            <a href="{{ route('admin.system-settings.index') }}" class="dashboard-menu-card">
                <h3>システム設定</h3>
                <p>サイト基本設定・メール設定・メンテナンス設定</p>
            </a>
        </div>
    </div>

    @if(isset($stats) && isset($stats['chartData']))
    <script>
        // Chart.jsのデフォルト設定
        Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", "Hiragino Sans", "Yu Gothic", "Noto Sans JP", sans-serif';
        Chart.defaults.font.size = 12;
        Chart.defaults.color = '#666';
        
        const chartData = @json($stats['chartData']);
        
        // ユーザーグラフ
        if (document.getElementById('usersChart')) {
            const usersCtx = document.getElementById('usersChart').getContext('2d');
            new Chart(usersCtx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'ユーザー数',
                        data: chartData.users,
                        borderColor: '#2271b1',
                        backgroundColor: 'rgba(34, 113, 177, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 2,
                        pointHoverRadius: 4,
                        pointBackgroundColor: '#2271b1',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 1.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + '人';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + '人';
                                },
                                font: { size: 10 }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: {
                            ticks: {
                                font: { size: 10 },
                                maxRotation: 45,
                                minRotation: 45
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
        
        // 事業者グラフ
        if (document.getElementById('companiesChart')) {
            const companiesCtx = document.getElementById('companiesChart').getContext('2d');
            new Chart(companiesCtx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: '事業者数',
                        data: chartData.companies,
                        borderColor: '#00a32a',
                        backgroundColor: 'rgba(0, 163, 42, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 2,
                        pointHoverRadius: 4,
                        pointBackgroundColor: '#00a32a',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 1.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + '社';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + '社';
                                },
                                font: { size: 10 }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: {
                            ticks: {
                                font: { size: 10 },
                                maxRotation: 45,
                                minRotation: 45
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
        
        // 求人グラフ
        if (document.getElementById('jobPostsChart')) {
            const jobPostsCtx = document.getElementById('jobPostsChart').getContext('2d');
            new Chart(jobPostsCtx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: '求人数',
                        data: chartData.jobPosts,
                        borderColor: '#d63638',
                        backgroundColor: 'rgba(214, 54, 56, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#d63638',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + '件';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    return value + '件';
                                },
                                font: { size: 10 }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: {
                            ticks: {
                                font: { size: 10 },
                                maxRotation: 45,
                                minRotation: 45
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
        
        // 応募グラフ
        if (document.getElementById('applicationsChart')) {
            const applicationsCtx = document.getElementById('applicationsChart').getContext('2d');
            new Chart(applicationsCtx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: '応募数',
                        data: chartData.applications,
                        borderColor: '#826eb4',
                        backgroundColor: 'rgba(130, 110, 180, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#826eb4',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + '件';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    return value + '件';
                                },
                                font: { size: 10 }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: {
                            ticks: {
                                font: { size: 10 },
                                maxRotation: 45,
                                minRotation: 45
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
        
        // 注文グラフ
        if (document.getElementById('ordersChart')) {
            const ordersCtx = document.getElementById('ordersChart').getContext('2d');
            new Chart(ordersCtx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: '注文数',
                        data: chartData.orders,
                        borderColor: '#f56e28',
                        backgroundColor: 'rgba(245, 110, 40, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#f56e28',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + '件';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    return value + '件';
                                },
                                font: { size: 10 }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: {
                            ticks: {
                                font: { size: 10 },
                                maxRotation: 45,
                                minRotation: 45
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
        
        // 売上グラフ
        if (document.getElementById('revenueChart')) {
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: '売上（円）',
                        data: chartData.revenue,
                        borderColor: '#f0b849',
                        backgroundColor: 'rgba(240, 184, 73, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 2,
                        pointHoverRadius: 4,
                        pointBackgroundColor: '#f0b849',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 1.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return '¥' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 10000) {
                                        return '¥' + (value / 10000).toFixed(1) + '万';
                                    }
                                    return '¥' + value.toLocaleString();
                                },
                                font: { size: 10 }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: {
                            ticks: {
                                font: { size: 10 },
                                maxRotation: 45,
                                minRotation: 45
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    </script>
    @endif
@endsection
