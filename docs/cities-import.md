# 市区町村データインポート機能

## 概要

この機能は、デジタル庁のアドレス・ベース・レジストリから市区町村マスターデータを取得し、データベースにインポートするコマンドです。都道府県コード1から47までを巡回して、各都道府県の市区町村データを自動的に取得・更新します。

## データソース

- **提供元**: デジタル庁 アドレス・ベース・レジストリ
- **URL形式**: `https://data.address-br.digital.go.jp/mt_city/pref/mt_city_pref{都道府県コード}.csv.zip`
- **データ形式**: ZIP圧縮されたCSVファイル
- **更新頻度**: 月次（手動実行または自動スケジュール実行）

## コマンドの使い方

### 基本的な使い方

```bash
# 全ての都道府県（1-47）のデータをインポート
php artisan cities:import
```

### オプション

#### `--pref={都道府県コード}`

特定の都道府県のみをインポートします。テストやデバッグ時に便利です。

```bash
# 大分県（44）のみインポート
php artisan cities:import --pref=44

# 東京都（13）のみインポート
php artisan cities:import --pref=13
```

#### `--debug`

デバッグ情報を表示します。ダウンロードURL、CSVの内容、エラー詳細などが表示されます。

```bash
# デバッグモードで実行
php artisan cities:import --debug

# 特定の都道府県をデバッグモードで実行
php artisan cities:import --pref=44 --debug
```

### 実行例

```bash
# 全ての都道府県をインポート（通常モード）
php artisan cities:import

# 大分県のみをデバッグモードでインポート
php artisan cities:import --pref=44 --debug

# 東京都のみをインポート
php artisan cities:import --pref=13
```

## データベース構造

インポートされたデータは `cities` テーブルに保存されます。

### テーブル構造

| カラム名 | 型 | 説明 |
|---------|-----|------|
| `id` | bigint | 主キー |
| `prefecture_code` | tinyint | 都道府県コード（1-47） |
| `city_code` | varchar(10) | 市区町村コード |
| `name` | varchar(100) | 市区町村名 |
| `name_kana` | varchar(200) | 市区町村名カナ |
| `created_at` | timestamp | 作成日時 |
| `updated_at` | timestamp | 更新日時 |

### データの更新方法

- 既存のデータは都道府県ごとに削除され、新しいデータで置き換えられます
- 同じ都道府県コードと市区町村コードの組み合わせで `updateOrCreate` が実行されます

## 自動更新の設定

### 概要

このコマンドは、Laravelのスケジューラーを使用して毎月1日の午前2時（日本時間）に自動実行されるように設定されています。

設定ファイル: `src/bootstrap/app.php`

```php
->withSchedule(function (Schedule $schedule): void {
    // 市区町村データを毎月1日に自動更新
    $schedule->command('cities:import')
        ->monthlyOn(1, '02:00')
        ->timezone('Asia/Tokyo')
        ->withoutOverlapping()
        ->runInBackground();
})
```

### Windows環境での設定（推奨）

Windows環境では、**Windowsタスクスケジューラ**を使用して定期実行を設定します。

#### 手順1: バッチファイルの作成

まず、スケジューラーを実行するバッチファイルを作成します。

`src/schedule-run.bat` を作成：

```batch
@echo off
cd /d "%~dp0"
php artisan schedule:run >> storage\logs\schedule.log 2>&1
```

#### 手順2: タスクスケジューラの設定

1. **タスクスケジューラを起動**
   - Windowsキーを押して「タスクスケジューラ」と検索
   - 「タスクスケジューラ」を開く

2. **基本タスクの作成**
   - 右側の「基本タスクの作成」をクリック

3. **名前と説明の入力**
   - 名前: `Laravel Schedule Runner`
   - 説明: `Laravelのスケジューラーを1分ごとに実行`
   - 「次へ」をクリック

4. **トリガーの設定**
   - 「コンピューターの起動時」または「ログオン時」を選択
   - 「次へ」をクリック

5. **操作の設定**
   - 「プログラムの開始」を選択
   - 「次へ」をクリック

6. **プログラム/スクリプトの指定**
   - プログラム/スクリプト: `C:\Users\sawa\Documents\bellbi\src\schedule-run.bat`
   - 開始場所: `C:\Users\sawa\Documents\bellbi\src`
   - 「次へ」をクリック

7. **完了**
   - 設定を確認して「完了」をクリック

8. **トリガーの追加（1分ごとに実行）**
   - 作成したタスクを右クリック → 「プロパティ」
   - 「トリガー」タブ → 「新規」をクリック
   - タスクの開始: 「スケジュールに従う」
   - 設定: 「繰り返し間隔」を選択し、「1分間」に設定
   - 「有効」にチェック → 「OK」

9. **実行条件の設定（オプション）**
   - 「条件」タブで以下を設定（推奨）:
     - ☑ コンピューターが AC 電源で動作している場合のみタスクを開始する（ノートPCの場合）
     - ☑ タスクを実行するためにコンピューターを起動する

10. **設定の確認**
    - 「全般」タブで以下を確認:
      - ☑ ユーザーがログオンしているかどうかにかかわらず実行する
      - ☑ 最上位の特権で実行する（必要に応じて）

#### PowerShellスクリプトを使用する場合

バッチファイルの代わりに、PowerShellスクリプトを使用することもできます。

`src/schedule-run.ps1` を作成：

```powershell
Set-Location $PSScriptRoot
php artisan schedule:run >> storage\logs\schedule.log 2>&1
```

タスクスケジューラで実行する場合：
- プログラム/スクリプト: `powershell.exe`
- 引数の追加: `-ExecutionPolicy Bypass -File "C:\Users\sawa\Documents\bellbi\src\schedule-run.ps1"`

### Linux/Unix環境での設定

Linux/Unix環境では、cronを使用してLaravelのスケジューラーを実行します。

#### Cronの設定

```bash
# crontabを編集
crontab -e

# 以下を追加（1分ごとに実行）
* * * * * cd /path-to-your-project/src && php artisan schedule:run >> /dev/null 2>&1
```

**パスの確認**:

```bash
# PHPのパスを確認
which php

# フルパスで指定する場合
* * * * * /usr/bin/php /path-to-your-project/src/artisan schedule:run >> /dev/null 2>&1
```

### スケジュールの確認

設定したスケジュールが正しく動作しているか確認する方法：

#### スケジュール一覧の表示

```bash
php artisan schedule:list
```

実行すると、登録されているスケジュールタスクの一覧が表示されます：

```
+------------------+------------------+------------------+
| Command          | Interval         | Description      |
+------------------+------------------+------------------+
| cities:import    | 0 2 1 * *        | 毎月1日 02:00   |
+------------------+------------------+------------------+
```

#### 手動でスケジューラーを実行（テスト用）

```bash
# スケジューラーを1回だけ実行（テスト用）
php artisan schedule:run

# 詳細な出力を表示
php artisan schedule:run -v
```

#### ログの確認

スケジューラーの実行ログは以下の場所に保存されます：

- Windows: `src/storage/logs/schedule.log`
- Linux: 指定したログファイルまたは `storage/logs/laravel.log`

### 実行時間の変更

実行時間を変更する場合は、`src/bootstrap/app.php` を編集します：

```php
// 毎月1日の午前2時（現在の設定）
->monthlyOn(1, '02:00')

// 毎月15日の午前3時
->monthlyOn(15, '03:00')

// 毎週月曜日の午前1時
->weeklyOn(1, '01:00')  // 1 = 月曜日

// 毎日午前2時
->dailyAt('02:00')

// 毎時間
->hourly()

// カスタムスケジュール（毎月1日と15日の午前2時）
->cron('0 2 1,15 * *')
```

### トラブルシューティング

#### スケジューラーが実行されない場合

1. **タスクスケジューラ（Windows）の確認**
   - タスクスケジューラでタスクが「有効」になっているか確認
   - 「履歴」タブで実行履歴を確認
   - エラーが表示されている場合は、エラーメッセージを確認

2. **ログの確認**
   ```bash
   # Windows
   type src\storage\logs\schedule.log
   
   # Linux
   tail -f src/storage/logs/schedule.log
   ```

3. **手動実行のテスト**
   ```bash
   cd src
   php artisan schedule:run
   ```
   エラーが表示される場合は、エラーメッセージを確認してください。

4. **PHPのパスの確認**
   - タスクスケジューラで使用しているPHPのパスが正しいか確認
   - コマンドプロンプトで `php -v` を実行してパスを確認

5. **権限の確認**
   - タスクスケジューラで「最上位の特権で実行する」にチェック
   - ファイルへの書き込み権限があるか確認

#### スケジューラーが重複実行される場合

`withoutOverlapping()` が設定されているため、前回の実行が完了していない場合は新しい実行はスキップされます。実行時間が長い場合は、タイムアウト時間を調整してください：

```php
->withoutOverlapping(60)  // 60分でタイムアウト
```

## データの確認

### データベースから市区町村を取得

```php
use App\Models\City;

// 都道府県コードで取得
$cities = City::getByTodofukenCode(44); // 大分県

// 市区町村名で検索
$cities = City::searchByName('大分');

// Eloquentで直接取得
$cities = City::where('prefecture_code', 44)
    ->orderBy('name')
    ->get();
```

### Tinkerで確認

```bash
# 大分県の市区町村一覧を表示
php artisan tinker
>>> App\Models\City::where('prefecture_code', 44)->get(['name', 'city_code', 'name_kana']);

# 特定の市区町村を検索
>>> App\Models\City::searchByName('大分');
```

## トラブルシューティング

### データ取得に失敗する場合

1. **ネットワーク接続を確認**
   - デジタル庁のサーバーにアクセスできるか確認してください
   - ファイアウォールやプロキシの設定を確認してください

2. **デバッグモードで実行**
   ```bash
   php artisan cities:import --pref=44 --debug
   ```
   エラーメッセージやダウンロードURLを確認してください

3. **ログを確認**
   - `storage/logs/laravel.log` にエラーログが記録されます

### ZIPファイルの解凍に失敗する場合

- PHPの `ZipArchive` 拡張機能が有効になっているか確認してください
- `php -m | grep zip` で確認できます

### CSVのパースエラー

- デジタル庁のCSV形式が変更された可能性があります
- `--debug` オプションでCSVの内容を確認してください
- 必要に応じて `ImportCities.php` の `importCsvData` メソッドを修正してください

### メモリ不足エラー

大量のデータをインポートする際にメモリ不足が発生する場合：

1. PHPのメモリ制限を増やす
   ```php
   ini_set('memory_limit', '512M');
   ```

2. 都道府県ごとに個別に実行
   ```bash
   for i in {1..47}; do
     php artisan cities:import --pref=$i
   done
   ```

## 都道府県コード一覧

| コード | 都道府県名 |
|-------|----------|
| 1 | 北海道 |
| 2 | 青森 |
| 3 | 岩手 |
| 4 | 宮城 |
| 5 | 秋田 |
| 6 | 山形 |
| 7 | 福島 |
| 8 | 茨城 |
| 9 | 栃木 |
| 10 | 群馬 |
| 11 | 埼玉 |
| 12 | 千葉 |
| 13 | 東京 |
| 14 | 神奈川 |
| 15 | 新潟 |
| 16 | 富山 |
| 17 | 石川 |
| 18 | 福井 |
| 19 | 山梨 |
| 20 | 長野 |
| 21 | 岐阜 |
| 22 | 静岡 |
| 23 | 愛知 |
| 24 | 三重 |
| 25 | 滋賀 |
| 26 | 京都 |
| 27 | 大阪 |
| 28 | 兵庫 |
| 29 | 奈良 |
| 30 | 和歌山 |
| 31 | 鳥取 |
| 32 | 島根 |
| 33 | 岡山 |
| 34 | 広島 |
| 35 | 山口 |
| 36 | 徳島 |
| 37 | 香川 |
| 38 | 愛媛 |
| 39 | 高知 |
| 40 | 福岡 |
| 41 | 佐賀 |
| 42 | 長崎 |
| 43 | 熊本 |
| 44 | 大分 |
| 45 | 宮崎 |
| 46 | 鹿児島 |
| 47 | 沖縄 |

## 注意事項

1. **データの更新頻度**: デジタル庁のデータは定期的に更新される可能性があります。月次での自動更新を推奨します。

2. **既存データの削除**: インポート時、該当都道府県の既存データは削除されます。他のテーブルから参照されている場合は注意してください。

3. **実行時間**: 全ての都道府県（47件）をインポートする場合、ネットワーク状況によっては数分かかる場合があります。

4. **エラーハンドリング**: 一部の都道府県でエラーが発生しても、他の都道府県の処理は継続されます。最後に成功・失敗の件数が表示されます。

## 関連ファイル

- コマンド: `src/app/Console/Commands/ImportCities.php`
- モデル: `src/app/Models/City.php`
- マイグレーション: `src/database/migrations/2025_12_24_122959_create_cities_table.php`
- スケジューラー設定: `src/bootstrap/app.php`
- 都道府県Enum: `src/app/Enums/Todofuken.php`

## 更新履歴

- 2025-01-XX: 初版作成
  - デジタル庁のアドレス・ベース・レジストリから市区町村データを取得する機能を実装
  - ZIPファイルのダウンロードと解凍機能を追加
  - 月次自動更新のスケジューラー設定を追加

