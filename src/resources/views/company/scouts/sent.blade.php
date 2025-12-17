@extends('layouts.company')

@section('title', '送信済みスカウト')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">送信済みスカウト</h1>
    <a href="{{ route('company.scouts.search') }}" style="
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
        候補者を検索
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
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">スカウト一覧</h3>
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
                ">送信日</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">送信先</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">件名</th>
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
            @forelse($scouts as $scout)
            <tr style="border-bottom: 1px solid #f5f5f5;" onmouseover="this.style.background='#fafafa';" onmouseout="this.style.background='#ffffff';">
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $scout->created_at->format('Y-m-d H:i') }}</td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $scout->toUser->name }}</td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $scout->subject }}</td>
                <td style="padding: 12px 16px;">
                    @if($scout->status === 1)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #5D535E;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">送信済</span>
                    @elseif($scout->status === 2)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #90AFC5;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">既読</span>
                    @elseif($scout->status === 3)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #336B87;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">返信あり</span>
                    @elseif($scout->status === 9)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #999999;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">クローズ</span>
                    @endif
                </td>
                <td style="padding: 12px 16px;">
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('company.scouts.show', $scout) }}" style="
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
                            詳細
                        </a>
                        <a href="{{ route('company.messages.create-from-scout', $scout) }}" style="
                            padding: 6px 16px;
                            background: #5D535E;
                            color: #ffffff;
                            border: none;
                            border-radius: 16px;
                            font-size: 12px;
                            font-weight: 700;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                            text-decoration: none;
                            transition: all 0.2s ease;
                            position: relative;
                        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                            メッセージ
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding: 40px 16px; text-align: center; color: #999999; font-size: 14px;">まだスカウトを送信していません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection


