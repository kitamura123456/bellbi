@extends('layouts.company')

@section('title', '店舗管理')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">店舗管理</h1>
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
        新規店舗追加
    </a>
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
    overflow-x: auto;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">店舗一覧</h3>
    </div>
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
                ">店舗名</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">種別</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">住所</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">電話番号</th>
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
            @forelse($stores as $store)
            <tr style="border-bottom: 1px solid #f5f5f5;" onmouseover="this.style.background='#fafafa';" onmouseout="this.style.background='#ffffff';">
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $store->name }}</td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">
                    @if($store->store_type === 1) 美容室
                    @elseif($store->store_type === 2) エステ
                    @elseif($store->store_type === 3) 医科
                    @elseif($store->store_type === 4) 歯科
                    @else その他
                    @endif
                </td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $store->address ?? '未登録' }}</td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $store->tel ?? '未登録' }}</td>
                <td style="padding: 12px 16px;">
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('company.stores.edit', $store) }}" style="
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
                        <form action="{{ route('company.stores.destroy', $store) }}" method="POST" style="display:inline;" onsubmit="return confirm('この店舗を削除してもよろしいですか？');">
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
            @empty
            <tr>
                <td colspan="5" style="padding: 40px 16px; text-align: center; color: #999999; font-size: 14px;">店舗が登録されていません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

