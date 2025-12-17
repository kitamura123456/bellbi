@extends('layouts.company')

@section('title', '応募管理')

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">応募管理</h1>
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
    overflow-x: auto;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">応募一覧</h3>
    </div>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #fafafa; border-bottom: 1px solid #e8e8e8;">
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">応募日</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">求人タイトル</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">応募者</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">ステータス</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $application)
            <tr style="border-bottom: 1px solid #f5f5f5;" onmouseover="this.style.background='#fafafa';" onmouseout="this.style.background='#ffffff';">
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $application->created_at->format('Y-m-d H:i') }}</td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $application->jobPost->title }}</td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $application->user->name }}</td>
                <td style="padding: 12px 16px;">
                    @if($application->status === 1)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #5D535E;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">応募済</span>
                    @elseif($application->status === 2)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #90AFC5;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">書類選考中</span>
                    @elseif($application->status === 3)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #90AFC5;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">面接中</span>
                    @elseif($application->status === 4)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #336B87;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">内定</span>
                    @elseif($application->status === 5)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #763626;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">不採用</span>
                    @elseif($application->status === 9)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #999999;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">キャンセル</span>
                    @endif
                </td>
                <td style="padding: 12px 16px;">
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('company.applications.show', $application) }}" style="
                            padding: 6px 16px;
                            background: transparent;
                            color: #5D535E;
                            border: 1px solid #5D535E;
                            border-radius: 16px;
                            font-size: 12px;
                            font-weight: 700;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                            text-decoration: none;
                            transition: all 0.2s ease;
                        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                            詳細
                        </a>
                        <a href="{{ route('company.messages.create-from-application', $application) }}" style="
                            padding: 6px 16px;
                            background: #5D535E;
                            color: #ffffff;
                            border: none;
                            border-radius: 16px;
                            font-size: 12px;
                            font-weight: 700;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                            text-decoration: none;
                            transition: all 0.2s ease;
                            position: relative;
                        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                            メッセージ
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding: 40px 16px; text-align: center; color: #999999; font-size: 14px;">応募がありません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

