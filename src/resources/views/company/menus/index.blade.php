@extends('layouts.company')

@section('title', 'メニュー管理')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">メニュー管理</h1>
    <a href="{{ route('company.menus.create') }}" style="
        padding: 12px 24px;
        background: #5D535E;
        color: #ffffff;
        border: none;
        border-radius: 24px;
        font-size: 14px;
        font-weight: 700;
        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
        メニュー追加
    </a>
</div>

@foreach($stores as $store)
<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
    margin-bottom: 24px;
    overflow-x: auto;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">{{ $store->name }}</h3>
    </div>
    @if($store->serviceMenus->isEmpty())
        <div style="padding: 40px 24px; text-align: center; color: #999999;">
            <p style="margin: 0; font-size: 14px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">メニューが登録されていません。</p>
        </div>
    @else
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #fafafa; border-bottom: 1px solid #e8e8e8;">
                    <th style="
                        padding: 12px 16px;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">メニュー名</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">所要時間</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">料金</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">ステータス</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($store->serviceMenus as $menu)
                <tr style="border-bottom: 1px solid #f5f5f5;" onmouseover="this.style.background='#fafafa';" onmouseout="this.style.background='#ffffff';">
                    <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $menu->name }}</td>
                    <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $menu->duration_minutes }}分</td>
                    <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ number_format($menu->price) }}円</td>
                    <td style="padding: 12px 16px;">
                        @if($menu->is_active)
                            <span style="
                                display: inline-block;
                                padding: 4px 12px;
                                background: #336B87;
                                color: #ffffff;
                                border-radius: 12px;
                                font-size: 12px;
                                font-weight: 500;
                            ">公開中</span>
                        @else
                            <span style="
                                display: inline-block;
                                padding: 4px 12px;
                                background: #999999;
                                color: #ffffff;
                                border-radius: 12px;
                                font-size: 12px;
                                font-weight: 500;
                            ">非公開</span>
                        @endif
                    </td>
                    <td style="padding: 12px 16px;">
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('company.menus.edit', $menu) }}" style="
                                padding: 6px 16px;
                                background: transparent;
                                color: #5D535E;
                                border: 1px solid #5D535E;
                                border-radius: 16px;
                                font-size: 12px;
                                font-weight: 700;
                                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                                text-decoration: none;
                                transition: all 0.2s ease;
                                position: relative;
                            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                                編集
                            </a>
                            <form action="{{ route('company.menus.destroy', $menu) }}" method="POST" style="display: inline;" onsubmit="return confirm('このメニューを削除してもよろしいですか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="
                                    padding: 6px 16px;
                                    background: #763626;
                                    color: #ffffff;
                                    border: none;
                                    border-radius: 16px;
                                    font-size: 12px;
                                    font-weight: 700;
                                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                                    cursor: pointer;
                                    transition: all 0.2s ease;
                                    position: relative;
                                " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                                    削除
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endforeach

@if($stores->isEmpty())
    <div style="
        padding: 0;
        border: none;
        box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
        border-radius: 0;
        background: #ffffff;
    ">
        <div style="padding: 40px 24px; text-align: center;">
            <p style="margin: 0 0 20px 0; color: #999999; font-size: 14px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">店舗が登録されていません。先に店舗を登録してください。</p>
            <a href="{{ route('company.stores.create') }}" style="
                padding: 12px 24px;
                background: #5D535E;
                color: #ffffff;
                border: none;
                border-radius: 24px;
                font-size: 14px;
                font-weight: 700;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.2s ease;
                position: relative;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                店舗を登録する
            </a>
        </div>
    </div>
@endif
@endsection

