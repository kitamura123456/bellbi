@extends('layouts.company')

@section('title', 'メッセージ')

@section('content')
<div style="margin-bottom: 24px; margin-top: 48px;">
    <h1 style="margin: 0 0 8px 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">メッセージ</h1>
    <p style="margin: 0; font-size: 14px; color: #666666;">応募者・スカウト送信先とのメッセージ一覧です。</p>
</div>

@forelse($conversationsWithInfo as $item)
@php
    $conversation = $item['conversation'];
    $latestMessage = $conversation->latestMessage;
@endphp
<div class="message-card-desktop" style="
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
        <table class="message-table" style="width: 100%; border-collapse: collapse;">
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

<!-- スマホ用カードレイアウト -->
<div class="message-card-mobile">
    <div class="message-card-mobile-header">
        <h3 class="message-card-mobile-title">
            <a href="{{ route('company.messages.show', $conversation) }}">
                {{ $item['title'] }}
            </a>
        </h3>
    </div>
    <div class="message-card-mobile-body">
        <div class="message-card-mobile-row">
            <span class="message-card-mobile-label">応募者・送信先</span>
            <span class="message-card-mobile-value">{{ $conversation->user->name }}</span>
        </div>
        <div class="message-card-mobile-row">
            <span class="message-card-mobile-label">最新メッセージ</span>
            <div class="message-card-mobile-message">
                @if($latestMessage)
                    <p class="message-card-mobile-text">{{ \Str::limit($latestMessage->body, 100) }}</p>
                    <p class="message-card-mobile-time">
                        {{ $latestMessage->created_at->format('Y年m月d日 H:i') }}
                        @if($latestMessage->sender_type === 'user' && $latestMessage->read_flg === 0)
                            <span class="message-card-mobile-unread">未読</span>
                        @endif
                    </p>
                @else
                    <p class="message-card-mobile-empty">まだメッセージがありません</p>
                @endif
            </div>
        </div>
    </div>
    <div class="message-card-mobile-actions">
        <a href="{{ route('company.messages.show', $conversation) }}" class="message-card-mobile-btn">
            メッセージを見る
        </a>
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

<style>
/* スマホ用カードレイアウト（デフォルトは非表示） */
.message-card-mobile {
    display: none;
}

.message-card-mobile {
    background: #ffffff;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    width: 100%;
    box-sizing: border-box;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.message-card-mobile-header {
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e8e8e8;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.message-card-mobile-title {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #5D535E;
    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.message-card-mobile-title a {
    color: #90AFC5;
    text-decoration: none;
    transition: color 0.2s ease;
    word-wrap: break-word;
    overflow-wrap: break-word;
    display: block;
}

.message-card-mobile-title a:hover {
    color: #7FB3D3;
    text-decoration: underline;
}

.message-card-mobile-body {
    display: grid;
    gap: 12px;
    margin-bottom: 12px;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.message-card-mobile-row {
    display: flex;
    flex-direction: column;
    gap: 4px;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.message-card-mobile-label {
    color: #6b7280;
    font-weight: 700;
    font-size: 13px;
    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
}

.message-card-mobile-value {
    color: #111827;
    font-weight: 600;
    font-size: 14px;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.message-card-mobile-message {
    margin-top: 4px;
    word-wrap: break-word;
    overflow-wrap: break-word;
    width: 100%;
}

.message-card-mobile-text {
    margin: 0 0 4px 0;
    color: #2A3132;
    font-size: 14px;
    line-height: 1.5;
    word-wrap: break-word;
    overflow-wrap: break-word;
    word-break: break-word;
    white-space: normal;
}

.message-card-mobile-time {
    margin: 0;
    font-size: 12px;
    color: #999999;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.message-card-mobile-unread {
    color: #763626;
    margin-left: 8px;
    font-weight: 500;
    white-space: nowrap;
}

.message-card-mobile-empty {
    margin: 0;
    color: #999999;
    font-size: 13px;
}

.message-card-mobile-actions {
    padding-top: 12px;
    border-top: 1px solid #e8e8e8;
    width: 100%;
    box-sizing: border-box;
}

.message-card-mobile-btn {
    display: block;
    width: 100%;
    max-width: 100%;
    padding: 12px 16px;
    background: transparent;
    color: #5D535E;
    border: 1px solid #5D535E;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
    text-decoration: none;
    text-align: center;
    transition: all 0.2s ease;
    box-sizing: border-box;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.message-card-mobile-btn:hover {
    background: #5D535E;
    color: #ffffff;
}

/* スマホ用レスポンシブデザイン */
@media (max-width: 768px) {
    div[style*="margin-top: 48px"] {
        margin-top: 24px !important;
    }

    div[style*="margin-top: 48px"] h1 {
        font-size: 20px !important;
        margin-bottom: 6px !important;
    }

    div[style*="margin-top: 48px"] p {
        font-size: 13px !important;
    }

    .message-card-desktop {
        display: none;
    }

    .message-card-mobile {
        display: block;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .message-table {
        display: none;
    }

    .message-card-desktop {
        display: none !important;
    }

    div[style*="padding: 40px 24px"] {
        padding: 32px 16px !important;
    }

    div[style*="padding: 40px 24px"] p {
        font-size: 13px !important;
        margin-bottom: 16px !important;
    }

    div[style*="display: flex; gap: 12px; justify-content: center"] {
        flex-direction: column !important;
        gap: 8px !important;
    }

    div[style*="display: flex; gap: 12px; justify-content: center"] a {
        width: 100%;
        text-align: center;
        font-size: 13px;
        padding: 12px 16px;
    }
}

@media (max-width: 480px) {
    div[style*="margin-top: 48px"] {
        margin-top: 20px !important;
    }

    div[style*="margin-top: 48px"] h1 {
        font-size: 18px !important;
    }

    .message-card-mobile {
        padding: 12px;
        margin-bottom: 10px;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .message-card-mobile-header {
        padding-bottom: 10px;
        margin-bottom: 10px;
    }

    .message-card-mobile-title {
        font-size: 16px;
    }

    .message-card-mobile-body {
        gap: 10px;
        margin-bottom: 10px;
    }

    .message-card-mobile-label {
        font-size: 12px;
    }

    .message-card-mobile-value {
        font-size: 13px;
    }

    .message-card-mobile-text {
        font-size: 13px;
        line-height: 1.4;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .message-card-mobile-time {
        font-size: 11px;
    }

    .message-card-mobile-btn {
        padding: 10px 14px;
        font-size: 12px;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    div[style*="padding: 40px 24px"] {
        padding: 24px 12px !important;
    }
}
</style>
@endsection

