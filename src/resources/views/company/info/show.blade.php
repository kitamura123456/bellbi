@extends('layouts.company')

@section('title', '会社情報')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">会社情報</h1>
    <a href="{{ route('company.info.edit') }}" style="
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
        編集する
    </a>
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">会社情報</h3>
    </div>
    <div style="padding: 24px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="border-bottom: 1px solid #f5f5f5;">
                <th style="
                    width: 200px;
                    padding: 12px 0;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">会社名</th>
                <td style="
                    padding: 12px 0;
                    color: #2A3132;
                    font-size: 14px;
                ">{{ $company->name }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f5f5f5;">
                <th style="
                    padding: 12px 0;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">担当者名</th>
                <td style="
                    padding: 12px 0;
                    color: #2A3132;
                    font-size: 14px;
                ">{{ $company->contact_name ?? '未登録' }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f5f5f5;">
                <th style="
                    padding: 12px 0;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">業種（大分類）</th>
                <td style="
                    padding: 12px 0;
                    color: #2A3132;
                    font-size: 14px;
                ">
                    @if($company->industry_type === 1) 美容
                    @elseif($company->industry_type === 2) 医療
                    @elseif($company->industry_type === 3) 歯科
                    @else その他
                    @endif
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
                ">業種（詳細）</th>
                <td style="
                    padding: 12px 0;
                    color: #2A3132;
                    font-size: 14px;
                ">{{ \App\Services\BusinessCategoryService::getCategoryName($company->business_category) ?? '未登録' }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f5f5f5;">
                <th style="
                    padding: 12px 0;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">郵便番号</th>
                <td style="
                    padding: 12px 0;
                    color: #2A3132;
                    font-size: 14px;
                ">{{ $company->postal_code ?? '未登録' }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f5f5f5;">
                <th style="
                    padding: 12px 0;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">住所</th>
                <td style="
                    padding: 12px 0;
                    color: #2A3132;
                    font-size: 14px;
                ">{{ $company->address ?? '未登録' }}</td>
            </tr>
            <tr>
                <th style="
                    padding: 12px 0;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">電話番号</th>
                <td style="
                    padding: 12px 0;
                    color: #2A3132;
                    font-size: 14px;
                ">{{ $company->tel ?? '未登録' }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection

