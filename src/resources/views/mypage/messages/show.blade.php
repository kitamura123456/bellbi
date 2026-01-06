@extends('layouts.app')

@section('title', 'メッセージ詳細 | Bellbi')

@section('sidebar')
    <div class="sidebar-card">
        <div class="mypage-menu-header" style="display: flex; align-items: center; justify-content: space-between; cursor: pointer;" onclick="if(window.innerWidth <= 768) toggleMypageMenu()">
            <h3 class="sidebar-title" style="margin: 0;">メニュー</h3>
            <span class="mypage-toggle-icon" style="
                display: none;
                font-size: 16px;
                color: #1a1a1a;
                transition: transform 0.3s ease;
                user-select: none;
                flex-shrink: 0;
                margin-left: 8px;
            ">▼</span>
        </div>
        <ul class="sidebar-menu mypage-menu-list" id="mypageMenuList">
            <li><a href="{{ route('mypage') }}" class="sidebar-menu-link">応募履歴</a></li>
            <li><a href="{{ route('mypage.scouts.index') }}" class="sidebar-menu-link">スカウト受信</a></li>
            <li><a href="{{ route('mypage.messages.index') }}" class="sidebar-menu-link active">メッセージ</a></li>
            <li><a href="{{ route('mypage.scout-profile.edit') }}" class="sidebar-menu-link">スカウト用プロフィール</a></li>
            <li><a href="{{ route('mypage.reservations.index') }}" class="sidebar-menu-link">予約履歴</a></li>
            <li><a href="{{ route('mypage.orders.index') }}" class="sidebar-menu-link">注文履歴</a></li>
        </ul>
    </div>
    <style>
        /* デスクトップ版の固定メニュー */
        .sidebar {
            position: sticky !important;
            top: 0 !important;
            align-self: flex-start !important;
            z-index: 40 !important;
            max-height: 100vh !important;
            overflow-y: auto !important;
        }
        .sidebar-card {
            position: sticky !important;
            top: 0 !important;
        }
        .sidebar-menu,
        .mypage-menu-list {
            position: relative !important;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: sticky !important;
                top: 0 !important;
                z-index: 50 !important;
                background: #ffffff !important;
                margin-bottom: 0 !important;
            }
            .sidebar-card {
                position: sticky !important;
                top: 0 !important;
                z-index: 50 !important;
                background: #ffffff !important;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05) !important;
                margin-bottom: 0 !important;
                padding: 8px 12px !important;
            }
            .sidebar-menu,
            .mypage-menu-list {
                position: relative !important;
            }
            .mypage-menu-header {
                padding: 4px 0 !important;
                margin-bottom: 0 !important;
            }
            .sidebar-title {
                font-size: 11px !important;
                margin-bottom: 0 !important;
            }
            .mypage-toggle-icon {
                display: block !important;
                font-size: 14px !important;
            }
            .mypage-menu-list {
                display: none;
                margin-top: 8px;
            }
            .mypage-menu-list.active {
                display: block !important;
            }
            .mypage-toggle-icon.active {
                transform: rotate(180deg);
            }
            .container.main-inner {
                flex-direction: column !important;
            }
            .sidebar {
                order: -1 !important;
            }
            .page-header {
                margin-top: 24px !important;
            }
        }
    </style>
    <script>
        function toggleMypageMenu() {
            const menu = document.getElementById('mypageMenuList');
            const icon = document.querySelector('.mypage-toggle-icon');
            
            if (menu && icon) {
                menu.classList.toggle('active');
                icon.classList.toggle('active');
            }
        }
    </script>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <p class="page-lead">
            <a href="{{ route('mypage.messages.index') }}" style="
                padding: 8px 16px;
                background: #1a1a1a;
                color: #ffffff;
                border: none;
                border-radius: 4px;
                font-size: 13px;
                font-weight: 500;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.15s ease;
                display: inline-block;
            " onmouseover="this.style.backgroundColor='#333333';" onmouseout="this.style.backgroundColor='#1a1a1a';">
                一覧に戻る
            </a>
        </p>
    </div>

    <div class="job-detail-card">
        <h3 style="margin-top: 0; margin-bottom: 16px; font-size: 16px; font-weight: 600; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">企業情報</h3>
        <table class="company-table">
            <tr>
                <th style="width: 150px;">企業名</th>
                <td>{{ $conversation->company->name }}</td>
            </tr>
            @if($conversation->jobApplication)
                <tr>
                    <th>関連応募</th>
                    <td>
                        <a href="{{ route('jobs.show', $conversation->jobApplication->jobPost) }}" style="color: #1a1a1a; text-decoration: none; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                            {{ $conversation->jobApplication->jobPost->title }}
                        </a>
                    </td>
                </tr>
            @elseif($conversation->scoutMessage)
                <tr>
                    <th>関連スカウト</th>
                    <td>{{ $conversation->scoutMessage->subject }}</td>
                </tr>
            @endif
        </table>
    </div>

    <div class="job-detail-card" style="margin-top: 24px;">
        <h3 style="margin-top: 0; margin-bottom: 16px; font-size: 16px; font-weight: 600; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">メッセージ履歴</h3>
        
        <div style="max-height: 600px; overflow-y: auto; padding: 20px; background-color: #fafafa; border-radius: 0;">
            @forelse($messages as $message)
            <div style="margin-bottom: 16px; display: flex; justify-content: {{ $message->sender_type === 'user' ? 'flex-end' : 'flex-start' }}; align-items: flex-start;">
                <div style="max-width: 70%; display: flex; flex-direction: column; {{ $message->sender_type === 'user' ? 'align-items: flex-end;' : 'align-items: flex-start;' }}">
                    <div style="font-size: 11px; color: #999; margin-bottom: 4px; padding: 0 2px;">
                        @if($message->sender_type === 'user')
                            {{ $conversation->user->name ?? 'あなた' }}
                        @else
                            {{ $conversation->company->name }}
                        @endif
                        <span style="margin-left: 6px;">{{ $message->created_at->format('Y年m月d日 H:i') }}</span>
                    </div>
                    <div style="display: inline-block; background-color: {{ $message->sender_type === 'user' ? '#1a1a1a' : '#ffffff' }}; color: {{ $message->sender_type === 'user' ? '#ffffff' : '#1a1a1a' }}; padding: 10px 14px; border-radius: 4px; white-space: pre-wrap; word-wrap: break-word; line-height: 1.6; font-size: 14px; text-align: left; {{ $message->sender_type === 'user' ? '' : 'border: 1px solid #e0e0e0;' }}; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                        {{ $message->body }}
                    </div>
                    @if($message->attachments->count() > 0)
                        <div style="margin-top: 8px;">
                            @foreach($message->attachments as $attachment)
                                <a href="{{ $attachment->url }}" target="_blank" style="display: inline-block; padding: 6px 12px; background-color: #f5f5f5; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 12px; color: #1a1a1a; text-decoration: none; margin-right: 8px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                                    📎 {{ $attachment->file_name }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <p style="text-align: center; color: #666; padding: 40px 0; font-size: 14px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">まだメッセージがありません</p>
            @endforelse
        </div>
    </div>

    <div class="job-detail-card" style="margin-top: 24px;">
        <h3 style="margin-top: 0; margin-bottom: 16px; font-size: 16px; font-weight: 600; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">メッセージを送信</h3>
        <form action="{{ route('mypage.messages.store', $conversation) }}" method="POST" class="company-form" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="body">メッセージ <span class="required">必須</span></label>
                <textarea id="body" name="body" required placeholder="メッセージを入力してください" rows="5">{{ old('body') }}</textarea>
                @error('body')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="attachments">ファイル添付（最大10MB、複数選択可）</label>
                <input type="file" id="attachments" name="attachments[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
                <small style="display: block; margin-top: 4px; color: #333333; font-size: 12px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">対応形式: PDF, Word, 画像ファイル</small>
                @error('attachments.*')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-actions">
                <button type="submit" style="
                    padding: 10px 24px;
                    background: #1a1a1a;
                    color: #ffffff;
                    border: none;
                    border-radius: 4px;
                    font-size: 13px;
                    font-weight: 500;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    cursor: pointer;
                    transition: all 0.15s ease;
                " onmouseover="this.style.backgroundColor='#333333';" onmouseout="this.style.backgroundColor='#1a1a1a';">
                    送信する
                </button>
            </div>
        </form>
    </div>
@endsection

