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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #1a1a1a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">メッセージを送信</h3>
            <button type="button" id="startVideoCallBtn" style="
                padding: 8px 16px;
                background: #1a1a1a;
                color: #ffffff;
                border: none;
                border-radius: 4px;
                font-size: 13px;
                font-weight: 500;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 6px;
                transition: all 0.15s ease;
            " onmouseover="this.style.backgroundColor='#333333';" onmouseout="this.style.backgroundColor='#1a1a1a';">
                <span>ビデオ通話を開始する</span>
            </button>
        </div>
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

    <!-- ビデオ通話モーダル -->
    <div id="videoCallModal" style="
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.9);
        z-index: 10000;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    ">
        <div style="position: relative; width: 100%; height: 100%; max-width: 1200px; max-height: 800px; display: flex; align-items: center; justify-content: center;">
            <!-- リモートビデオ（相手の画面） -->
            <video id="remoteVideo" autoplay playsinline style="
                width: 100%;
                height: 100%;
                object-fit: contain;
                background: #000;
            "></video>
            
            <!-- ローカルビデオ（自分の画面） -->
            <video id="localVideo" autoplay playsinline style="
                position: absolute;
                bottom: 20px;
                right: 20px;
                width: 200px;
                height: 150px;
                object-fit: cover;
                border: 2px solid #fff;
                border-radius: 8px;
                background: #000;
            "></video>
        </div>
        
        <!-- コントロールボタン -->
        <div style="
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 16px;
            align-items: center;
        ">
            <button id="toggleVideoBtn" style="
                width: 50px;
                height: 50px;
                border-radius: 50%;
                border: none;
                background: rgba(255, 255, 255, 0.2);
                color: white;
                font-size: 20px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0;
            " title="ビデオON/OFF">
                <img id="videoIcon" src="{{ asset('images/カメラ.png') }}" alt="ビデオON/OFF" style="width: 24px; height: 24px; object-fit: contain; object-position: center;">
            </button>
            
            <button id="toggleMuteBtn" style="
                width: 50px;
                height: 50px;
                border-radius: 50%;
                border: none;
                background: rgba(255, 255, 255, 0.2);
                color: white;
                font-size: 20px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0;
            " title="ミュート">
                <img id="muteIcon" src="{{ asset('images/スピーカー.png') }}" alt="ミュート" style="width: 24px; height: 24px; object-fit: contain; object-position: center;">
            </button>
            
            <button id="endCallBtn" style="
                width: 60px;
                height: 60px;
                border-radius: 50%;
                border: none;
                background: #f44336;
                color: white;
                font-size: 24px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0;
            " title="通話終了">
                <img src="{{ asset('images/call.png') }}" alt="通話終了" style="width: 28px; height: 28px; object-fit: contain;">
            </button>
        </div>
    </div>

    @vite(['resources/js/app.js'])
    <meta name="pusher-key" content="{{ config('broadcasting.connections.pusher.key', env('PUSHER_APP_KEY', '')) }}">
    <meta name="pusher-cluster" content="{{ config('broadcasting.connections.pusher.options.cluster', env('PUSHER_APP_CLUSTER', 'mt1')) }}">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const conversationId = {{ $conversation->id }};
            const currentUserId = {{ Auth::id() }};
            // ベースパスを取得（Laravelのassetヘルパーから）
            const basePath = '{{ url("") }}'.replace(window.location.origin, '') || '';
            // グローバルに設定（Echoの初期化でも使用）
            window.BASE_PATH = basePath;
            // 画像パスを設定
            window.SPEAKER_IMAGE = '{{ asset("images/スピーカー.png") }}';
            window.SPEAKER_OFF_IMAGE = '{{ asset("images/スピーカーオフ.png") }}';
            window.CAMERA_IMAGE = '{{ asset("images/カメラ.png") }}';
            
            console.log('Initializing VideoCallManager:', { conversationId, currentUserId, basePath });
            
            // VideoCallManagerを初期化
            try {
                const videoCallManager = new window.VideoCallManager(conversationId, currentUserId, basePath);
            
            // ビデオ通話開始ボタン
            document.getElementById('startVideoCallBtn').addEventListener('click', function() {
                videoCallManager.startCall();
            });
            
            // コントロールボタン
            document.getElementById('endCallBtn').addEventListener('click', function() {
                videoCallManager.endCall();
            });
            
            document.getElementById('toggleMuteBtn').addEventListener('click', function() {
                videoCallManager.toggleMute();
            });
            
            document.getElementById('toggleVideoBtn').addEventListener('click', function() {
                videoCallManager.toggleVideo();
            });
            } catch (error) {
                console.error('Failed to initialize VideoCallManager:', error);
                alert('ビデオ通話機能の初期化に失敗しました。コンソールを確認してください。');
            }
        });
    </script>
@endsection

