<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index()
    {
        // デフォルト設定を取得または初期化
        $settings = [
            'site_name' => SystemSetting::get('site_name', 'Bellbi'),
            'site_description' => SystemSetting::get('site_description', '美容・医療・歯科の求人・予約プラットフォーム'),
            'contact_email' => SystemSetting::get('contact_email', ''),
            'admin_email' => SystemSetting::get('admin_email', ''),
            'maintenance_mode' => SystemSetting::get('maintenance_mode', false),
            'maintenance_message' => SystemSetting::get('maintenance_message', 'メンテナンス中です。しばらくお待ちください。'),
        ];

        return view('admin.system-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:500'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'admin_email' => ['nullable', 'email', 'max:255'],
            'maintenance_mode' => ['nullable'],
            'maintenance_message' => ['nullable', 'string', 'max:500'],
        ]);

        // 各設定を保存
        SystemSetting::set('site_name', $validated['site_name'], 'string', 'サイト名');
        SystemSetting::set('site_description', $validated['site_description'] ?? '', 'string', 'サイト説明');
        SystemSetting::set('contact_email', $validated['contact_email'] ?? '', 'string', 'お問い合わせメールアドレス');
        SystemSetting::set('admin_email', $validated['admin_email'] ?? '', 'string', '管理者メールアドレス');
        // チェックボックスは送信されない場合はfalseになる
        SystemSetting::set('maintenance_mode', isset($validated['maintenance_mode']) && $validated['maintenance_mode'] == '1', 'boolean', 'メンテナンスモード');
        SystemSetting::set('maintenance_message', $validated['maintenance_message'] ?? '', 'string', 'メンテナンスメッセージ');

        return redirect()->route('admin.system-settings.index')->with('status', 'システム設定を更新しました。');
    }
}

