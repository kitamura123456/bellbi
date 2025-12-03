## Bellbi 基本設計書（簡易版）

### 1. ドキュメント情報

- **ドキュメント名**: Bellbi 基本設計書（簡易版）  
- **版数**: 1.0（ドラフト）  
- **作成日**: 2025-12-01  
- **作成者**: （未定）  
- **対象システム**: Bellbi（べるび）

---

### 2. システム構成方針

- **2.1 アーキテクチャ**  
  - Laravel を用いた MVC アーキテクチャを採用する。  
  - Web ルーティング + Blade テンプレートを基本とし、必要に応じて API エンドポイントを追加する。  
  - 認証には Laravel 標準の認証機構（Breeze / Fortify 等）を利用する想定とする。

- **2.2 フロントエンド技術方針**  
  - JavaScript は jQuery を利用する。  
  - CSS は、開発の大部分（実装〜中盤）では通常の CSS（生CSS）で記述する。  
  - Tailwind CSS はプロジェクトに導入しておき、**デザインの最終調整フェーズでのみユーティリティクラスを使用する**。  
    - それまでは Tailwind のクラスは極力使用せず、既存CSSを優先する。

- **2.3 レイヤ構成（論理）**  
  - `Controllers`: HTTP リクエストの受入口。画面・API 単位で配置。  
  - `Services`: 求人、スカウト、会計、補助金、EC などドメインごとの業務ロジックを配置。  
  - `Repositories`（必要に応じて）: Eloquent に対するアクセスを抽象化。  
  - `Models`: Eloquent モデルによるテーブル定義・リレーション。  
  - `Requests`: 入力バリデーションの定義。  
  - `Views`: Blade による画面テンプレート。
  
- **2.4 データベース設計方針**  
  - 全ての主要テーブルに `delete_flg`（0:有効, 1:削除）を持たせ、論理削除を行う。  
  - ステータス、カテゴリ、種別等は **必ず数値** で管理し、アプリケーション側で定数管理する。  
  - 事業者（`companies`）・店舗（`stores`）をキーにする簡易マルチテナント構成とする。

---

### 3. 画面・URL 構成（概要）

#### 3.1 一般ユーザ向け画面

- `/`  
  - トップページ（サービス概要・機能紹介・一部求人のピックアップ表示）

- `/jobs`  
  - 求人一覧画面。検索条件フォーム（業種、職種、エリア、雇用形態、給与レンジ等）を表示。

- `/jobs/{id}`  
  - 求人詳細画面。求人情報および店舗情報を表示し、「応募する」ボタンを設置。

- `/jobs/{id}/apply`  
  - 応募フォーム画面。応募メッセージを入力して送信する。

- `/subsidies`  
  - 補助金一覧画面。業種・地域・ステータスでのフィルタリング。

- `/subsidies/{id}`  
  - 補助金詳細画面。概要・外部URL等を表示。

- `/shops`  
  - モール内ショップ一覧画面。

- `/shops/{id}`  
  - ショップ詳細画面。ショップの説明と商品一覧を表示。

- `/products/{id}`  
  - 商品詳細画面。カート投入や注文ボタンを表示。

#### 3.2 個人マイページ

- `/mypage`  
  - 個人ユーザ用ダッシュボード。応募状況やスカウト受信状況のサマリを表示。

- `/mypage/profile`  
  - 個人プロフィール編集画面。

- `/mypage/scout-profile`  
  - スカウト用プロフィール編集画面（希望条件など）。

- `/mypage/applications`  
  - 応募履歴一覧画面。

- `/mypage/scouts`  
  - 受信スカウト一覧およびスカウト詳細・返信画面。

- `/mypage/subsidies/bookmarks`  
  - ブックマークした補助金一覧画面。

#### 3.3 事業者向け画面

- `/company`  
  - 事業者ダッシュボード。求人数、応募数、売上サマリなど。

- `/company/stores`  
  - 店舗一覧・編集画面。

- `/company/job-posts`  
  - 求人一覧画面（自社分）。

- `/company/job-posts/create`  
  - 求人作成画面。

- `/company/job-posts/{id}/edit`  
  - 求人編集画面。

- `/company/scouts`  
  - 送信スカウト一覧・詳細画面。

- `/company/transactions`  
  - 売上・経費一覧画面。

- `/company/shops`  
  - ECショップ管理画面（ショップ情報編集、商品一覧など）。

#### 3.4 管理者向け画面

- `/admin`  
  - 管理ダッシュボード。

- `/admin/subsidies`  
  - 補助金情報の一覧・登録・編集画面。

- `/admin/plans`  
  - プラン定義の一覧・編集画面。

- `/admin/companies`  
  - 事業者一覧・詳細画面。

---

### 4. データベース設計（代表テーブル）

※全テーブル共通で `delete_flg TINYINT(1) DEFAULT 0`、`created_at`, `updated_at` を持つ前提。

#### 4.1 `users` テーブル

- **用途**: 全ユーザ（個人・事業者・管理者）のアカウント情報を管理する。  
- **主な項目**  
  - `id`: BIGINT, PK  
  - `name`: VARCHAR  
  - `email`: VARCHAR, UNIQUE  
  - `password`: VARCHAR  
  - `role`: TINYINT  
    - 1: 個人ユーザ  
    - 2: 事業者管理者  
    - 3: 店舗担当者  
    - 9: 運営管理者  
  - `profile_completed_flg`: TINYINT（プロフィール入力完了フラグ）  
  - `delete_flg`: TINYINT

#### 4.2 `companies` テーブル

- **用途**: 事業者（法人・個人事業主）情報の管理。  
- **主な項目**  
  - `id`: BIGINT, PK  
  - `user_id`: BIGINT, FK → `users.id`（オーナーユーザ）  
  - `name`: VARCHAR  
  - `industry_type`: TINYINT（1:美容, 2:医療, 3:歯科, 99:その他）  
  - `postal_code`: VARCHAR  
  - `address`: VARCHAR  
  - `tel`: VARCHAR  
  - `plan_id`: BIGINT, FK → `plans.id`  
  - `plan_status`: TINYINT（1:トライアル, 2:有効, 3:遅延, 9:解約）  
  - `delete_flg`: TINYINT

#### 4.3 `stores` テーブル

- **用途**: 各事業者に紐づく店舗・クリニック情報の管理。  
- **主な項目**  
  - `id`: BIGINT, PK  
  - `company_id`: BIGINT, FK → `companies.id`  
  - `name`: VARCHAR  
  - `store_type`: TINYINT（1:美容室, 2:エステ, 3:医科, 4:歯科 等）  
  - `postal_code`: VARCHAR  
  - `address`: VARCHAR  
  - `tel`: VARCHAR  
  - `delete_flg`: TINYINT

#### 4.4 `job_posts` テーブル

- **用途**: 求人情報の管理。  
- **主な項目**  
  - `id`: BIGINT, PK  
  - `company_id`: BIGINT, FK → `companies.id`  
  - `store_id`: BIGINT, FK → `stores.id`（NULL可）  
  - `title`: VARCHAR  
  - `description`: TEXT  
  - `job_category`: SMALLINT（数値カテゴリ）  
  - `employment_type`: TINYINT（1:正社員, 2:パート, 3:業務委託 等）  
  - `min_salary`: INT（NULL可）  
  - `max_salary`: INT（NULL可）  
  - `work_location`: VARCHAR（NULL可）  
  - `status`: TINYINT（0:下書き, 1:公開, 2:停止）  
  - `publish_start_at`: DATETIME（NULL可）  
  - `publish_end_at`: DATETIME（NULL可）  
  - `delete_flg`: TINYINT

#### 4.5 `job_applications` テーブル

- **用途**: 求人への応募情報の管理。  
- **主な項目**  
  - `id`: BIGINT, PK  
  - `job_post_id`: BIGINT, FK → `job_posts.id`  
  - `user_id`: BIGINT, FK → `users.id`（応募者）  
  - `status`: TINYINT  
    - 1: 応募済  
    - 2: 書類選考中  
    - 3: 面接中  
    - 4: 内定  
    - 5: 不採用  
    - 9: 応募者キャンセル  
  - `message`: TEXT（応募メッセージ）  
  - `delete_flg`: TINYINT

#### 4.6 `scout_profiles` テーブル

- **用途**: スカウト用の個人プロフィール情報。  
- **主な項目**  
  - `id`: BIGINT, PK  
  - `user_id`: BIGINT, FK → `users.id`  
  - `industry_type`: TINYINT（1:美容, 2:医療, 3:歯科 等）  
  - `desired_job_category`: SMALLINT  
  - `experience_years`: TINYINT  
  - `desired_work_style`: TINYINT（1:フルタイム, 2:パート 等）  
  - `is_public`: TINYINT（0:非公開, 1:公開）  
  - `delete_flg`: TINYINT

#### 4.7 `scout_messages` テーブル

- **用途**: スカウトメッセージの管理。  
- **主な項目**  
  - `id`: BIGINT, PK  
  - `from_company_id`: BIGINT, FK → `companies.id`  
  - `from_store_id`: BIGINT, FK → `stores.id`（NULL可）  
  - `to_user_id`: BIGINT, FK → `users.id`（個人）  
  - `scout_profile_id`: BIGINT, FK → `scout_profiles.id`  
  - `status`: TINYINT（1:送信, 2:既読, 3:返信あり, 9:クローズ）  
  - `subject`: VARCHAR  
  - `body`: TEXT  
  - `delete_flg`: TINYINT

#### 4.8 `account_items` テーブル

- **用途**: 売上・経費などの科目マスタ。  
- **主な項目**  
  - `id`: BIGINT, PK  
  - `company_id`: BIGINT, FK → `companies.id`  
  - `type`: TINYINT（1:売上, 2:経費）  
  - `name`: VARCHAR  
  - `default_tax_rate`: DECIMAL  
  - `delete_flg`: TINYINT

#### 4.9 `transactions` テーブル

- **用途**: 売上・経費の取引明細。  
- **主な項目**  
  - `id`: BIGINT, PK  
  - `company_id`: BIGINT, FK → `companies.id`  
  - `store_id`: BIGINT, FK → `stores.id`  
  - `date`: DATE  
  - `account_item_id`: BIGINT, FK → `account_items.id`  
  - `amount`: INT  
  - `tax_amount`: INT  
  - `transaction_type`: TINYINT（1:売上, 2:経費）  
  - `source_type`: TINYINT（1:手入力, 2:EC連携, 3:外部API 等）  
  - `note`: TEXT（NULL可）  
  - `delete_flg`: TINYINT

#### 4.10 `subsidies` テーブル

- **用途**: 補助金情報の管理。  
- **主な項目**  
  - `id`: BIGINT, PK  
  - `title`: VARCHAR  
  - `category`: SMALLINT（補助金種別）  
  - `target_region`: SMALLINT（地域コード）  
  - `applicable_industry_type`: SMALLINT（業種種別。必要に応じて中間テーブル化）  
  - `application_start_at`: DATETIME  
  - `application_end_at`: DATETIME  
  - `status`: TINYINT（1:募集中, 2:締切, 3:未開始）  
  - `summary`: TEXT  
  - `detail_url`: VARCHAR  
  - `delete_flg`: TINYINT

#### 4.11 `subsidy_bookmarks` テーブル

- **用途**: 補助金ブックマーク情報。  
- **主な項目**  
  - `id`: BIGINT, PK  
  - `user_id`: BIGINT, FK → `users.id`  
  - `subsidy_id`: BIGINT, FK → `subsidies.id`  
  - `delete_flg`: TINYINT

#### 4.12 EC関連・プラン関連（概要）

- `shops`  
  - 事業者のECショップ情報（`company_id`, `store_id`, `name`, `status`, `delete_flg` 等）
- `products`  
  - 商品情報（`shop_id`, `name`, `price`, `stock`, `status`, `category`, `delete_flg` 等）
- `orders`  
  - 受注情報（`shop_id`, `user_id`, `total_amount`, `status`, `delete_flg` 等）
- `order_items`  
  - 注文明細情報（`order_id`, `product_id`, `quantity`, `unit_price`, `delete_flg` 等）
- `plans`  
  - プラン定義（`name`, `price_monthly`, `features_bitmask` など）
- `subscriptions`  
  - 事業者ごとの契約情報（`company_id`, `plan_id`, `status`, `started_at`, `ended_at`, `delete_flg` 等）

---

### 5. Laravel 実装方針（概要）

- **5.1 モデルとリレーション**  
  - 各テーブルに対応する Eloquent モデルを作成する。  
  - 代表的なリレーション例:  
    - `User` - `Company`: 1対1（事業者オーナー）  
    - `Company` - `Store`: 1対多  
    - `Company` - `JobPost`: 1対多  
    - `JobPost` - `JobApplication`: 1対多  
    - `User` - `JobApplication`: 1対多（応募者視点）  
    - `User` - `ScoutProfile`: 1対1  
    - `Company` - `Transaction`: 1対多  
    - `Shop` - `Product`: 1対多  
    - `Order` - `OrderItem`: 1対多

- **5.2 ステータス・カテゴリ管理**  
  - `app/Enums/` または `config/const.php` などに Enum/定数クラスを定義し、  
    コード中では魔法の数字を避け、定数を通じて参照する。

- **5.3 バリデーション方針**  
  - フォーム入力ごとに `FormRequest` クラスを作成し、必須項目・型・範囲チェックを定義する。  
  - ステータスやカテゴリの値は定数一覧に含まれる値のみ許可する。

---

### 6. 今後の詳細設計

- 各画面のワイヤーフレーム、入力項目一覧、メッセージ仕様などは詳細設計書にて定義する。  
- API エンドポイントが必要な場合は、別途 API 設計書を作成する。  
- 実装フェーズ開始時に、マイグレーションファイル・モデルクラスを本設計に基づき作成する。


