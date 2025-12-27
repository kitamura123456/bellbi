@extends('layouts.company')

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
        
        .stat-card.revenue::before {
            background: #2271b1;
        }
        
        .stat-card.orders::before {
            background: #00a32a;
        }
        
        .stat-card.applications::before {
            background: #d63638;
        }
        
        .stat-card.reservations::before {
            background: #826eb4;
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
        
    </style>
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <div style="margin-bottom: 24px; margin-top: 48px;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">ダッシュボード</h1>
    </div>

    @if($company)
    <div class="dashboard-welcome">
        <h2 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">{{ $company->name }} 様</h2>
        <p style="margin: 0; font-size: 14px; color: #666666; line-height: 1.5;">事業者管理画面へようこそ。売上状況、新しい注文、求人応募などを確認できます。</p>
    </div>

    @if(isset($stats))
    <!-- 統計情報とグラフ一体化セクション -->
    <div class="stats-grid">
        <!-- 売上カード -->
        <div class="stat-card revenue">
            <div class="stat-card-header">
                <div class="stat-card-title">今月の売上</div>
                <div class="stat-card-value number-format">¥{{ number_format($stats['thisMonthTotalRevenue']) }}</div>
                @if($stats['lastMonthTotalRevenue'] > 0)
                    @php
                        $revenueChange = (($stats['thisMonthTotalRevenue'] - $stats['lastMonthTotalRevenue']) / $stats['lastMonthTotalRevenue']) * 100;
                    @endphp
                    <div class="stat-card-change {{ $revenueChange >= 0 ? 'positive' : 'negative' }}">
                        @if($revenueChange >= 0)+@endif{{ number_format($revenueChange, 1) }}% 先月比
                    </div>
                @else
                    <div class="stat-card-change">先月のデータなし</div>
                @endif
                <a href="{{ route('company.transactions.index') }}" class="stat-card-link">売上詳細を見る →</a>
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

        <!-- 注文カード -->
        <div class="stat-card orders">
            <div class="stat-card-header">
                <div class="stat-card-title">新しい注文</div>
                <div class="stat-card-value">{{ $stats['newOrdersCount'] }}</div>
                <div class="stat-card-change">今月の注文数: {{ $stats['thisMonthOrdersCount'] }}件</div>
                <a href="{{ route('company.orders.index') }}" class="stat-card-link">注文一覧を見る →</a>
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

        <!-- 応募カード -->
        <div class="stat-card applications">
            <div class="stat-card-header">
                <div class="stat-card-title">新しい求人応募</div>
                <div class="stat-card-value">{{ $stats['newApplicationsCount'] }}</div>
                <div class="stat-card-change">今月の応募数: {{ $stats['thisMonthApplicationsCount'] }}件</div>
                <a href="{{ route('company.applications.index') }}" class="stat-card-link">応募一覧を見る →</a>
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

        <!-- 予約カード -->
        <div class="stat-card reservations">
            <div class="stat-card-header">
                <div class="stat-card-title">今後の予約</div>
                <div class="stat-card-value">{{ $stats['upcomingReservationsCount'] }}</div>
                <div class="stat-card-change">アクティブな店舗: {{ $stats['activeStoresCount'] }}店舗</div>
                <a href="{{ route('company.reservations.index') }}" class="stat-card-link">予約一覧を見る →</a>
            </div>
            @if(isset($stats['chartData']))
            <div class="stat-card-chart">
                <div class="stat-card-chart-title">過去30日間の推移</div>
                <div class="stat-card-chart-container">
                    <canvas id="reservationsChart"></canvas>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- 売上・注文関連 -->
    <div class="dashboard-section">
        <h2 class="dashboard-section-title">売上・注文</h2>
        <div class="dashboard-menu-grid">
            <a href="{{ route('company.transactions.index') }}" class="dashboard-menu-card">
                <h3>売上・経費</h3>
                <p>日々の売上・経費を記録・集計</p>
            </a>

            <a href="{{ route('company.transactions.report') }}" class="dashboard-menu-card">
                <h3>月次レポート</h3>
                <p>売上・経費の月次集計</p>
            </a>

            <a href="{{ route('company.account-items.index') }}" class="dashboard-menu-card">
                <h3>科目マスタ</h3>
                <p>売上・経費の科目設定</p>
            </a>

            <a href="{{ route('company.orders.index') }}" class="dashboard-menu-card">
                <h3>受注管理</h3>
                <p>注文一覧・ステータス管理</p>
            </a>

            <a href="{{ route('company.shops.index') }}" class="dashboard-menu-card">
                <h3>ECショップ管理</h3>
                <p>ショップの開設・編集・削除</p>
            </a>

            <a href="{{ route('company.products.index') }}" class="dashboard-menu-card">
                <h3>商品管理</h3>
                <p>商品の登録・編集・削除</p>
            </a>
        </div>
    </div>

    <!-- 求人・応募関連 -->
    <div class="dashboard-section">
        <h2 class="dashboard-section-title">求人・応募</h2>
        <div class="dashboard-menu-grid">
            <a href="{{ route('company.job-posts.index') }}" class="dashboard-menu-card">
                <h3>求人管理</h3>
                <p>求人の作成・編集・応募管理</p>
            </a>

            <a href="{{ route('company.applications.index') }}" class="dashboard-menu-card">
                <h3>応募管理</h3>
                <p>応募者の確認・管理</p>
            </a>

            <a href="{{ route('company.scouts.search') }}" class="dashboard-menu-card">
                <h3>スカウト</h3>
                <p>候補者検索・スカウト送信</p>
            </a>

            <a href="{{ route('company.messages.index') }}" class="dashboard-menu-card">
                <h3>メッセージ</h3>
                <p>応募者・スカウト送信先とのやりとり</p>
            </a>
        </div>
    </div>

    <!-- 店舗・予約関連 -->
    <div class="dashboard-section">
        <h2 class="dashboard-section-title">店舗・予約</h2>
        <div class="dashboard-menu-grid">
            <a href="{{ route('company.stores.index') }}" class="dashboard-menu-card">
                <h3>店舗管理</h3>
                <p>店舗の登録・編集・削除</p>
            </a>

            <a href="{{ route('company.staffs.index') }}" class="dashboard-menu-card">
                <h3>スタッフ管理</h3>
                <p>スタッフ登録・編集</p>
            </a>

            <a href="{{ route('company.menus.index') }}" class="dashboard-menu-card">
                <h3>メニュー管理</h3>
                <p>施術メニュー登録・編集</p>
            </a>

            <a href="{{ route('company.schedules.index') }}" class="dashboard-menu-card">
                <h3>営業スケジュール</h3>
                <p>営業時間・定休日設定</p>
            </a>

            <a href="{{ route('company.reservations.index') }}" class="dashboard-menu-card">
                <h3>予約管理</h3>
                <p>予約一覧・ステータス変更</p>
            </a>
        </div>
    </div>

    <!-- その他 -->
    <div class="dashboard-section">
        <h2 class="dashboard-section-title">その他</h2>
        <div class="dashboard-menu-grid">
            <a href="{{ route('company.info') }}" class="dashboard-menu-card">
                <h3>会社情報</h3>
                <p>会社情報の確認・編集</p>
            </a>

            <a href="{{ route('company.plans.index') }}" class="dashboard-menu-card">
                <h3>プラン管理</h3>
                <p>プランの選択・契約・変更</p>
            </a>

            <a href="{{ route('company.subsidies.index') }}" class="dashboard-menu-card">
                <h3>補助金情報</h3>
                <p>補助金情報の閲覧・検索</p>
            </a>
        </div>
    </div>
    @else
    <div class="dashboard-welcome">
        <p style="margin: 0; color: #2A3132; font-size: 14px;">会社情報が登録されていません。システム管理者にお問い合わせください。</p>
    </div>
    @endif

    @if(isset($stats) && isset($stats['chartData']))
    <script>
        // Chart.jsのデフォルト設定
        Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", "Hiragino Sans", "Yu Gothic", "Noto Sans JP", sans-serif';
        Chart.defaults.font.size = 12;
        Chart.defaults.color = '#666';
        
        const chartData = @json($stats['chartData']);
        
        // 売上グラフ
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: '売上（円）',
                    data: chartData.revenue,
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
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return '¥' + context.parsed.y.toLocaleString();
                            }
                        },
                        titleFont: {
                            size: 12
                        },
                        bodyFont: {
                            size: 11
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
                            font: {
                                size: 10
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10
                            },
                            maxRotation: 45,
                            minRotation: 45
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
        
        // 注文グラフ
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        new Chart(ordersCtx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: '注文数',
                    data: chartData.orders,
                    borderColor: '#00a32a',
                    backgroundColor: 'rgba(0, 163, 42, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#00a32a',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + '件';
                            }
                        },
                        titleFont: {
                            size: 12
                        },
                        bodyFont: {
                            size: 11
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
                            font: {
                                size: 10
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10
                            },
                            maxRotation: 45,
                            minRotation: 45
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
        
        // 応募グラフ
        const applicationsCtx = document.getElementById('applicationsChart').getContext('2d');
        new Chart(applicationsCtx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: '応募数',
                    data: chartData.applications,
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
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + '件';
                            }
                        },
                        titleFont: {
                            size: 12
                        },
                        bodyFont: {
                            size: 11
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
                            font: {
                                size: 10
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10
                            },
                            maxRotation: 45,
                            minRotation: 45
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
        
        // 予約グラフ
        const reservationsCtx = document.getElementById('reservationsChart').getContext('2d');
        new Chart(reservationsCtx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: '予約数',
                    data: chartData.reservations,
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
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + '件';
                            }
                        },
                        titleFont: {
                            size: 12
                        },
                        bodyFont: {
                            size: 11
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
                            font: {
                                size: 10
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10
                            },
                            maxRotation: 45,
                            minRotation: 45
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    </script>
    @endif
@endsection
