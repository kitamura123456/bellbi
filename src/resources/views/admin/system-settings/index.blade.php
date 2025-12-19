@extends('layouts.admin')

@section('title', 'システム設定')

@section('content')
<div class="admin-header">
    <h1 class="admin-title">システム設定</h1>
</div>

<div class="admin-card">
    <form action="{{ route('admin.system-settings.update') }}" method="POST" class="admin-form">
        @csrf
        @method('PUT')

        <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">基本設定</h3>

        <div class="form-group">
            <label for="site_name">サイト名 <span class="required">必須</span></label>
            <input type="text" id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required>
            <small>サイトの名称を入力してください</small>
            @error('site_name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="site_description">サイト説明</label>
            <textarea id="site_description" name="site_description" rows="3">{{ old('site_description', $settings['site_description']) }}</textarea>
            <small>サイトの説明文を入力してください（SEO用）</small>
            @error('site_description')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <h3 style="margin: 30px 0 20px 0; font-size: 18px; font-weight: 600; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">メール設定</h3>

        <div class="form-group">
            <label for="contact_email">お問い合わせメールアドレス</label>
            <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}">
            <small>ユーザーからのお問い合わせを受け取るメールアドレス</small>
            @error('contact_email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="admin_email">管理者メールアドレス</label>
            <input type="email" id="admin_email" name="admin_email" value="{{ old('admin_email', $settings['admin_email']) }}">
            <small>システム通知を受け取る管理者のメールアドレス</small>
            @error('admin_email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <h3 style="margin: 30px 0 20px 0; font-size: 18px; font-weight: 600; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">メンテナンス設定</h3>

        <div class="form-group">
            <label for="maintenance_mode">
                <input type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }} style="margin-right: 8px;">
                メンテナンスモードを有効にする
            </label>
            <small>有効にすると、一般ユーザーはサイトにアクセスできなくなります（管理者はアクセス可能）</small>
            @error('maintenance_mode')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="maintenance_message">メンテナンスメッセージ</label>
            <textarea id="maintenance_message" name="maintenance_message" rows="3">{{ old('maintenance_message', $settings['maintenance_message']) }}</textarea>
            <small>メンテナンスモード時に表示されるメッセージ</small>
            @error('maintenance_message')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">設定を保存</button>
        </div>
    </form>
</div>
@endsection

