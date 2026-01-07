<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LogController extends Controller
{
    /**
     * ログファイル一覧とログ内容を表示
     */
    public function index(Request $request)
    {
        $logPath = storage_path('logs');
        $logFiles = [];
        
        // ログディレクトリからファイルを取得
        if (File::exists($logPath)) {
            $files = File::files($logPath);
            
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'log') {
                    $logFiles[] = [
                        'name' => $file->getFilename(),
                        'path' => $file->getPathname(),
                        'size' => $file->getSize(),
                        'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                    ];
                }
            }
            
            // 更新日時でソート（新しい順）
            usort($logFiles, function($a, $b) {
                return strtotime($b['modified']) - strtotime($a['modified']);
            });
        }
        
        // 選択されたログファイル
        $selectedFile = $request->get('file', !empty($logFiles) ? $logFiles[0]['name'] : null);
        $logContent = [];
        $errorCount = 0;
        $warningCount = 0;
        $infoCount = 0;
        $debugCount = 0;
        $level = $request->get('level', 'all');
        $search = $request->get('search', '');
        $date = $request->get('date', '');
        $currentPage = 1;
        $totalPages = 1;
        $total = 0;
        $userIdFilter = $request->get('user_id', ''); // ユーザーID/ユーザー名での検索
        
        // ログファイルが存在しない場合は空の配列を返す
        if (empty($logFiles)) {
            $stats = [
                'error' => 0,
                'warning' => 0,
                'info' => 0,
                'debug' => 0,
                'total' => 0,
            ];
            return view('admin.logs.index', compact(
                'logFiles',
                'selectedFile',
                'logContent',
                'stats',
                'level',
                'search',
                'date',
                'userIdFilter',
                'currentPage',
                'totalPages',
                'total'
            ));
        }
        
        if ($selectedFile && File::exists($logPath . '/' . $selectedFile)) {
            $content = File::get($logPath . '/' . $selectedFile);
            
            
            // ログをパース
            $lines = explode("\n", $content);
            $parsedLogs = [];
            
            foreach ($lines as $line) {
                if (empty(trim($line))) {
                    continue;
                }
                
                // ログエントリをパース（Laravelのログ形式）
                $logEntry = $this->parseLogLine($line);
                
                if ($logEntry) {
                    // レベルフィルタ
                    if ($level !== 'all' && $logEntry['level'] !== $level) {
                        continue;
                    }
                    
                    // 検索フィルタ（メッセージ内の検索）
                    if ($search && stripos($logEntry['message'], $search) === false) {
                        continue;
                    }
                    
                    // 日付フィルタ
                    if ($date && !str_starts_with($logEntry['date'], $date)) {
                        continue;
                    }
                    
                    // ユーザーID/ユーザー名フィルタ
                    if ($userIdFilter) {
                        $userId = $logEntry['user_id'] !== null ? (string)$logEntry['user_id'] : null;
                        $matched = false;
                        
                        // ユーザーIDが存在する場合
                        if ($userId !== null) {
                            // 数値検索の場合は完全一致
                            if (is_numeric($userIdFilter)) {
                                if ($userId === $userIdFilter) {
                                    $matched = true;
                                }
                            } else {
                                // 文字列検索の場合は部分一致
                                if (stripos($userId, $userIdFilter) !== false) {
                                    $matched = true;
                                }
                            }
                        }
                        
                        // メッセージ内にユーザーIDまたはユーザー名が含まれているかチェック
                        if (!$matched && stripos($logEntry['message'], $userIdFilter) !== false) {
                            $matched = true;
                        }
                        
                        if (!$matched) {
                            continue;
                        }
                    }
                    
                    $parsedLogs[] = $logEntry;
                    
                    // 統計（全体）
                    switch ($logEntry['level']) {
                        case 'ERROR':
                        case 'CRITICAL':
                        case 'EMERGENCY':
                            $errorCount++;
                            break;
                        case 'WARNING':
                        case 'ALERT':
                            $warningCount++;
                            break;
                        case 'INFO':
                        case 'NOTICE':
                            $infoCount++;
                            break;
                        case 'DEBUG':
                            $debugCount++;
                            break;
                    }
                } else {
                    // パースできない行は前のログエントリに追加
                    if (!empty($parsedLogs)) {
                        $parsedLogs[count($parsedLogs) - 1]['message'] .= "\n" . $line;
                    }
                }
            }
            
            // 新しい順にソート
            $parsedLogs = array_reverse($parsedLogs);
            
            // ページネーション
            $perPage = 100;
            $currentPage = $request->get('page', 1);
            $total = count($parsedLogs);
            $offset = ($currentPage - 1) * $perPage;
            $logContent = array_slice($parsedLogs, $offset, $perPage);
            
            // ページネーション情報
            $totalPages = ceil($total / $perPage);
        }
        
        $stats = [
            'error' => $errorCount,
            'warning' => $warningCount,
            'info' => $infoCount,
            'debug' => $debugCount,
            'total' => $errorCount + $warningCount + $infoCount + $debugCount,
        ];
        
        return view('admin.logs.index', compact(
            'logFiles',
            'selectedFile',
            'logContent',
            'stats',
            'level',
            'search',
            'date',
            'userIdFilter',
            'currentPage',
            'totalPages',
            'total'
        ));
    }
    
    /**
     * ログ行をパース
     */
    private function parseLogLine($line)
    {
        $logEntry = null;
        $userId = null;
        
        // Laravelのログ形式: [YYYY-MM-DD HH:MM:SS] local.LEVEL: message
        if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]\s+(\w+)\.(\w+):\s+(.+)$/', $line, $matches)) {
            $logEntry = [
                'date' => $matches[1],
                'environment' => $matches[2],
                'level' => $matches[3],
                'message' => $matches[4],
                'raw' => $line,
            ];
        }
        // 別の形式: [YYYY-MM-DD HH:MM:SS] LEVEL: message
        elseif (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]\s+(\w+):\s+(.+)$/', $line, $matches)) {
            $logEntry = [
                'date' => $matches[1],
                'environment' => 'local',
                'level' => $matches[2],
                'message' => $matches[3],
                'raw' => $line,
            ];
        }
        
        if ($logEntry) {
            // メッセージからJSONコンテキストを抽出
            $userId = $this->extractUserIdFromMessage($logEntry['message']);
            $logEntry['user_id'] = $userId;
        }
        
        return $logEntry;
    }
    
    /**
     * メッセージからuser_idを抽出
     * 例: "Something went wrong {"user_id":123,"email":"test@example.com"}"
     * 例: "[2026-01-07 13:00:00] local.ERROR: Something went wrong {"user_id":123}"
     */
    private function extractUserIdFromMessage($message)
    {
        // パターン1: JSONオブジェクト全体を抽出してパース
        // ネストされたJSONにも対応（最後の}までマッチ）
        if (preg_match('/\{[^{}]*(?:\{[^{}]*\}[^{}]*)*"user_id"[^}]*\}/', $message, $jsonMatches)) {
            $jsonStr = $jsonMatches[0];
            $decoded = json_decode($jsonStr, true);
            
            if (is_array($decoded) && isset($decoded['user_id']) && $decoded['user_id'] !== null) {
                return (int)$decoded['user_id'];
            }
        }
        
        // パターン2: より広範囲にJSONを探す（ネストされたJSONにも対応）
        // "user_id":123 の形式を直接抽出
        if (preg_match('/"user_id"\s*:\s*(\d+)/', $message, $matches)) {
            return (int)$matches[1];
        }
        
        // パターン3: シングルクォート形式にも対応
        if (preg_match("/'user_id'\s*:\s*(\d+)/", $message, $matches)) {
            return (int)$matches[1];
        }
        
        // user_idが見つからない場合はnullを返す（後で'guest'に変換）
        return null;
    }
    
    /**
     * ログファイルをダウンロード
     */
    public function download($filename)
    {
        $logPath = storage_path('logs/' . $filename);
        
        if (!File::exists($logPath)) {
            abort(404, 'ログファイルが見つかりません。');
        }
        
        return response()->download($logPath);
    }
    
    /**
     * ログファイルを削除
     */
    public function delete($filename)
    {
        $logPath = storage_path('logs/' . $filename);
        
        if (!File::exists($logPath)) {
            return redirect()->route('admin.logs.index')
                ->with('error', 'ログファイルが見つかりません。');
        }
        
        File::delete($logPath);
        
        return redirect()->route('admin.logs.index')
            ->with('status', 'ログファイルを削除しました。');
    }
    
    /**
     * エラー統計を取得（API）
     */
    public function stats(Request $request)
    {
        $logPath = storage_path('logs');
        $selectedFile = $request->get('file', 'laravel.log');
        $stats = [
            'error' => 0,
            'warning' => 0,
            'info' => 0,
            'debug' => 0,
        ];
        
        if (File::exists($logPath . '/' . $selectedFile)) {
            $content = File::get($logPath . '/' . $selectedFile);
            $lines = explode("\n", $content);
            
            foreach ($lines as $line) {
                if (preg_match('/\.(ERROR|CRITICAL|EMERGENCY):/', $line)) {
                    $stats['error']++;
                } elseif (preg_match('/\.(WARNING|ALERT):/', $line)) {
                    $stats['warning']++;
                } elseif (preg_match('/\.(INFO|NOTICE):/', $line)) {
                    $stats['info']++;
                } elseif (preg_match('/\.DEBUG:/', $line)) {
                    $stats['debug']++;
                }
            }
        }
        
        $stats['total'] = array_sum($stats);
        
        return response()->json($stats);
    }
}

