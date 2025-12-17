@extends('layouts.company')

@section('title', '応募詳細')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">応募詳細</h1>
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('company.messages.create-from-application', $application) }}" style="
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
            position: relative;
        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
            メッセージを送る
        </a>
        <a href="{{ route('company.applications.index') }}" style="
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
            一覧に戻る
        </a>
    </div>
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
    margin-bottom: 24px;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">応募情報</h3>
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
                ">応募日時</th>
                <td style="
                    padding: 12px 0;
                    color: #2A3132;
                    font-size: 14px;
                ">{{ $application->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f5f5f5;">
                <th style="
                    padding: 12px 0;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">求人タイトル</th>
                <td style="
                    padding: 12px 0;
                    color: #2A3132;
                    font-size: 14px;
                ">
                    <a href="{{ route('jobs.show', $application->jobPost) }}" target="_blank" style="
                        color: #90AFC5;
                        text-decoration: none;
                        transition: color 0.2s ease;
                    " onmouseover="this.style.color='#7FB3D3'; this.style.textDecoration='underline';" onmouseout="this.style.color='#90AFC5'; this.style.textDecoration='none';">
                        {{ $application->jobPost->title }}
                    </a>
                </td>
            </tr>
            <tr style="border-bottom: 1px solid #f5f5f5;">
                <th style="
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
                ">{{ $application->user->name }}</td>
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
                ">{{ $application->user->email }}</td>
            </tr>
            <tr>
                <th style="
                    padding: 12px 0;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    vertical-align: top;
                ">応募メッセージ</th>
                <td style="
                    padding: 12px 0;
                    color: #2A3132;
                    font-size: 14px;
                    white-space: pre-wrap;
                    line-height: 1.6;
                ">{{ $application->message }}</td>
            </tr>
        </table>
    </div>
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">ステータス変更</h3>
    </div>
    <form action="{{ route('company.applications.update-status', $application) }}" method="POST" style="padding: 24px;">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 20px;">
            <label for="status" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                ステータス
            </label>
            <select id="status" name="status" required style="
                width: 100%;
                padding: 12px 16px;
                border: 1px solid #e8e8e8;
                border-radius: 12px;
                font-size: 14px;
                font-family: inherit;
                color: #2A3132;
                background: #fafafa;
                transition: all 0.2s ease;
                box-sizing: border-box;
            " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
                <option value="1" {{ $application->status == 1 ? 'selected' : '' }}>応募済</option>
                <option value="2" {{ $application->status == 2 ? 'selected' : '' }}>書類選考中</option>
                <option value="3" {{ $application->status == 3 ? 'selected' : '' }}>面接中</option>
                <option value="4" {{ $application->status == 4 ? 'selected' : '' }}>内定</option>
                <option value="5" {{ $application->status == 5 ? 'selected' : '' }}>不採用</option>
                <option value="9" {{ $application->status == 9 ? 'selected' : '' }}>キャンセル</option>
            </select>
        </div>

        <div style="display: flex; justify-content: flex-end;">
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
                ステータスを更新
            </button>
        </div>
    </form>
</div>
@endsection

