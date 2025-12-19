@extends('layouts.admin')

@section('title', '管理画面')

@section('content')
    <div class="admin-header">
        <h1 class="admin-title">ダッシュボード</h1>
    </div>

    <div class="admin-menu-grid">
        <a href="{{ route('admin.users.index') }}" class="admin-menu-card">
            <h3 class="admin-menu-title">ユーザー管理</h3>
            <p class="admin-menu-desc">ユーザーの一覧・追加・編集・削除</p>
        </a>

        <a href="{{ route('admin.companies.index') }}" class="admin-menu-card">
            <h3 class="admin-menu-title">事業者管理</h3>
            <p class="admin-menu-desc">事業者の一覧・編集・削除</p>
        </a>

        <a href="{{ route('admin.job-posts.index') }}" class="admin-menu-card">
            <h3 class="admin-menu-title">求人管理</h3>
            <p class="admin-menu-desc">求人の一覧・編集・削除</p>
        </a>

        <a href="{{ route('admin.subsidies.index') }}" class="admin-menu-card">
            <h3 class="admin-menu-title">補助金情報管理</h3>
            <p class="admin-menu-desc">補助金情報の追加・編集・削除</p>
        </a>

        <a href="{{ route('admin.plans.index') }}" class="admin-menu-card">
            <h3 class="admin-menu-title">プラン管理</h3>
            <p class="admin-menu-desc">プランの作成・編集・削除</p>
        </a>

        <a href="{{ route('admin.system-settings.index') }}" class="admin-menu-card">
            <h3 class="admin-menu-title">システム設定</h3>
            <p class="admin-menu-desc">サイト基本設定・メール設定・メンテナンス設定</p>
        </a>
    </div>
@endsection


