@extends('layouts.company')

@section('title', 'メッセージ詳細')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">{{ $title }}</h1>
    <a href="{{ route('company.messages.index') }}" style="
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
    " onmouseover="this.style.background='#5D535E'; this.style.color='#ffffff'; this.style.borderColor='#5D535E';" onmouseout="this.style.background='transparent'; this.style.color='#5D535E'; this.style.borderColor='#5D535E';">
        一覧に戻る
    </a>
</div>

<div style="padding: 0; border: none; box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1); background: #ffffff; margin-bottom: 24px;">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8; background: #ffffff;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">応募者情報</h3>
    </div>
    <div style="padding: 24px; background: #ffffff;">
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
                ">応募者名</th>
                <td style="
                    padding: 12px 0;
                    color: #2A3132;
                    font-size: 14px;
                ">{{ $conversation->user->name }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f5f5f5;">
                <th style="
                    padding: 12px 0;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">メールアドレス</th>
                <td style="
                    padding: 12px 0;
                    color: #2A3132;
                    font-size: 14px;
                ">{{ $conversation->user->email }}</td>
            </tr>
            @if($conversation->jobApplication)
                <tr>
                    <th style="
                        padding: 12px 0;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">関連応募</th>
                    <td style="
                        padding: 12px 0;
                        color: #2A3132;
                        font-size: 14px;
                    ">
                        <a href="{{ route('company.applications.show', $conversation->jobApplication) }}" style="
                            color: #90AFC5;
                            text-decoration: none;
                            font-weight: 400;
                            transition: all 0.2s ease;
                            position: relative;
                        " onmouseover="this.style.color='#7FB3D3'; this.style.textDecoration='underline';" onmouseout="this.style.color='#90AFC5'; this.style.textDecoration='none';">
                            {{ $conversation->jobApplication->jobPost->title }}
                        </a>
                    </td>
                </tr>
            @elseif($conversation->scoutMessage)
                <tr>
                    <th style="
                        padding: 12px 0;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">関連スカウト</th>
                    <td style="
                        padding: 12px 0;
                        color: #2A3132;
                        font-size: 14px;
                    ">{{ $conversation->scoutMessage->subject }}</td>
                </tr>
            @endif
        </table>
    </div>
</div>

<div style="padding: 0; border: none; box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1); background: #ffffff; margin-bottom: 24px;">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8; background: #ffffff;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">メッセージ履歴</h3>
    </div>
    
    <style>
        .message-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .message-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .message-scroll::-webkit-scrollbar-thumb {
            background: #d0d0d0;
            border-radius: 3px;
        }
        .message-scroll::-webkit-scrollbar-thumb:hover {
            background: #b0b0b0;
        }
    </style>
    
    <div class="message-scroll" style="
        max-height: 600px; 
        overflow-y: auto; 
        padding: 20px 16px; 
        background-color: #fafafa;
        min-height: 400px;
    ">
        @forelse($messages as $message)
        @php $isCompany = $message->sender_type === 'company'; @endphp
        <div style="margin-bottom: 12px; display: flex; justify-content: {{ $isCompany ? 'flex-end' : 'flex-start' }}; align-items: flex-end;">
            @if(!$isCompany)
            <div style="
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: #5D535E;
                margin-right: 8px;
                flex-shrink: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                color: #ffffff;
                font-weight: 700;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                {{ mb_substr($conversation->user->name, 0, 1) }}
            </div>
            @endif
            
            <div style="display: flex; flex-direction: column; max-width: 60%; min-width: 80px;">
                <div style="
                    background-color: {{ $isCompany ? '#5D535E' : '#ffffff' }};
                    color: {{ $isCompany ? '#ffffff' : '#2A3132' }};
                    padding: 10px 14px;
                    border-radius: {{ $isCompany ? '18px 18px 4px 18px' : '18px 18px 18px 4px' }};
                    white-space: pre-wrap;
                    word-break: break-word;
                    line-height: 1.5;
                    font-size: 14px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    box-shadow: {{ $isCompany ? 'none' : '0 1px 2px rgba(93, 83, 94, 0.08)' }};
                    {{ $isCompany ? '' : 'border: 1px solid #e8e8e8;' }}
                    position: relative;
                " onmouseover="if(!{{ $isCompany ? 'true' : 'false' }}) { this.style.borderColor='#90AFC5'; }" onmouseout="if(!{{ $isCompany ? 'true' : 'false' }}) { this.style.borderColor='#e8e8e8'; }">
                    {{ $message->body }}
                </div>
                
                <div style="
                    font-size: 11px;
                    color: #999999;
                    margin-top: 4px;
                    padding: 0 4px;
                    text-align: {{ $isCompany ? 'right' : 'left' }};
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">
                    {{ $message->created_at->format('H:i') }}
                </div>
                
                @if($message->attachments->count() > 0)
                    <div style="margin-top: 6px; display: flex; flex-wrap: wrap; gap: 4px; justify-content: {{ $isCompany ? 'flex-end' : 'flex-start' }};">
                        @foreach($message->attachments as $attachment)
                            <a href="{{ $attachment->url }}" target="_blank" style="
                                display: inline-flex;
                                align-items: center;
                                gap: 4px;
                                padding: 6px 10px;
                                background-color: {{ $isCompany ? 'rgba(255,255,255,0.15)' : '#f5f5f5' }};
                                border-radius: 12px;
                                font-size: 11px;
                                color: {{ $isCompany ? '#ffffff' : '#5D535E' }};
                                text-decoration: none;
                                border: {{ $isCompany ? '1px solid rgba(255,255,255,0.2)' : '1px solid #e8e8e8' }};
                                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                                transition: all 0.2s ease;
                            " onmouseover="this.style.borderColor='#90AFC5'; {{ $isCompany ? "this.style.borderColor='rgba(255,255,255,0.4)';" : '' }}" onmouseout="this.style.borderColor='{{ $isCompany ? 'rgba(255,255,255,0.2)' : '#e8e8e8' }}';">
                                <span>📎</span>
                                <span style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $attachment->file_name }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
            
            @if($isCompany)
            <div style="
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: #5D535E;
                margin-left: 8px;
                flex-shrink: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                color: #ffffff;
                font-weight: 700;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                {{ mb_substr($company->name, 0, 1) }}
            </div>
            @endif
        </div>
        @empty
        <div style="text-align: center; padding: 60px 20px; color: #999999;">
            <p style="margin: 0; font-size: 14px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">まだメッセージがありません</p>
        </div>
        @endforelse
    </div>
</div>

<div style="padding: 0; border: none; box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1); background: #ffffff;">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8; background: #ffffff;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">メッセージを送信</h3>
    </div>
    
    <form action="{{ route('company.messages.store', $conversation) }}" method="POST" class="company-form" enctype="multipart/form-data" style="padding: 24px; background: #ffffff;">
        @csrf
        <div style="margin-bottom: 20px;">
            <label for="body" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                メッセージ <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <textarea 
                id="body" 
                name="body" 
                required 
                placeholder="メッセージを入力してください..." 
                rows="4"
                style="
                    width: 100%;
                    padding: 12px 16px;
                    border: 1px solid #e8e8e8;
                    border-radius: 8px;
                    font-size: 14px;
                    font-family: inherit;
                    line-height: 1.5;
                    color: #2A3132;
                    background: #fafafa;
                    transition: all 0.2s ease;
                    resize: vertical;
                    box-sizing: border-box;
                "
                onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff'; this.style.outline='none';"
                onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';"
            >{{ old('body') }}</textarea>
            @error('body')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>
        
        <div style="margin-bottom: 24px;">
            <label for="attachments" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                ファイル添付
            </label>
            <div style="
                padding: 12px;
                border: 1px dashed #d0d0d0;
                border-radius: 8px;
                background: #fafafa;
                transition: all 0.2s ease;
            " onmouseover="this.style.borderColor='#90AFC5'; this.style.background='#f5f5f5';" onmouseout="this.style.borderColor='#d0d0d0'; this.style.background='#fafafa';">
                <input 
                    type="file" 
                    id="attachments" 
                    name="attachments[]" 
                    multiple 
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif"
                    style="
                        width: 100%;
                        font-size: 13px;
                        color: #5D535E;
                        cursor: pointer;
                    "
                >
            </div>
            <small style="display: block; margin-top: 6px; color: #999999; font-size: 12px;">
                最大10MB、複数選択可 | PDF, Word, 画像ファイル
            </small>
            @error('attachments.*')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>
        
        <div style="display: flex; justify-content: flex-end;">
            <button 
                type="submit" 
                style="
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
                    overflow: hidden;
                "
                onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';"
                onmouseout="this.style.boxShadow='none';"
            >
                送信する
            </button>
        </div>
    </form>
</div>
@endsection

