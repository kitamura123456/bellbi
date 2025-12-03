<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 管理者アカウント
        if (!User::where('email', 'kitamura.atsuki@derac.co.jp')->exists()) {
            User::create([
                'name' => '北村敦輝',
                'email' => 'kitamura.atsuki@derac.co.jp',
                'password' => Hash::make('atsuki0594'),
                'role' => User::ROLE_ADMIN,
                'profile_completed_flg' => 1,
                'delete_flg' => 0,
            ]);
        }

        // 店舗テストアカウント
        if (!User::where('email', 'tenpo')->exists()) {
            $tenpoUser = User::create([
                'name' => '店舗テストアカウント',
                'email' => 'tenpo',
                'password' => Hash::make('test1234'),
                'role' => User::ROLE_COMPANY,
                'profile_completed_flg' => 1,
                'delete_flg' => 0,
            ]);

            // 会社情報も作成
            Company::create([
                'user_id' => $tenpoUser->id,
                'name' => 'テスト美容サロン',
                'contact_name' => '山田太郎',
                'industry_type' => 1,
                'business_category' => 11,
                'plan_id' => null,
                'plan_status' => 1,
                'delete_flg' => 0,
            ]);
        }

        // エンドユーザーテストアカウント
        if (!User::where('email', 'user')->exists()) {
            User::create([
                'name' => 'ユーザーテストアカウント',
                'email' => 'user',
                'password' => Hash::make('test1234'),
                'role' => User::ROLE_PERSONAL,
                'profile_completed_flg' => 1,
                'delete_flg' => 0,
            ]);
        }

        // 追加ユーザー
        if (!User::where('email', 'hirooka.sawa@derac.co.jp')->exists()) {
            User::create([
                'name' => '廣岡沙和',
                'email' => 'hirooka.sawa@derac.co.jp',
                'password' => Hash::make('test1234'),
                'role' => User::ROLE_PERSONAL,
                'profile_completed_flg' => 1,
                'delete_flg' => 0,
            ]);
        }
    }
}

