<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Store;
use App\Models\StoreStaff;
use App\Models\ServiceMenu;
use App\Models\StoreSchedule;
use App\Models\JobPost;
use App\Models\JobApplication;
use App\Models\ScoutProfile;
use App\Models\ScoutMessage;
use App\Models\AccountItem;
use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
                'name' => '北村豊貴',
                'email' => 'kitamura.atsuki@derac.co.jp',
                'password' => Hash::make('atsuki0594'),
                'role' => User::ROLE_ADMIN,
                'profile_completed_flg' => 1,
                'delete_flg' => 0,
            ]);
        }

        // 管理者アカウント (admin)
        if (!User::where('email', 'admin')->exists()) {
            User::create([
                'name' => 'admin',
                'email' => 'admin',
                'password' => Hash::make('test1234'),
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
            $company = Company::create([
                'user_id' => $tenpoUser->id,
                'name' => 'テスト美容サロン',
                'contact_name' => '山田太郎',
                'industry_type' => 1,
                'business_category' => 11,
                'plan_id' => null,
                'plan_status' => 1,
                'delete_flg' => 0,
            ]);

            // 店舗情報を作成
            $store = Store::create([
                'company_id' => $company->id,
                'name' => 'テストサロン 表参道店',
                'store_type' => 1,
                'description' => "表参道駅から徒歩3分の好立地！\n\nお客様一人ひとりの髪質や雰囲気に合わせた、丁寧なカウンセリングと施術を心がけています。\n\n落ち着いた雰囲気の店内で、リラックスしながら美しさを手に入れてください。",
                'postal_code' => '150-0001',
                'address' => '東京都渋谷区神宮前5-1-1',
                'tel' => '03-1234-5678',
                'accepts_reservations' => 1,
                'cancel_deadline_hours' => 24,
                'delete_flg' => 0,
            ]);

            // スタッフを作成
            $staff1 = StoreStaff::create([
                'store_id' => $store->id,
                'name' => '田中美咲',
                'display_order' => 1,
                'is_active' => 1,
                'delete_flg' => 0,
            ]);

            $staff2 = StoreStaff::create([
                'store_id' => $store->id,
                'name' => '佐藤優子',
                'display_order' => 2,
                'is_active' => 1,
                'delete_flg' => 0,
            ]);

            // メニューを作成
            ServiceMenu::create([
                'store_id' => $store->id,
                'name' => 'カット',
                'description' => 'シャンプー・ブロー込み',
                'duration_minutes' => 60,
                'price' => 5000,
                'category' => 1,
                'display_order' => 1,
                'is_active' => 1,
                'delete_flg' => 0,
            ]);

            ServiceMenu::create([
                'store_id' => $store->id,
                'name' => 'カラー',
                'description' => 'フルカラー',
                'duration_minutes' => 90,
                'price' => 8000,
                'category' => 2,
                'display_order' => 2,
                'is_active' => 1,
                'delete_flg' => 0,
            ]);

            ServiceMenu::create([
                'store_id' => $store->id,
                'name' => 'トリートメント',
                'description' => '髪質改善トリートメント',
                'duration_minutes' => 30,
                'price' => 3000,
                'category' => 4,
                'display_order' => 3,
                'is_active' => 1,
                'delete_flg' => 0,
            ]);

            // 営業スケジュールを作成（月〜日）
            for ($day = 0; $day <= 6; $day++) {
                StoreSchedule::create([
                    'store_id' => $store->id,
                    'day_of_week' => $day,
                    'is_open' => $day === 2 ? 0 : 1, // 火曜定休
                    'open_time' => '10:00',
                    'close_time' => '19:00',
                    'delete_flg' => 0,
                ]);
            }
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

        // 管理者（廣岡さん）
        if (!User::where('email', 'hirooka.sawa@derac.co.jp')->exists()) {
            User::create([
                'name' => '廣岡沙和',
                'email' => 'hirooka.sawa@derac.co.jp',
                'password' => Hash::make('test1234'),
                'role' => User::ROLE_ADMIN,
                'profile_completed_flg' => 1,
                'delete_flg' => 0,
            ]);
        }

        // ===== 追加の事業者アカウント =====
        
        // 美容サロン2号店
        if (!User::where('email', 'salon2@test.com')->exists()) {
            $salon2User = User::create([
                'name' => 'ビューティーサロン2',
                'email' => 'salon2@test.com',
                'password' => Hash::make('test1234'),
                'role' => User::ROLE_COMPANY,
                'profile_completed_flg' => 1,
                'delete_flg' => 0,
            ]);

            $salon2Company = Company::create([
                'user_id' => $salon2User->id,
                'name' => 'ヘアサロン GLOW',
                'contact_name' => '鈴木花子',
                'industry_type' => 1,
                'business_category' => 11,
                'postal_code' => '107-0062',
                'address' => '東京都港区南青山3-10-5',
                'tel' => '03-9876-5432',
                'plan_id' => null,
                'plan_status' => 1,
                'delete_flg' => 0,
            ]);

            $salon2Store = Store::create([
                'company_id' => $salon2Company->id,
                'name' => 'GLOW 青山店',
                'store_type' => 1,
                'description' => "青山のハイセンスなヘアサロン。\n\n最新のトレンドを取り入れたスタイル提案が得意です。経験豊富なトップスタイリストが、あなたの魅力を最大限に引き出します。\n\nカラーリストも在籍しており、ダメージレスなオーガニックカラーも人気です。",
                'postal_code' => '107-0062',
                'address' => '東京都港区南青山3-10-5',
                'tel' => '03-9876-5432',
                'accepts_reservations' => 1,
                'cancel_deadline_hours' => 48,
                'delete_flg' => 0,
            ]);

            // スタッフ
            StoreStaff::create(['store_id' => $salon2Store->id, 'name' => '高橋麻衣', 'display_order' => 1, 'is_active' => 1, 'delete_flg' => 0]);
            StoreStaff::create(['store_id' => $salon2Store->id, 'name' => '渡辺さくら', 'display_order' => 2, 'is_active' => 1, 'delete_flg' => 0]);
            StoreStaff::create(['store_id' => $salon2Store->id, 'name' => '伊藤美咲', 'display_order' => 3, 'is_active' => 1, 'delete_flg' => 0]);

            // メニュー
            ServiceMenu::create(['store_id' => $salon2Store->id, 'name' => 'カット＋シャンプー', 'description' => 'スタイリストカット', 'duration_minutes' => 60, 'price' => 6000, 'category' => 1, 'display_order' => 1, 'is_active' => 1, 'delete_flg' => 0]);
            ServiceMenu::create(['store_id' => $salon2Store->id, 'name' => 'カラーリング（フル）', 'description' => 'オーガニックカラー使用', 'duration_minutes' => 120, 'price' => 12000, 'category' => 2, 'display_order' => 2, 'is_active' => 1, 'delete_flg' => 0]);
            ServiceMenu::create(['store_id' => $salon2Store->id, 'name' => 'パーマ', 'description' => 'デジタルパーマ', 'duration_minutes' => 150, 'price' => 15000, 'category' => 3, 'display_order' => 3, 'is_active' => 1, 'delete_flg' => 0]);
            ServiceMenu::create(['store_id' => $salon2Store->id, 'name' => 'ヘッドスパ', 'description' => 'リラックスコース', 'duration_minutes' => 30, 'price' => 3500, 'category' => 5, 'display_order' => 4, 'is_active' => 1, 'delete_flg' => 0]);

            // 営業スケジュール（水曜定休）
            for ($day = 0; $day <= 6; $day++) {
                StoreSchedule::create([
                    'store_id' => $salon2Store->id,
                    'day_of_week' => $day,
                    'is_open' => $day === 3 ? 0 : 1,
                    'open_time' => '09:00',
                    'close_time' => '20:00',
                    'delete_flg' => 0,
                ]);
            }
        }

        // エステサロン
        if (!User::where('email', 'esthe@test.com')->exists()) {
            $estheUser = User::create([
                'name' => 'エステサロン代表',
                'email' => 'esthe@test.com',
                'password' => Hash::make('test1234'),
                'role' => User::ROLE_COMPANY,
                'profile_completed_flg' => 1,
                'delete_flg' => 0,
            ]);

            $estheCompany = Company::create([
                'user_id' => $estheUser->id,
                'name' => 'エステティックサロン Lumière',
                'contact_name' => '山本美香',
                'industry_type' => 1,
                'business_category' => 12,
                'postal_code' => '150-0042',
                'address' => '東京都渋谷区宇田川町21-1',
                'tel' => '03-5555-1234',
                'plan_id' => null,
                'plan_status' => 1,
                'delete_flg' => 0,
            ]);

            $estheStore = Store::create([
                'company_id' => $estheCompany->id,
                'name' => 'Lumière 渋谷店',
                'store_type' => 2,
                'description' => "渋谷駅直結の本格エステティックサロン。\n\nフェイシャルからボディまで、豊富なメニューをご用意しています。経験豊富なエステティシャンが、お客様の肌の悩みに寄り添います。\n\n完全個室のプライベート空間で、日頃の疲れを癒してください。",
                'postal_code' => '150-0042',
                'address' => '東京都渋谷区宇田川町21-1',
                'tel' => '03-5555-1234',
                'accepts_reservations' => 1,
                'cancel_deadline_hours' => 24,
                'delete_flg' => 0,
            ]);

            // スタッフ
            StoreStaff::create(['store_id' => $estheStore->id, 'name' => '小林奈々', 'display_order' => 1, 'is_active' => 1, 'delete_flg' => 0]);
            StoreStaff::create(['store_id' => $estheStore->id, 'name' => '加藤愛美', 'display_order' => 2, 'is_active' => 1, 'delete_flg' => 0]);

            // メニュー
            ServiceMenu::create(['store_id' => $estheStore->id, 'name' => 'フェイシャルエステ（60分）', 'description' => '毛穴ケア・保湿', 'duration_minutes' => 60, 'price' => 8000, 'category' => 8, 'display_order' => 1, 'is_active' => 1, 'delete_flg' => 0]);
            ServiceMenu::create(['store_id' => $estheStore->id, 'name' => 'フェイシャルエステ（90分）', 'description' => 'フルコース', 'duration_minutes' => 90, 'price' => 12000, 'category' => 8, 'display_order' => 2, 'is_active' => 1, 'delete_flg' => 0]);
            ServiceMenu::create(['store_id' => $estheStore->id, 'name' => 'ボディマッサージ', 'description' => 'リンパマッサージ', 'duration_minutes' => 90, 'price' => 10000, 'category' => 9, 'display_order' => 3, 'is_active' => 1, 'delete_flg' => 0]);
            ServiceMenu::create(['store_id' => $estheStore->id, 'name' => '痩身エステ', 'description' => 'キャビテーション', 'duration_minutes' => 120, 'price' => 15000, 'category' => 9, 'display_order' => 4, 'is_active' => 1, 'delete_flg' => 0]);

            // 営業スケジュール（月曜定休）
            for ($day = 0; $day <= 6; $day++) {
                StoreSchedule::create([
                    'store_id' => $estheStore->id,
                    'day_of_week' => $day,
                    'is_open' => $day === 1 ? 0 : 1,
                    'open_time' => '11:00',
                    'close_time' => '21:00',
                    'delete_flg' => 0,
                ]);
            }
        }

        // ネイルサロン
        if (!User::where('email', 'nail@test.com')->exists()) {
            $nailUser = User::create([
                'name' => 'ネイルサロン代表',
                'email' => 'nail@test.com',
                'password' => Hash::make('test1234'),
                'role' => User::ROLE_COMPANY,
                'profile_completed_flg' => 1,
                'delete_flg' => 0,
            ]);

            $nailCompany = Company::create([
                'user_id' => $nailUser->id,
                'name' => 'Nail Salon Bijou',
                'contact_name' => '佐々木彩',
                'industry_type' => 1,
                'business_category' => 14,
                'postal_code' => '160-0022',
                'address' => '東京都新宿区新宿3-15-8',
                'tel' => '03-6666-7890',
                'plan_id' => null,
                'plan_status' => 1,
                'delete_flg' => 0,
            ]);

            $nailStore = Store::create([
                'company_id' => $nailCompany->id,
                'name' => 'Bijou 新宿店',
                'store_type' => 1,
                'description' => "新宿駅直結で通いやすいネイルサロン。\n\nシンプルなワンカラーから、トレンドを取り入れたデザインネイルまで幅広く対応。持ち込みデザインも大歓迎です！\n\n丁寧なケアで、爪に優しい施術を心がけています。",
                'postal_code' => '160-0022',
                'address' => '東京都新宿区新宿3-15-8',
                'tel' => '03-6666-7890',
                'accepts_reservations' => 1,
                'cancel_deadline_hours' => 12,
                'delete_flg' => 0,
            ]);

            // スタッフ
            StoreStaff::create(['store_id' => $nailStore->id, 'name' => '中村彩乃', 'display_order' => 1, 'is_active' => 1, 'delete_flg' => 0]);
            StoreStaff::create(['store_id' => $nailStore->id, 'name' => '木村美紀', 'display_order' => 2, 'is_active' => 1, 'delete_flg' => 0]);
            StoreStaff::create(['store_id' => $nailStore->id, 'name' => '山田莉子', 'display_order' => 3, 'is_active' => 1, 'delete_flg' => 0]);

            // メニュー
            ServiceMenu::create(['store_id' => $nailStore->id, 'name' => 'ワンカラー', 'description' => 'シンプルネイル', 'duration_minutes' => 60, 'price' => 5000, 'category' => 7, 'display_order' => 1, 'is_active' => 1, 'delete_flg' => 0]);
            ServiceMenu::create(['store_id' => $nailStore->id, 'name' => 'グラデーション', 'description' => 'ラメグラデーション', 'duration_minutes' => 90, 'price' => 7000, 'category' => 7, 'display_order' => 2, 'is_active' => 1, 'delete_flg' => 0]);
            ServiceMenu::create(['store_id' => $nailStore->id, 'name' => 'アート10本', 'description' => 'デザインネイル', 'duration_minutes' => 120, 'price' => 10000, 'category' => 7, 'display_order' => 3, 'is_active' => 1, 'delete_flg' => 0]);
            ServiceMenu::create(['store_id' => $nailStore->id, 'name' => 'ケア', 'description' => 'ハンドケア', 'duration_minutes' => 30, 'price' => 3000, 'category' => 7, 'display_order' => 4, 'is_active' => 1, 'delete_flg' => 0]);

            // 営業スケジュール（不定休）
            for ($day = 0; $day <= 6; $day++) {
                StoreSchedule::create([
                    'store_id' => $nailStore->id,
                    'day_of_week' => $day,
                    'is_open' => 1,
                    'open_time' => '10:00',
                    'close_time' => '19:00',
                    'delete_flg' => 0,
                ]);
            }
        }

        // ===== 追加の個人ユーザー =====
        
        $personalUsers = [
            ['name' => '田中花子', 'email' => 'tanaka@test.com'],
            ['name' => '佐藤美咲', 'email' => 'sato@test.com'],
            ['name' => '鈴木愛', 'email' => 'suzuki@test.com'],
            ['name' => '高橋由美', 'email' => 'takahashi@test.com'],
            ['name' => '渡辺舞', 'email' => 'watanabe@test.com'],
            ['name' => '伊藤彩', 'email' => 'ito@test.com'],
            ['name' => '山本里奈', 'email' => 'yamamoto@test.com'],
            ['name' => '中村優子', 'email' => 'nakamura@test.com'],
        ];

        foreach ($personalUsers as $userData) {
            if (!User::where('email', $userData['email'])->exists()) {
                User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('test1234'),
                    'role' => User::ROLE_PERSONAL,
                    'profile_completed_flg' => 1,
                    'delete_flg' => 0,
                ]);
            }
        }

        // ===== 求人データ =====
        
        // テスト美容サロンの求人
        $tenpoCompany = Company::where('user_id', User::where('email', 'tenpo')->first()->id)->first();
        $tenpoStore = Store::where('company_id', $tenpoCompany->id)->first();

        JobPost::create([
            'company_id' => $tenpoCompany->id,
            'store_id' => $tenpoStore->id,
            'title' => '【急募】スタイリスト募集！表参道の人気サロン',
            'description' => "表参道駅徒歩3分の好立地！\n\nお客様一人ひとりに合わせた丁寧な接客を大切にしているサロンです。\n\n【仕事内容】\n・カット、カラー、パーマなどの施術\n・カウンセリング\n・店舗運営補助\n\n【こんな方歓迎】\n・美容師免許をお持ちの方\n・接客が好きな方\n・チームワークを大切にできる方",
            'job_category' => 1,
            'employment_type' => 1,
            'min_salary' => 250000,
            'max_salary' => 400000,
            'prefecture_code' => 13,
            'city' => '渋谷区',
            'status' => 1,
            'publish_start_at' => now()->subDays(10),
            'publish_end_at' => now()->addMonths(2),
            'delete_flg' => 0,
        ]);

        JobPost::create([
            'company_id' => $tenpoCompany->id,
            'store_id' => $tenpoStore->id,
            'title' => 'アシスタント募集中！未経験者歓迎',
            'description' => "美容師を目指す方を全力でサポートします！\n\n丁寧な研修制度で、基礎からしっかり学べます。\n\n【待遇】\n・交通費全額支給\n・社会保険完備\n・研修制度あり\n・スタイリストデビュー支援",
            'job_category' => 2,
            'employment_type' => 1,
            'min_salary' => 200000,
            'max_salary' => 250000,
            'prefecture_code' => 13,
            'city' => '渋谷区',
            'status' => 1,
            'publish_start_at' => now()->subDays(5),
            'publish_end_at' => now()->addMonths(3),
            'delete_flg' => 0,
        ]);

        // GLOW青山店の求人
        $glowCompany = Company::whereHas('user', function($q) {
            $q->where('email', 'salon2@test.com');
        })->first();
        $glowStore = Store::where('company_id', $glowCompany->id)->first();

        JobPost::create([
            'company_id' => $glowCompany->id,
            'store_id' => $glowStore->id,
            'title' => '【高収入】トップスタイリスト募集',
            'description' => "青山の人気サロンで一緒に働きませんか？\n\n実力に応じた高収入が可能です。\n\n【給与】\n基本給25万円〜50万円 + 歩合給\n\n【福利厚生】\n・社会保険完備\n・有給休暇\n・産休・育休制度\n・技術講習費補助",
            'job_category' => 1,
            'employment_type' => 1,
            'min_salary' => 300000,
            'max_salary' => 600000,
            'prefecture_code' => 13,
            'city' => '港区',
            'status' => 1,
            'publish_start_at' => now()->subDays(15),
            'publish_end_at' => now()->addMonths(1),
            'delete_flg' => 0,
        ]);

        // Lumière渋谷店の求人
        $lumiereCompany = Company::whereHas('user', function($q) {
            $q->where('email', 'esthe@test.com');
        })->first();
        $lumiereStore = Store::where('company_id', $lumiereCompany->id)->first();

        JobPost::create([
            'company_id' => $lumiereCompany->id,
            'store_id' => $lumiereStore->id,
            'title' => 'エステティシャン募集｜週3日〜OK',
            'description' => "渋谷駅から徒歩5分の好立地サロン！\n\n【勤務時間】\n週3日〜、1日5時間〜相談可能\n\n【こんな方におすすめ】\n・美容に興味がある方\n・人を癒すことが好きな方\n・資格取得を目指している方\n\n【研修制度】\n未経験でも安心の研修制度完備",
            'job_category' => 3,
            'employment_type' => 2,
            'min_salary' => 180000,
            'max_salary' => 300000,
            'prefecture_code' => 13,
            'city' => '渋谷区',
            'status' => 1,
            'publish_start_at' => now()->subDays(7),
            'publish_end_at' => now()->addMonths(2),
            'delete_flg' => 0,
        ]);

        // Bijou新宿店の求人
        $bijouCompany = Company::whereHas('user', function($q) {
            $q->where('email', 'nail@test.com');
        })->first();
        $bijouStore = Store::where('company_id', $bijouCompany->id)->first();

        JobPost::create([
            'company_id' => $bijouCompany->id,
            'store_id' => $bijouStore->id,
            'title' => 'ネイリスト募集｜経験者優遇',
            'description' => "新宿駅直結の大型商業施設内サロン！\n\n【仕事内容】\n・ジェルネイル\n・スカルプチュア\n・ネイルケア\n・接客・カウンセリング\n\n【求める人物像】\n・ネイリスト検定2級以上\n・接客経験のある方\n・向上心のある方",
            'job_category' => 7,
            'employment_type' => 1,
            'min_salary' => 230000,
            'max_salary' => 350000,
            'prefecture_code' => 13,
            'city' => '新宿区',
            'status' => 1,
            'publish_start_at' => now()->subDays(3),
            'publish_end_at' => now()->addMonths(1),
            'delete_flg' => 0,
        ]);

        JobPost::create([
            'company_id' => $bijouCompany->id,
            'store_id' => $bijouStore->id,
            'title' => '【パート】ネイリスト募集｜扶養内OK',
            'description' => "家事や育児と両立しながら働けます！\n\n【勤務時間】\n週2日〜、1日4時間〜相談OK\n\n【時給】\n1,200円〜1,800円（経験による）\n\n【こんな方歓迎】\n・ブランクがある方もOK\n・扶養内で働きたい方\n・シフトの融通が利くところで働きたい方",
            'job_category' => 7,
            'employment_type' => 2,
            'min_salary' => 100000,
            'max_salary' => 150000,
            'prefecture_code' => 13,
            'city' => '新宿区',
            'status' => 1,
            'publish_start_at' => now()->subDays(1),
            'publish_end_at' => now()->addMonth(),
            'delete_flg' => 0,
        ]);

        // ===== 応募データ =====
        
        $personalUsers = User::where('role', User::ROLE_PERSONAL)->get();
        $jobPosts = JobPost::where('status', 1)->get();

        // 田中花子さんの応募
        $tanakaUser = User::where('email', 'tanaka@test.com')->first();
        if ($tanakaUser && $jobPosts->count() > 0) {
            JobApplication::create([
                'job_post_id' => $jobPosts[0]->id,
                'user_id' => $tanakaUser->id,
                'status' => 2,
                'message' => '美容師歴5年の経験があります。カットとカラーが得意です。ぜひ面接の機会をいただけますと幸いです。',
                'delete_flg' => 0,
            ]);
        }

        // 佐藤美咲さんの応募
        $satoUser = User::where('email', 'sato@test.com')->first();
        if ($satoUser && $jobPosts->count() > 1) {
            JobApplication::create([
                'job_post_id' => $jobPosts[1]->id,
                'user_id' => $satoUser->id,
                'status' => 1,
                'message' => '美容の仕事に興味があります。未経験ですが、一生懸命頑張りたいと思っています。',
                'delete_flg' => 0,
            ]);
        }

        // 鈴木愛さんの応募
        $suzukiUser = User::where('email', 'suzuki@test.com')->first();
        if ($suzukiUser && $jobPosts->count() > 3) {
            JobApplication::create([
                'job_post_id' => $jobPosts[3]->id,
                'user_id' => $suzukiUser->id,
                'status' => 3,
                'message' => 'エステティシャンとして3年間勤務していました。フェイシャルとボディマッサージが得意です。',
                'delete_flg' => 0,
            ]);
        }

        // ===== スカウトプロフィール =====
        
        // 高橋由美さんのプロフィール
        $takahashiUser = User::where('email', 'takahashi@test.com')->first();
        if ($takahashiUser) {
            ScoutProfile::create([
                'user_id' => $takahashiUser->id,
                'industry_type' => 1,
                'desired_job_category' => 1,
                'experience_years' => 8,
                'desired_work_style' => 1,
                'is_public' => 1,
                'delete_flg' => 0,
            ]);
        }

        // 渡辺舞さんのプロフィール
        $watanabeUser = User::where('email', 'watanabe@test.com')->first();
        if ($watanabeUser) {
            ScoutProfile::create([
                'user_id' => $watanabeUser->id,
                'industry_type' => 1,
                'desired_job_category' => 3,
                'experience_years' => 5,
                'desired_work_style' => 2,
                'is_public' => 1,
                'delete_flg' => 0,
            ]);
        }

        // 伊藤彩さんのプロフィール
        $itoUser = User::where('email', 'ito@test.com')->first();
        if ($itoUser) {
            ScoutProfile::create([
                'user_id' => $itoUser->id,
                'industry_type' => 1,
                'desired_job_category' => 7,
                'experience_years' => 3,
                'desired_work_style' => 1,
                'is_public' => 1,
                'delete_flg' => 0,
            ]);
        }

        // ===== スカウトメッセージ =====
        
        // GLOWから高橋由美さんへ
        if ($glowCompany && $takahashiUser) {
            $takahashiProfile = ScoutProfile::where('user_id', $takahashiUser->id)->first();
            if ($takahashiProfile) {
                ScoutMessage::create([
                    'from_company_id' => $glowCompany->id,
                    'from_store_id' => $glowStore->id,
                    'to_user_id' => $takahashiUser->id,
                    'scout_profile_id' => $takahashiProfile->id,
                    'status' => 2,
                    'subject' => 'トップスタイリストとして活躍しませんか？',
                    'body' => "高橋様\n\nヘアサロンGLOWの採用担当の鈴木です。\n\nプロフィールを拝見し、8年の豊富な経験をお持ちであることに魅力を感じました。\n\n当店では、スタイリストの技術力向上を全力でサポートしており、高収入も実現可能です。\n\nぜひ一度、店舗見学にいらっしゃいませんか？\n\nご連絡をお待ちしております。",
                    'delete_flg' => 0,
                ]);
            }
        }

        // Lumiereから渡辺舞さんへ
        if ($lumiereCompany && $watanabeUser) {
            $watanabeProfile = ScoutProfile::where('user_id', $watanabeUser->id)->first();
            if ($watanabeProfile) {
                ScoutMessage::create([
                    'from_company_id' => $lumiereCompany->id,
                    'from_store_id' => $lumiereStore->id,
                    'to_user_id' => $watanabeUser->id,
                    'scout_profile_id' => $watanabeProfile->id,
                    'status' => 3,
                    'subject' => 'エステティシャン募集のご案内',
                    'body' => "渡辺様\n\nエステティックサロン Lumièreです。\n\n5年の経験をお持ちで、パート勤務をご希望とのこと、当店の勤務条件とマッチするのではと思いご連絡差し上げました。\n\n週3日〜、時短勤務も相談可能です。\n\nお気軽にご連絡ください。\n\n--- 返信 ---\nご連絡ありがとうございます。ぜひ詳しいお話を伺いたいです。店舗見学は可能でしょうか？",
                    'delete_flg' => 0,
                ]);
            }
        }

        // ===== テスト美容サロン（tenpo）の会計データ =====
        
        $tenpoUser = User::where('email', 'tenpo')->first();
        if ($tenpoUser) {
            $tenpoCompany = Company::where('user_id', $tenpoUser->id)->first();
            $tenpoStore = Store::where('company_id', $tenpoCompany->id)->first();

            if ($tenpoCompany && $tenpoStore) {
                // 科目マスタ作成
                $revenueItems = [];
                $expenseItems = [];

                // 売上科目
                if (!AccountItem::where('company_id', $tenpoCompany->id)->where('type', 1)->exists()) {
                    $revenueItems[] = AccountItem::create([
                        'company_id' => $tenpoCompany->id,
                        'type' => 1,
                        'name' => 'カット',
                        'default_tax_rate' => 10.0,
                        'delete_flg' => 0,
                    ]);

                    $revenueItems[] = AccountItem::create([
                        'company_id' => $tenpoCompany->id,
                        'type' => 1,
                        'name' => 'カラー',
                        'default_tax_rate' => 10.0,
                        'delete_flg' => 0,
                    ]);

                    $revenueItems[] = AccountItem::create([
                        'company_id' => $tenpoCompany->id,
                        'type' => 1,
                        'name' => 'パーマ',
                        'default_tax_rate' => 10.0,
                        'delete_flg' => 0,
                    ]);

                    $revenueItems[] = AccountItem::create([
                        'company_id' => $tenpoCompany->id,
                        'type' => 1,
                        'name' => 'トリートメント',
                        'default_tax_rate' => 10.0,
                        'delete_flg' => 0,
                    ]);

                    $revenueItems[] = AccountItem::create([
                        'company_id' => $tenpoCompany->id,
                        'type' => 1,
                        'name' => 'ヘッドスパ',
                        'default_tax_rate' => 10.0,
                        'delete_flg' => 0,
                    ]);
                } else {
                    $revenueItems = AccountItem::where('company_id', $tenpoCompany->id)
                        ->where('type', 1)
                        ->where('delete_flg', 0)
                        ->get()
                        ->toArray();
                }

                // 経費科目
                if (!AccountItem::where('company_id', $tenpoCompany->id)->where('type', 2)->exists()) {
                    $expenseItems[] = AccountItem::create([
                        'company_id' => $tenpoCompany->id,
                        'type' => 2,
                        'name' => '材料費（カラー剤）',
                        'default_tax_rate' => 10.0,
                        'delete_flg' => 0,
                    ]);

                    $expenseItems[] = AccountItem::create([
                        'company_id' => $tenpoCompany->id,
                        'type' => 2,
                        'name' => '材料費（パーマ液）',
                        'default_tax_rate' => 10.0,
                        'delete_flg' => 0,
                    ]);

                    $expenseItems[] = AccountItem::create([
                        'company_id' => $tenpoCompany->id,
                        'type' => 2,
                        'name' => '広告宣伝費',
                        'default_tax_rate' => 10.0,
                        'delete_flg' => 0,
                    ]);

                    $expenseItems[] = AccountItem::create([
                        'company_id' => $tenpoCompany->id,
                        'type' => 2,
                        'name' => '水道光熱費',
                        'default_tax_rate' => 10.0,
                        'delete_flg' => 0,
                    ]);

                    $expenseItems[] = AccountItem::create([
                        'company_id' => $tenpoCompany->id,
                        'type' => 2,
                        'name' => '消耗品費',
                        'default_tax_rate' => 10.0,
                        'delete_flg' => 0,
                    ]);
                } else {
                    $expenseItems = AccountItem::where('company_id', $tenpoCompany->id)
                        ->where('type', 2)
                        ->where('delete_flg', 0)
                        ->get()
                        ->toArray();
                }

                // 取引データ作成（過去3ヶ月分）
                if (!Transaction::where('company_id', $tenpoCompany->id)->exists()) {
                    $startDate = Carbon::now()->subMonths(3)->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();

                    $currentDate = $startDate->copy();
                    while ($currentDate <= $endDate) {
                        // 定休日（火曜日）以外は営業
                        if ($currentDate->dayOfWeek !== 2) {
                            // 1日あたり3〜8件の売上
                            $salesCount = rand(3, 8);
                            for ($i = 0; $i < $salesCount; $i++) {
                                $revenueItem = $revenueItems[array_rand($revenueItems)];
                                
                                // 科目に応じた金額設定
                                $amounts = [
                                    'カット' => [4000, 6000],
                                    'カラー' => [6000, 12000],
                                    'パーマ' => [8000, 15000],
                                    'トリートメント' => [2000, 5000],
                                    'ヘッドスパ' => [3000, 5000],
                                ];

                                $itemName = is_array($revenueItem) ? $revenueItem['name'] : $revenueItem->name;
                                $itemId = is_array($revenueItem) ? $revenueItem['id'] : $revenueItem->id;
                                
                                if (isset($amounts[$itemName])) {
                                    $amount = rand($amounts[$itemName][0], $amounts[$itemName][1]);
                                } else {
                                    $amount = rand(3000, 10000);
                                }
                                
                                $taxAmount = floor($amount * 0.1);

                                Transaction::create([
                                    'company_id' => $tenpoCompany->id,
                                    'store_id' => $tenpoStore->id,
                                    'date' => $currentDate->format('Y-m-d'),
                                    'account_item_id' => $itemId,
                                    'amount' => $amount,
                                    'tax_amount' => $taxAmount,
                                    'transaction_type' => 1,
                                    'source_type' => 1,
                                    'note' => null,
                                    'delete_flg' => 0,
                                ]);
                            }

                            // 経費（週に2〜3回程度）
                            if (rand(1, 3) <= 2) {
                                $expenseItem = $expenseItems[array_rand($expenseItems)];
                                
                                $expenseAmounts = [
                                    '材料費（カラー剤）' => [5000, 15000],
                                    '材料費（パーマ液）' => [3000, 10000],
                                    '広告宣伝費' => [10000, 50000],
                                    '水道光熱費' => [5000, 20000],
                                    '消耗品費' => [2000, 8000],
                                ];

                                $itemName = is_array($expenseItem) ? $expenseItem['name'] : $expenseItem->name;
                                $itemId = is_array($expenseItem) ? $expenseItem['id'] : $expenseItem->id;
                                
                                if (isset($expenseAmounts[$itemName])) {
                                    $amount = rand($expenseAmounts[$itemName][0], $expenseAmounts[$itemName][1]);
                                } else {
                                    $amount = rand(3000, 15000);
                                }
                                
                                $taxAmount = floor($amount * 0.1);

                                Transaction::create([
                                    'company_id' => $tenpoCompany->id,
                                    'store_id' => $tenpoStore->id,
                                    'date' => $currentDate->format('Y-m-d'),
                                    'account_item_id' => $itemId,
                                    'amount' => $amount,
                                    'tax_amount' => $taxAmount,
                                    'transaction_type' => 2,
                                    'source_type' => 1,
                                    'note' => null,
                                    'delete_flg' => 0,
                                ]);
                            }
                        }

                        $currentDate->addDay();
                    }
                }
            }
        }
    }
}

