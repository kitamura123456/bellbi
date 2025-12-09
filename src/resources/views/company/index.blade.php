@extends('layouts.company')

@section('title', 'ダッシュボード')

@section('content')
    <div class="company-header">
        <h1 class="company-title">ダッシュボード</h1>
    </div>

    @if($company)
    <div class="company-welcome-card">
        <h2 class="company-welcome-title">{{ $company->name }} 様</h2>
        <p class="company-welcome-text">事業者管理画面へようこそ。求人管理・店舗管理などの機能をご利用いただけます。</p>
    </div>

    <div class="company-menu-grid">
        <a href="{{ route('company.info') }}" class="company-menu-card">
            <div class="company-menu-icon">🏢</div>
            <h3 class="company-menu-title">会社情報</h3>
            <p class="company-menu-desc">会社情報の確認・編集</p>
        </a>

        <a href="{{ route('company.stores.index') }}" class="company-menu-card">
            <div class="company-menu-icon">🏪</div>
            <h3 class="company-menu-title">店舗管理</h3>
            <p class="company-menu-desc">店舗の登録・編集・削除</p>
        </a>

        <a href="{{ route('company.job-posts.index') }}" class="company-menu-card">
            <div class="company-menu-icon">💼</div>
            <h3 class="company-menu-title">求人管理</h3>
            <p class="company-menu-desc">求人の作成・編集・応募管理</p>
        </a>

        <a href="{{ route('company.scouts.search') }}" class="company-menu-card">
            <div class="company-menu-icon">📧</div>
            <h3 class="company-menu-title">スカウト管理</h3>
            <p class="company-menu-desc">候補者検索・スカウト送信</p>
        </a>

        <a href="{{ route('company.reservations.index') }}" class="company-menu-card">
            <div class="company-menu-icon">📅</div>
            <h3 class="company-menu-title">予約管理</h3>
            <p class="company-menu-desc">予約一覧・ステータス変更</p>
        </a>

        <a href="{{ route('company.staffs.index') }}" class="company-menu-card">
            <div class="company-menu-icon">👥</div>
            <h3 class="company-menu-title">スタッフ管理</h3>
            <p class="company-menu-desc">スタッフ登録・編集</p>
        </a>

        <a href="{{ route('company.menus.index') }}" class="company-menu-card">
            <div class="company-menu-icon">📋</div>
            <h3 class="company-menu-title">メニュー管理</h3>
            <p class="company-menu-desc">施術メニュー登録・編集</p>
        </a>

        <a href="{{ route('company.schedules.index') }}" class="company-menu-card">
            <div class="company-menu-icon">🕐</div>
            <h3 class="company-menu-title">営業スケジュール</h3>
            <p class="company-menu-desc">営業時間・定休日設定</p>
        </a>

        <div class="company-menu-card company-menu-disabled">
            <div class="company-menu-icon">💰</div>
            <h3 class="company-menu-title">売上管理</h3>
            <p class="company-menu-desc">準備中</p>
        </div>

        <div class="company-menu-card company-menu-disabled">
            <div class="company-menu-icon">🛒</div>
            <h3 class="company-menu-title">ECショップ</h3>
            <p class="company-menu-desc">準備中</p>
        </div>
    </div>
    @else
    <div class="company-alert">
        <p>会社情報が登録されていません。システム管理者にお問い合わせください。</p>
    </div>
    @endif
@endsection
