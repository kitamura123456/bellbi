@extends('layouts.company')

@section('title', '会社情報')

@section('content')
<div class="company-header">
    <h1 class="company-title">会社情報</h1>
    <a href="{{ route('company.info.edit') }}" class="btn-primary">編集する</a>
</div>

<div class="company-card">
    <table class="company-table">
        <tr>
            <th style="width: 200px;">会社名</th>
            <td>{{ $company->name }}</td>
        </tr>
        <tr>
            <th>担当者名</th>
            <td>{{ $company->contact_name ?? '未登録' }}</td>
        </tr>
        <tr>
            <th>業種（大分類）</th>
            <td>
                @if($company->industry_type === 1) 美容
                @elseif($company->industry_type === 2) 医療
                @elseif($company->industry_type === 3) 歯科
                @else その他
                @endif
            </td>
        </tr>
        <tr>
            <th>業種（詳細）</th>
            <td>{{ \App\Services\BusinessCategoryService::getCategoryName($company->business_category) ?? '未登録' }}</td>
        </tr>
        <tr>
            <th>郵便番号</th>
            <td>{{ $company->postal_code ?? '未登録' }}</td>
        </tr>
        <tr>
            <th>住所</th>
            <td>{{ $company->address ?? '未登録' }}</td>
        </tr>
        <tr>
            <th>電話番号</th>
            <td>{{ $company->tel ?? '未登録' }}</td>
        </tr>
    </table>
</div>
@endsection

