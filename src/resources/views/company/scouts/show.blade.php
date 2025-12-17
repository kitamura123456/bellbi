@extends('layouts.company')

@section('title', 'スカウト詳細')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">スカウト詳細</h1>
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('company.messages.create-from-scout', $scout) }}" style="
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
            メッセージでやりとりする
        </a>
        <a href="{{ route('company.scouts.sent') }}" style="
            padding: 12px 24px;
            background: transparent;
            color: #5D535E;
            border: 1px solid #5D535E;
            border-radius: 24px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
            一覧に戻る
        </a>
    </div>
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">スカウト情報</h3>
    </div>
    <div style="padding: 24px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="border-bottom: 1px solid #f5f5f5;">
                <th style="
                    width: 150px;
                    padding: 12px 0;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">送信日時</th>
                <td style="
                    padding: 12px 0;
                    color: #2A3132;
                    font-size: 14px;
                ">{{ $scout->created_at->format('Y年m月d日 H:i') }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f5f5f5;">
                <th style="
                    padding: 12px 0;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">送信先</th>
                <td style="
                    padding: 12px 0;
                    color: #2A3132;
                    font-size: 14px;
                ">{{ $scout->toUser->name }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f5f5f5;">
                <th style="
                    padding: 12px 0;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">件名</th>
                <td style="
                    padding: 12px 0;
                    color: #2A3132;
                    font-size: 14px;
                ">{{ $scout->subject }}</td>
            </tr>
            <tr>
                <th style="
                    padding: 12px 0;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">ステータス</th>
                <td style="padding: 12px 0;">
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
            </tr>
        </table>

        <div style="margin-top: 24px;">
            <h4 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">メッセージ</h4>
            <div style="white-space: pre-wrap; background-color: #fafafa; padding: 16px; border-radius: 8px; font-size: 14px; line-height: 1.7; color: #2A3132; border: 1px solid #e8e8e8;">{{ $scout->body }}</div>
        </div>
    </div>
</div>
@endsection


