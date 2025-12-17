@extends('layouts.company')

@section('title', 'メッセージ')

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="margin: 0 0 8px 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">メッセージ</h1>
    <p style="margin: 0; font-size: 14px; color: #666666;">応募者・スカウト送信先とのメッセージ一覧です。</p>
</div>

@forelse($conversationsWithInfo as $item)
@php
    $conversation = $item['conversation'];
    $latestMessage = $conversation->latestMessage;
@endphp
<div style="
    margin-bottom: 16px;
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    background: #ffffff;
    transition: all 0.2s ease;
" onmouseover="this.style.boxShadow='0 2px 4px rgba(93, 83, 94, 0.15)';" onmouseout="this.style.boxShadow='0 1px 2px rgba(93, 83, 94, 0.1)';">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">
            <a href="{{ route('company.messages.show', $conversation) }}" style="
                color: #90AFC5;
                text-decoration: none;
                transition: all 0.2s ease;
                position: relative;
            " onmouseover="this.style.color='#7FB3D3'; this.style.textDecoration='underline';" onmouseout="this.style.color='#90AFC5'; this.style.textDecoration='none';">
                {{ $item['title'] }}
            </a>
        </h3>
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
                ">応募者・送信先</th>
                <td style="
                    padding: 12px 0;
                    color: #2A3132;
                    font-size: 14px;
                ">{{ $conversation->user->name }}</td>
            </tr>
            @if($latestMessage)
                <tr>
                    <th style="
                        padding: 12px 0;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">最新メッセージ</th>
                    <td style="padding: 12px 0;">
                        <p style="margin: 0 0 4px 0; color: #2A3132; font-size: 14px; line-height: 1.5;">{{ \Str::limit($latestMessage->body, 100) }}</p>
                        <p style="margin: 0; font-size: 12px; color: #999999;">
                            {{ $latestMessage->created_at->format('Y年m月d日 H:i') }}
                            @if($latestMessage->sender_type === 'user' && $latestMessage->read_flg === 0)
                                <span style="color: #763626; margin-left: 8px; font-weight: 500;">未読</span>
                            @endif
                        </p>
                    </td>
                </tr>
            @else
                <tr>
                    <th style="
                        padding: 12px 0;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">最新メッセージ</th>
                    <td style="padding: 12px 0; color: #999999; font-size: 13px;">まだメッセージがありません</td>
                </tr>
            @endif
        </table>
        <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
            <a href="{{ route('company.messages.show', $conversation) }}" style="
                padding: 10px 24px;
                background: transparent;
                color: #5D535E;
                border: 1px solid #5D535E;
                border-radius: 20px;
                font-size: 13px;
                font-weight: 700;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.2s ease;
                position: relative;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                メッセージを見る
            </a>
        </div>
    </div>
</div>
@empty
<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    background: #ffffff;
">
    <div style="padding: 40px 24px; text-align: center;">
        <p style="margin: 0 0 20px 0; color: #999999; font-size: 14px;">まだメッセージはありません。</p>
        <div style="display: flex; gap: 12px; justify-content: center;">
            <a href="{{ route('company.applications.index') }}" style="
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
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                応募管理を見る
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
                スカウト送信一覧を見る
            </a>
        </div>
    </div>
</div>
@endforelse
@endsection

