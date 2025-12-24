<?php

namespace App\Console\Commands;

use App\Enums\Todofuken;
use App\Models\City;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImportCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cities:import {--debug : デバッグ情報を表示} {--pref= : 特定の都道府県コードのみ実行}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'デジタル庁の市区町村マスターデータを取得してインポートします';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('市区町村データのインポートを開始します...');

        $successCount = 0;
        $errorCount = 0;

        // 特定の都道府県コードが指定されている場合はそれのみ実行
        $startCode = 1;
        $endCode = 47;
        if ($this->option('pref')) {
            $prefCode = (int)$this->option('pref');
            if ($prefCode >= 1 && $prefCode <= 47) {
                $startCode = $prefCode;
                $endCode = $prefCode;
                $this->info("都道府県コード {$prefCode} のみ実行します");
            } else {
                $this->error("都道府県コードは1から47の範囲で指定してください");
                return Command::FAILURE;
            }
        }

        // 都道府県コードを巡回
        for ($todofukenCode = $startCode; $todofukenCode <= $endCode; $todofukenCode++) {
            try {
                $this->info("都道府県コード {$todofukenCode} のデータを取得中...");
                
                $todofuken = Todofuken::from($todofukenCode);
                $todofukenName = $todofuken->label();
                
                // デジタル庁のAPIからCSVを取得
                $csvData = $this->fetchCsvData($todofukenCode);
                
                if ($csvData === null) {
                    $this->warn("都道府県コード {$todofukenCode} ({$todofukenName}) のデータ取得に失敗しました");
                    $errorCount++;
                    continue;
                }

                // CSVをパースしてデータベースに保存
                $imported = $this->importCsvData($csvData, $todofukenCode, $todofukenName);
                
                if ($imported > 0) {
                    $this->info("都道府県コード {$todofukenCode} ({$todofukenName}): {$imported}件の市区町村データをインポートしました");
                    $successCount++;
                } else {
                    $this->warn("都道府県コード {$todofukenCode} ({$todofukenName}): データが見つかりませんでした");
                }
                
            } catch (\Exception $e) {
                $this->error("都道府県コード {$todofukenCode} の処理中にエラーが発生しました: " . $e->getMessage());
                Log::error("市区町村データインポートエラー (都道府県コード: {$todofukenCode})", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                $errorCount++;
            }
        }

        $this->info("インポート完了: 成功 {$successCount}件、失敗 {$errorCount}件");
        
        return Command::SUCCESS;
    }

    /**
     * デジタル庁のAPIからCSVデータを取得
     */
    private function fetchCsvData(int $todofukenCode): ?string
    {
        // 都道府県コードを2桁の文字列に変換（例: 1 -> 01, 13 -> 13）
        $todofukenCodeStr = str_pad($todofukenCode, 2, '0', STR_PAD_LEFT);
        
        // デジタル庁の市区町村マスターデータのURL形式
        // https://data.address-br.digital.go.jp/mt_city/pref/mt_city_pref{都道府県コード}.csv.zip
        // 都道府県コードは2桁のゼロパディングが必要
        $csvZipUrl = "https://data.address-br.digital.go.jp/mt_city/pref/mt_city_pref{$todofukenCodeStr}.csv.zip";
        
        try {
            if ($this->option('debug')) {
                $this->line("ダウンロード中: {$csvZipUrl}");
            }
            
            // ZIPファイルをダウンロード
            $response = Http::timeout(60)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'application/zip,application/octet-stream,*/*',
                ])
                ->get($csvZipUrl);
            
            if (!$response->successful()) {
                if ($this->option('debug')) {
                    $this->warn("ダウンロード失敗 (HTTP {$response->status()}): {$csvZipUrl}");
                }
                return null;
            }
            
            // ZIPファイルの内容を取得
            $zipContent = $response->body();
            
            if (empty($zipContent)) {
                if ($this->option('debug')) {
                    $this->warn("ZIPファイルが空です: {$csvZipUrl}");
                }
                return null;
            }
            
            // ZIPファイルを一時ファイルに保存
            $tempZipPath = sys_get_temp_dir() . '/mt_city_pref' . $todofukenCode . '_' . uniqid() . '.zip';
            file_put_contents($tempZipPath, $zipContent);
            
            try {
                // ZIPファイルを解凍
                $zip = new \ZipArchive();
                if ($zip->open($tempZipPath) !== true) {
                    if ($this->option('debug')) {
                        $this->warn("ZIPファイルの解凍に失敗しました: {$tempZipPath}");
                    }
                    return null;
                }
                
                // ZIP内のCSVファイルを探す
                $csvContent = null;
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    if (pathinfo($filename, PATHINFO_EXTENSION) === 'csv') {
                        $csvContent = $zip->getFromIndex($i);
                        break;
                    }
                }
                
                $zip->close();
                
                // 一時ファイルを削除
                @unlink($tempZipPath);
                
                if ($csvContent === null) {
                    if ($this->option('debug')) {
                        $this->warn("ZIPファイル内にCSVファイルが見つかりませんでした: {$tempZipPath}");
                    }
                    return null;
                }
                
                if ($this->option('debug')) {
                    $this->info("CSVデータの取得に成功しました");
                }
                
                return $csvContent;
                
            } catch (\Exception $e) {
                // 一時ファイルを削除
                @unlink($tempZipPath);
                throw $e;
            }
            
        } catch (\Exception $e) {
            Log::error("CSVデータ取得エラー (都道府県コード: {$todofukenCode})", [
                'error' => $e->getMessage(),
                'todofuken_code' => $todofukenCode,
                'url' => $csvZipUrl,
                'trace' => $e->getTraceAsString(),
            ]);
            
            if ($this->option('debug')) {
                $this->error("エラー: " . $e->getMessage());
            }
            
            return null;
        }
    }

    /**
     * CSVデータをパースしてデータベースに保存
     */
    private function importCsvData(string $csvData, int $todofukenCode, string $todofukenName): int
    {
        // 文字エンコーディングをUTF-8に変換
        if (!mb_check_encoding($csvData, 'UTF-8')) {
            $csvData = mb_convert_encoding($csvData, 'UTF-8', 'SJIS, EUC-JP, JIS, ASCII');
        }
        
        $lines = explode("\n", $csvData);
        $imported = 0;
        
        // ヘッダー行を検出してスキップ
        $startIndex = 0;
        $headerRow = null;
        if (count($lines) > 0) {
            $firstLine = mb_strtolower($lines[0]);
            if (strpos($firstLine, '都道府県') !== false || 
                strpos($firstLine, '市区町村') !== false ||
                strpos($firstLine, 'code') !== false ||
                strpos($firstLine, 'name') !== false ||
                strpos($firstLine, '地方公共団体') !== false) {
                $headerRow = $lines[0];
                $startIndex = 1;
            }
        }
        
        // デバッグモードで最初の数行を表示
        if ($this->option('debug') && count($lines) > 0) {
            $this->line("CSVの最初の5行:");
            for ($i = 0; $i < min(5, count($lines)); $i++) {
                $this->line("  [{$i}]: " . mb_substr($lines[$i], 0, 100));
            }
        }
        
        // 既存のデータを削除（都道府県ごとに更新）
        City::where('prefecture_code', $todofukenCode)->delete();
        
        for ($i = $startIndex; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            if (empty($line)) {
                continue;
            }
            
            // CSVをパース（カンマ区切り）
            $data = str_getcsv($line, ',', '"', '\\');
            
            // データが不足している場合はスキップ
            if (count($data) < 3) {
                continue;
            }
            
            // デジタル庁のCSV形式:
            // lg_code,pref,pref_kana,pref_roma,county,county_kana,county_roma,city,city_kana,city_roma,ward,ward_kana,ward_roma,effective_date,abolition_date,remarks
            // 0: lg_code (全国地方公共団体コード)
            // 1: pref (都道府県名)
            // 2: pref_kana
            // 3: pref_roma
            // 4: county (郡名)
            // 5: county_kana
            // 6: county_roma
            // 7: city (市区町村名)
            // 8: city_kana (市区町村名カナ)
            // 9: city_roma
            // 10: ward (区名)
            // 11: ward_kana
            // 12: ward_roma
            // 13: effective_date
            // 14: abolition_date
            // 15: remarks
            
            $lgCode = trim($data[0] ?? '');
            $prefName = trim($data[1] ?? '');
            $cityName = trim($data[7] ?? ''); // city列
            $cityKana = trim($data[8] ?? ''); // city_kana列
            $wardName = trim($data[10] ?? ''); // ward列
            
            // 全国地方公共団体コードから都道府県コードを確認
            if (!empty($lgCode) && strlen($lgCode) >= 2) {
                $codeTodofuken = (int)substr($lgCode, 0, 2);
                if ($codeTodofuken !== $todofukenCode) {
                    // 都道府県コードが一致しない場合はスキップ
                    continue;
                }
                // 市区町村コード部分を取得（3桁目以降）
                $cityCode = substr($lgCode, 2);
            } else {
                $cityCode = null;
            }
            
            // 市区町村名が空の場合はスキップ
            if (empty($cityName)) {
                continue;
            }
            
            // 区名がある場合は市区町村名に含める（例: 大分市 大分 → 大分市大分）
            if (!empty($wardName)) {
                $cityName = $cityName . $wardName;
            }
            
            // 市区町村名が都道府県名と同じ場合はスキップ（ヘッダー行の可能性）
            if ($cityName === $prefName || $cityName === '都道府県名' || $cityName === '市区町村名' || $cityName === 'city') {
                continue;
            }
            
            try {
                City::updateOrCreate(
                    [
                        'prefecture_code' => $todofukenCode,
                        'city_code' => $cityCode,
                    ],
                    [
                        'name' => $cityName,
                        'name_kana' => $cityKana,
                    ]
                );
                $imported++;
            } catch (\Exception $e) {
                Log::warning("市区町村データの保存に失敗", [
                    'prefecture_code' => $todofukenCode,
                    'city_code' => $cityCode,
                    'name' => $cityName,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        return $imported;
    }
}
