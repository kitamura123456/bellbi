@extends('layouts.app')

@section('title', 'スカウト詳細 | Bellbi')

@section('sidebar')
    <div class="sidebar-card">
        <h3 class="sidebar-title">メニュー</h3>
        <ul class="sidebar-menu">
            <li><a href="{{ route('mypage') }}" class="sidebar-menu-link">応募履歴</a></li>
            <li><a href="{{ route('mypage.scouts.index') }}" class="sidebar-menu-link active">スカウト受信</a></li>
            <li><a href="{{ route('mypage.messages.index') }}" class="sidebar-menu-link">メッセージ</a></li>
            <li><a href="{{ route('mypage.scout-profile.edit') }}" class="sidebar-menu-link">スカウト用プロフィール</a></li>
            <li><a href="{{ route('mypage.reservations.index') }}" class="sidebar-menu-link">予約履歴</a></li>
        </ul>
    </div>
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


