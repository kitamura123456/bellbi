@extends('layouts.company')

@section('title', 'ダッシュボード')

@section('content')
    <style>
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
    </style>
    <div style="margin-bottom: 24px;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">ダッシュボード</h1>
    </div>

    @if($company)
    <div style="
        padding: 24px;
        background: #ffffff;
        border: none;
        box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
        border-radius: 0;
        margin-bottom: 24px;
    ">
        <h2 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">{{ $company->name }} 様</h2>
        <p style="margin: 0; font-size: 14px; color: #666666; line-height: 1.5;">事業者管理画面へようこそ。求人管理・店舗管理などの機能をご利用いただけます。</p>
    </div>

    <div style="
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
    ">
        <a href="{{ route('company.info') }}" class="dashboard-menu-card">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">会社情報</h3>
            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">会社情報の確認・編集</p>
        </a>

        <a href="{{ route('company.stores.index') }}" class="dashboard-menu-card">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">店舗管理</h3>
            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">店舗の登録・編集・削除</p>
        </a>

        <a href="{{ route('company.staffs.index') }}" class="dashboard-menu-card">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">スタッフ管理</h3>
            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">スタッフ登録・編集</p>
        </a>

        <a href="{{ route('company.menus.index') }}" class="dashboard-menu-card">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">メニュー管理</h3>
            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">施術メニュー登録・編集</p>
        </a>

        <a href="{{ route('company.schedules.index') }}" class="dashboard-menu-card">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">営業スケジュール</h3>
            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">営業時間・定休日設定</p>
        </a>

        <a href="{{ route('company.job-posts.index') }}" class="dashboard-menu-card">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">求人管理</h3>
            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">求人の作成・編集・応募管理</p>
        </a>

        <a href="{{ route('company.applications.index') }}" class="dashboard-menu-card">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">応募管理</h3>
            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">応募者の確認・管理</p>
        </a>

        <a href="{{ route('company.scouts.search') }}" class="dashboard-menu-card">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">スカウト</h3>
            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">候補者検索・スカウト送信</p>
        </a>

        <a href="{{ route('company.messages.index') }}" class="dashboard-menu-card">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">メッセージ</h3>
            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">応募者・スカウト送信先とのやりとり</p>
        </a>

        <a href="{{ route('company.reservations.index') }}" class="dashboard-menu-card">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">予約管理</h3>
            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">予約一覧・ステータス変更</p>
        </a>

        <a href="{{ route('company.transactions.index') }}" class="dashboard-menu-card">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">売上・経費</h3>
            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">日々の売上・経費を記録・集計</p>
        </a>

        <a href="{{ route('company.account-items.index') }}" class="dashboard-menu-card">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">科目マスタ</h3>
            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">売上・経費の科目設定</p>
        </a>

        <a href="{{ route('company.transactions.report') }}" class="dashboard-menu-card">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">月次レポート</h3>
            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">売上・経費の月次集計</p>
        </a>

        <a href="{{ route('company.plans.index') }}" class="dashboard-menu-card">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">プラン管理</h3>
            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">プランの選択・契約・変更</p>
        </a>

        <a href="{{ route('company.subsidies.index') }}" class="dashboard-menu-card">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 400; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">補助金情報</h3>
            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; transition: color 0.3s ease;">補助金情報の閲覧・検索</p>
        </a>
    </div>
    @else
    <div style="
        padding: 24px;
        background: #ffffff;
        border: none;
        box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
        border-radius: 0;
    ">
        <p style="margin: 0; color: #2A3132; font-size: 14px;">会社情報が登録されていません。システム管理者にお問い合わせください。</p>
    </div>
    @endif
@endsection
