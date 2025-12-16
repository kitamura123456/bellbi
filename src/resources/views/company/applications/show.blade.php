@extends('layouts.company')

@section('title', '応募詳細')

@section('content')
<div class="company-header">
    <h1 class="company-title">応募詳細</h1>
    <div>
        <a href="{{ route('company.messages.create-from-application', $application) }}" class="btn-primary" style="margin-right: 8px;">メッセージを送る</a>
        <a href="{{ route('company.applications.index') }}" class="btn-secondary">一覧に戻る</a>
    </div>
</div>

<div class="company-card">
    <h3 style="margin-top: 0;">応募情報</h3>
    <table class="company-table">
        <tr>
            <th style="width: 150px;">応募日時</th>
            <td>{{ $application->created_at->format('Y-m-d H:i') }}</td>
        </tr>
        <tr>
            <th>求人タイトル</th>
            <td>
                <a href="{{ route('jobs.show', $application->jobPost) }}" target="_blank">
                    {{ $application->jobPost->title }}
                </a>
            </td>
        </tr>
        <tr>
            <th>応募者名</th>
            <td>{{ $application->user->name }}</td>
        </tr>
        <tr>
            <th>メールアドレス</th>
            <td>{{ $application->user->email }}</td>
        </tr>
        <tr>
            <th>応募メッセージ</th>
            <td style="white-space: pre-wrap;">{{ $application->message }}</td>
        </tr>
    </table>
</div>

<div class="company-card">
    <h3 style="margin-top: 0;">ステータス変更</h3>
    <form action="{{ route('company.applications.update-status', $application) }}" method="POST" class="company-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="status">ステータス</label>
            <select id="status" name="status" required>
                <option value="1" {{ $application->status == 1 ? 'selected' : '' }}>応募済</option>
                <option value="2" {{ $application->status == 2 ? 'selected' : '' }}>書類選考中</option>
                <option value="3" {{ $application->status == 3 ? 'selected' : '' }}>面接中</option>
                <option value="4" {{ $application->status == 4 ? 'selected' : '' }}>内定</option>
                <option value="5" {{ $application->status == 5 ? 'selected' : '' }}>不採用</option>
                <option value="9" {{ $application->status == 9 ? 'selected' : '' }}>キャンセル</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">ステータスを更新</button>
        </div>
    </form>
</div>
@endsection

