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

        <div class="admin-menu-card admin-menu-disabled">
            <h3 class="admin-menu-title">事業者管理</h3>
            <p class="admin-menu-desc">準備中</p>
        </div>

        <div class="admin-menu-card admin-menu-disabled">
            <h3 class="admin-menu-title">求人管理</h3>
            <p class="admin-menu-desc">準備中</p>
        </div>

        <div class="admin-menu-card admin-menu-disabled">
            <h3 class="admin-menu-title">補助金情報管理</h3>
            <p class="admin-menu-desc">準備中</p>
        </div>

        <div class="admin-menu-card admin-menu-disabled">
            <h3 class="admin-menu-title">プラン管理</h3>
            <p class="admin-menu-desc">準備中</p>
        </div>

        <div class="admin-menu-card admin-menu-disabled">
            <h3 class="admin-menu-title">システム設定</h3>
            <p class="admin-menu-desc">準備中</p>
        </div>
    </div>
@endsection


