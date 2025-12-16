@extends('layouts.app')

@section('title', 'メッセージ詳細 | Bellbi')

@section('sidebar')
    <div class="sidebar-card">
        <h3 class="sidebar-title">メニュー</h3>
        <ul class="sidebar-menu">
            <li><a href="{{ route('mypage') }}" class="sidebar-menu-link">応募履歴</a></li>
            <li><a href="{{ route('mypage.scouts.index') }}" class="sidebar-menu-link">スカウト受信</a></li>
            <li><a href="{{ route('mypage.messages.index') }}" class="sidebar-menu-link active">メッセージ</a></li>
            <li><a href="{{ route('mypage.scout-profile.edit') }}" class="sidebar-menu-link">スカウト用プロフィール</a></li>
            <li><a href="{{ route('mypage.reservations.index') }}" class="sidebar-menu-link">予約履歴</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <p class="page-lead">
            <a href="{{ route('mypage.messages.index') }}" class="btn-secondary btn-sm">一覧に戻る</a>
        </p>
    </div>

    <div class="job-detail-card">
        <h3 style="margin-top: 0;">企業情報</h3>
        <table class="company-table">
            <tr>
                <th style="width: 150px;">企業名</th>
                <td>{{ $conversation->company->name }}</td>
            </tr>
            @if($conversation->jobApplication)
                <tr>
                    <th>関連応募</th>
                    <td>
                        <a href="{{ route('jobs.show', $conversation->jobApplication->jobPost) }}">
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
        <h3 style="margin-top: 0;">メッセージ履歴</h3>
        
        <div style="max-height: 600px; overflow-y: auto; padding: 16px; background-color: #f9fafb; border-radius: 16px;">
            @forelse($messages as $message)
            <div style="margin-bottom: 12px; {{ $message->sender_type === 'user' ? 'text-align: right;' : '' }}">
                <div style="display: inline-block; max-width: 70%; text-align: left;">
                    <div style="font-size: 11px; color: #9ca3af; margin-bottom: 4px; padding: 0 2px;">
                        @if($message->sender_type === 'user')
                            {{ $conversation->user->name ?? 'あなた' }}
                        @else
                            {{ $conversation->company->name }}
                        @endif
                        <span style="margin-left: 6px;">{{ $message->created_at->format('m/d H:i') }}</span>
                    </div>
                    <div style="display: inline-block; background-color: {{ $message->sender_type === 'user' ? '#3b82f6' : '#ffffff' }}; color: {{ $message->sender_type === 'user' ? '#ffffff' : '#111827' }}; padding: 8px 12px; border-radius: 12px; white-space: pre-wrap; word-wrap: break-word; line-height: 1.4; font-size: 14px; text-align: left; {{ $message->sender_type === 'user' ? '' : 'border: 1px solid #e5e7eb;' }};">
                        {{ $message->body }}
                    </div>
                    @if($message->attachments->count() > 0)
                        <div style="margin-top: 8px;">
                            @foreach($message->attachments as $attachment)
                                <a href="{{ $attachment->url }}" target="_blank" style="display: inline-block; padding: 4px 8px; background-color: #e5e7eb; border-radius: 4px; font-size: 12px; color: #1f2937; text-decoration: none; margin-right: 8px;">
                                    📎 {{ $attachment->file_name }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <p style="text-align: center; color: #6b7280; padding: 40px 0;">まだメッセージがありません</p>
            @endforelse
        </div>
    </div>

    <div class="job-apply" style="margin-top: 24px;">
        <h3 class="job-apply-title">メッセージを送信</h3>
        <form action="{{ route('mypage.messages.store', $conversation) }}" method="POST" class="job-apply-form" enctype="multipart/form-data">
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
                <small style="display: block; margin-top: 4px; color: #6b7280;">対応形式: PDF, Word, 画像ファイル</small>
                @error('attachments.*')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">送信する</button>
            </div>
        </form>
    </div>
@endsection

