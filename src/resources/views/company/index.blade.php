@extends('layouts.company')

@section('title', 'ダッシュボード')

@section('content')
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
        <a href="{{ route('company.info') }}" style="
            padding: 20px;
            background: #ffffff;
            border: none;
            box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
            border-radius: 0;
            text-decoration: none;
            transition: all 0.2s ease;
            display: block;
        " onmouseover="this.style.boxShadow='0 2px 4px rgba(93, 83, 94, 0.15)'; this.style.borderLeft='1px solid rgba(255,255,255,0.5)';" onmouseout="this.style.boxShadow='0 1px 2px rgba(93, 83, 94, 0.1)'; this.style.borderLeft='none';">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">会社情報</h3>
            <p style="margin: 0; font-size: 13px; color: #666666; line-height: 1.5;">会社情報の確認・編集</p>
        </a>

        <a href="{{ route('company.stores.index') }}" style="
            padding: 20px;
            background: #ffffff;
            border: none;
            box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
            border-radius: 0;
            text-decoration: none;
            transition: all 0.2s ease;
            display: block;
        " onmouseover="this.style.boxShadow='0 2px 4px rgba(93, 83, 94, 0.15)'; this.style.borderLeft='1px solid rgba(255,255,255,0.5)';" onmouseout="this.style.boxShadow='0 1px 2px rgba(93, 83, 94, 0.1)'; this.style.borderLeft='none';">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">店舗管理</h3>
            <p style="margin: 0; font-size: 13px; color: #666666; line-height: 1.5;">店舗の登録・編集・削除</p>
        </a>

        <a href="{{ route('company.job-posts.index') }}" style="
            padding: 20px;
            background: #ffffff;
            border: none;
            box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
            border-radius: 0;
            text-decoration: none;
            transition: all 0.2s ease;
            display: block;
        " onmouseover="this.style.boxShadow='0 2px 4px rgba(93, 83, 94, 0.15)'; this.style.borderLeft='1px solid rgba(255,255,255,0.5)';" onmouseout="this.style.boxShadow='0 1px 2px rgba(93, 83, 94, 0.1)'; this.style.borderLeft='none';">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">求人管理</h3>
            <p style="margin: 0; font-size: 13px; color: #666666; line-height: 1.5;">求人の作成・編集・応募管理</p>
        </a>

        <a href="{{ route('company.scouts.search') }}" style="
            padding: 20px;
            background: #ffffff;
            border: none;
            box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
            border-radius: 0;
            text-decoration: none;
            transition: all 0.2s ease;
            display: block;
        " onmouseover="this.style.boxShadow='0 2px 4px rgba(93, 83, 94, 0.15)'; this.style.borderLeft='1px solid rgba(255,255,255,0.5)';" onmouseout="this.style.boxShadow='0 1px 2px rgba(93, 83, 94, 0.1)'; this.style.borderLeft='none';">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">スカウト管理</h3>
            <p style="margin: 0; font-size: 13px; color: #666666; line-height: 1.5;">候補者検索・スカウト送信</p>
        </a>

        <a href="{{ route('company.messages.index') }}" style="
            padding: 20px;
            background: #ffffff;
            border: none;
            box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
            border-radius: 0;
            text-decoration: none;
            transition: all 0.2s ease;
            display: block;
        " onmouseover="this.style.boxShadow='0 2px 4px rgba(93, 83, 94, 0.15)'; this.style.borderLeft='1px solid rgba(255,255,255,0.5)';" onmouseout="this.style.boxShadow='0 1px 2px rgba(93, 83, 94, 0.1)'; this.style.borderLeft='none';">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">メッセージ</h3>
            <p style="margin: 0; font-size: 13px; color: #666666; line-height: 1.5;">応募者・スカウト送信先とのやりとり</p>
        </a>

        <a href="{{ route('company.reservations.index') }}" style="
            padding: 20px;
            background: #ffffff;
            border: none;
            box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
            border-radius: 0;
            text-decoration: none;
            transition: all 0.2s ease;
            display: block;
        " onmouseover="this.style.boxShadow='0 2px 4px rgba(93, 83, 94, 0.15)'; this.style.borderLeft='1px solid rgba(255,255,255,0.5)';" onmouseout="this.style.boxShadow='0 1px 2px rgba(93, 83, 94, 0.1)'; this.style.borderLeft='none';">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">予約管理</h3>
            <p style="margin: 0; font-size: 13px; color: #666666; line-height: 1.5;">予約一覧・ステータス変更</p>
        </a>

        <a href="{{ route('company.staffs.index') }}" style="
            padding: 20px;
            background: #ffffff;
            border: none;
            box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
            border-radius: 0;
            text-decoration: none;
            transition: all 0.2s ease;
            display: block;
        " onmouseover="this.style.boxShadow='0 2px 4px rgba(93, 83, 94, 0.15)'; this.style.borderLeft='1px solid rgba(255,255,255,0.5)';" onmouseout="this.style.boxShadow='0 1px 2px rgba(93, 83, 94, 0.1)'; this.style.borderLeft='none';">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">スタッフ管理</h3>
            <p style="margin: 0; font-size: 13px; color: #666666; line-height: 1.5;">スタッフ登録・編集</p>
        </a>

        <a href="{{ route('company.menus.index') }}" style="
            padding: 20px;
            background: #ffffff;
            border: none;
            box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
            border-radius: 0;
            text-decoration: none;
            transition: all 0.2s ease;
            display: block;
        " onmouseover="this.style.boxShadow='0 2px 4px rgba(93, 83, 94, 0.15)'; this.style.borderLeft='1px solid rgba(255,255,255,0.5)';" onmouseout="this.style.boxShadow='0 1px 2px rgba(93, 83, 94, 0.1)'; this.style.borderLeft='none';">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">メニュー管理</h3>
            <p style="margin: 0; font-size: 13px; color: #666666; line-height: 1.5;">施術メニュー登録・編集</p>
        </a>

        <a href="{{ route('company.schedules.index') }}" style="
            padding: 20px;
            background: #ffffff;
            border: none;
            box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
            border-radius: 0;
            text-decoration: none;
            transition: all 0.2s ease;
            display: block;
        " onmouseover="this.style.boxShadow='0 2px 4px rgba(93, 83, 94, 0.15)'; this.style.borderLeft='1px solid rgba(255,255,255,0.5)';" onmouseout="this.style.boxShadow='0 1px 2px rgba(93, 83, 94, 0.1)'; this.style.borderLeft='none';">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">営業スケジュール</h3>
            <p style="margin: 0; font-size: 13px; color: #666666; line-height: 1.5;">営業時間・定休日設定</p>
        </a>

        <a href="{{ route('company.transactions.index') }}" style="
            padding: 20px;
            background: #ffffff;
            border: none;
            box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
            border-radius: 0;
            text-decoration: none;
            transition: all 0.2s ease;
            display: block;
        " onmouseover="this.style.boxShadow='0 2px 4px rgba(93, 83, 94, 0.15)'; this.style.borderLeft='1px solid rgba(255,255,255,0.5)';" onmouseout="this.style.boxShadow='0 1px 2px rgba(93, 83, 94, 0.1)'; this.style.borderLeft='none';">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">売上・経費管理</h3>
            <p style="margin: 0; font-size: 13px; color: #666666; line-height: 1.5;">日々の売上・経費を記録・集計</p>
        </a>

        <a href="{{ route('company.account-items.index') }}" style="
            padding: 20px;
            background: #ffffff;
            border: none;
            box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
            border-radius: 0;
            text-decoration: none;
            transition: all 0.2s ease;
            display: block;
        " onmouseover="this.style.boxShadow='0 2px 4px rgba(93, 83, 94, 0.15)'; this.style.borderLeft='1px solid rgba(255,255,255,0.5)';" onmouseout="this.style.boxShadow='0 1px 2px rgba(93, 83, 94, 0.1)'; this.style.borderLeft='none';">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">科目マスタ</h3>
            <p style="margin: 0; font-size: 13px; color: #666666; line-height: 1.5;">売上・経費の科目設定</p>
        </a>

        <a href="{{ route('company.transactions.report') }}" style="
            padding: 20px;
            background: #ffffff;
            border: none;
            box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
            border-radius: 0;
            text-decoration: none;
            transition: all 0.2s ease;
            display: block;
        " onmouseover="this.style.boxShadow='0 2px 4px rgba(93, 83, 94, 0.15)'; this.style.borderLeft='1px solid rgba(255,255,255,0.5)';" onmouseout="this.style.boxShadow='0 1px 2px rgba(93, 83, 94, 0.1)'; this.style.borderLeft='none';">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">月次レポート</h3>
            <p style="margin: 0; font-size: 13px; color: #666666; line-height: 1.5;">売上・経費の月次集計</p>
        </a>

        <div style="
            padding: 20px;
            background: #fafafa;
            border: none;
            box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
            border-radius: 0;
            opacity: 0.6;
        ">
            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #999999; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">ECショップ</h3>
            <p style="margin: 0; font-size: 13px; color: #999999; line-height: 1.5;">準備中</p>
        </div>
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
