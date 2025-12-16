@extends('layouts.company')

@section('title', 'メッセージ詳細')

@section('content')
<div class="company-header">
    <h1 class="company-title">{{ $title }}</h1>
    <a href="{{ route('company.messages.index') }}" class="btn-secondary">一覧に戻る</a>
</div>

<div class="company-card">
    <h3 style="margin-top: 0;">応募者情報</h3>
    <table class="company-table">
        <tr>
            <th style="width: 150px;">応募者名</th>
            <td>{{ $conversation->user->name }}</td>
        </tr>
        <tr>
            <th>メールアドレス</th>
            <td>{{ $conversation->user->email }}</td>
        </tr>
        @if($conversation->jobApplication)
            <tr>
                <th>関連応募</th>
                <td>
                    <a href="{{ route('company.applications.show', $conversation->jobApplication) }}">
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

<div class="company-card" style="margin-top: 24px;">
    <h3 style="margin-top: 0;">メッセージ履歴</h3>
    
    <div style="max-height: 600px; overflow-y: auto; padding: 16px; background-color: #f9fafb; border-radius: 16px;">
        @forelse($messages as $message)
        @php $isCompany = $message->sender_type === 'company'; @endphp
        <div style="flex: ;margin-bottom: 12px; display: flex; justify-content: {{ $isCompany ? 'flex-end' : 'flex-start' }};">
            <div style="display: flex; flex-direction: column; align-items: {{ $isCompany ? 'flex-end' : 'flex-start' }}; max-width: 70%;">
                <div style="font-size: 11px; color: #9ca3af; margin-bottom: 4px; padding: 0 2px; text-align: {{ $isCompany ? 'right' : 'left' }}; width: 100%;">
                    @if($isCompany)
                        {{ $company->name }}
                    @else
                        {{ $conversation->user->name }}
                    @endif
                    <span style="margin-left: 6px;">{{ $message->created_at->format('m/d H:i') }}</span>
                </div>
                <div style="
    display: inline-flex;
    width: fit-content;
    max-width: 70%;
    background-color: {{ $isCompany ? '#3b82f6' : '#ffffff' }};
    color: {{ $isCompany ? '#ffffff' : '#111827' }};
    padding: 4px 8px;
    border-radius: 12px;
    white-space: pre-wrap;
    word-break: break-word;
    line-height: 1.4;
    font-size: 14px;
    text-align: left;
    align-items: center;
    {{ $isCompany ? '' : 'border: 1px solid #e5e7eb;' }}
">
    {{ $message->body }}
</div>
                @if($message->attachments->count() > 0)
                    <div style="margin-top: 6px; text-align: {{ $isCompany ? 'right' : 'left' }}; width: 100%;">
                        @foreach($message->attachments as $attachment)
                            <a href="{{ $attachment->url }}" target="_blank" style="display: inline-block; padding: 3px 8px; background-color: {{ $isCompany ? 'rgba(255,255,255,0.15)' : '#f3f4f6' }}; border-radius: 8px; font-size: 11px; color: {{ $isCompany ? '#ffffff' : '#374151' }}; text-decoration: none; margin-right: 6px;">
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

<div class="company-card" style="margin-top: 24px;">
    <h3 style="margin-top: 0;">メッセージを送信</h3>
    <form action="{{ route('company.messages.store', $conversation) }}" method="POST" class="company-form" enctype="multipart/form-data">
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

