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
            <a href="{{ route('mypage.scouts.index') }}" class="btn-secondary btn-sm">一覧に戻る</a>
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
            <a href="{{ route('mypage.messages.create-from-scout', $scout) }}" class="btn-primary">メッセージでやりとりする</a>
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
                <button type="submit" class="btn-primary">返信する</button>
            </div>
        </form>
    </div>
    @endif
@endsection


