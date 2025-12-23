@extends('layouts.app')

@section('title', 'スカウト詳細 | Bellbi')

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
            <li><a href="{{ route('mypage.scouts.index') }}" class="sidebar-menu-link active">スカウト受信</a></li>
            <li><a href="{{ route('mypage.messages.index') }}" class="sidebar-menu-link">メッセージ</a></li>
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
        <h1 class="page-title">{{ $scout->subject }}</h1>
        <p class="page-lead">
            <a href="{{ route('mypage.scouts.index') }}" style="
                padding: 8px 16px;
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
                display: inline-block;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                一覧に戻る
            </a>
        </p>
    </div>

    <div class="job-detail-card">
        <h3 style="margin-top: 0;">スカウト内容</h3>
        <table class="company-table">
            <tr>
                <th style="width: 150px;">企業名</th>
                <td>{{ $scout->fromCompany->name }}
                    @if($scout->fromStore)
                        / {{ $scout->fromStore->name }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>受信日時</th>
                <td>{{ $scout->created_at->format('Y年m月d日 H:i') }}</td>
            </tr>
            <tr>
                <th>ステータス</th>
                <td>
                    @if($scout->status === 1) 未読
                    @elseif($scout->status === 2) 既読
                    @elseif($scout->status === 3) 返信済
                    @elseif($scout->status === 9) クローズ
                    @endif
                </td>
            </tr>
        </table>

        <div style="margin-top: 20px;">
            <h4>メッセージ</h4>
            <div style="white-space: pre-wrap; background-color: #f9fafb; padding: 16px; border-radius: 8px; font-size: 14px; line-height: 1.7;">{{ $scout->body }}</div>
        </div>
        
        <div style="margin-top: 20px;">
            <a href="{{ route('mypage.messages.create-from-scout', $scout) }}" style="
                padding: 12px 32px;
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
                display: inline-block;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                メッセージでやりとりする
            </a>
        </div>
    </div>

    @if($scout->status !== 3 && $scout->status !== 9)
    <div class="job-apply">
        <h3 class="job-apply-title">このスカウトに返信する</h3>
        <form action="{{ route('mypage.scouts.reply', $scout) }}" method="POST" class="job-apply-form">
            @csrf
            <div class="form-group">
                <label for="reply_body">返信メッセージ</label>
                <textarea id="reply_body" name="reply_body" required placeholder="企業へのメッセージを入力してください">{{ old('reply_body') }}</textarea>
                @error('reply_body')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-actions">
                <button type="submit" style="
                    padding: 12px 32px;
                    background: #5D535E;
                    color: #ffffff;
                    border: none;
                    border-radius: 24px;
                    font-size: 14px;
                    font-weight: 700;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    position: relative;
                " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                    返信する
                </button>
            </div>
        </form>
    </div>
    @endif
@endsection


