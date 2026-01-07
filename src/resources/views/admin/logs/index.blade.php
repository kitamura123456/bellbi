@extends('layouts.admin')

@section('title', 'ログ管理')

@section('content')
<style>
    .log-viewer-container {
        display: flex;
        gap: 20px;
        flex-direction: column;
    }

    .log-files-panel {
        background: #fff;
        border: 1px solid #c3c4c7;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
        padding: 20px;
    }

    .log-files-panel h3 {
        margin: 0 0 16px 0;
        font-size: 16px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .log-file-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .log-file-item {
        padding: 8px 12px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: background 0.2s;
    }

    .log-file-item:hover {
        background: #f8fafc;
    }

    .log-file-item.active {
        background: #e7f3ff;
        border-left: 4px solid #2271b1;
    }

    .log-file-name {
        font-weight: 500;
        color: #1a1a1a;
        font-size: 13px;
    }

    .log-file-info {
        font-size: 11px;
        color: #666;
        margin-top: 4px;
    }

    .log-filters {
        background: #fff;
        border: 1px solid #c3c4c7;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
        padding: 20px;
        margin-bottom: 20px;
    }

    .log-filters h3 {
        margin: 0 0 16px 0;
        font-size: 16px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .filter-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .filter-item {
        flex: 1;
        min-width: 200px;
    }

    .filter-item label {
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
        font-weight: 500;
        color: #1a1a1a;
    }

    .filter-item input,
    .filter-item select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #8c8f94;
        border-radius: 3px;
        font-size: 13px;
    }

    .filter-actions {
        display: flex;
        gap: 8px;
    }

    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 3px;
        font-size: 13px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: #2271b1;
        color: #fff;
    }

    .btn-primary:hover {
        background: #135e96;
    }

    .btn-secondary {
        background: #f6f7f7;
        color: #2c3338;
        border: 1px solid #dcdcde;
    }

    .btn-secondary:hover {
        background: #f0f0f1;
    }

    .btn-danger {
        background: #b32d2e;
        color: #fff;
    }

    .btn-danger:hover {
        background: #dc3232;
    }

    .log-stats {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .stat-card {
        flex: 1;
        min-width: 150px;
        background: #fff;
        border: 1px solid #c3c4c7;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
        padding: 16px;
        border-left: 4px solid;
    }

    .stat-card.error {
        border-left-color: #dc3232;
    }

    .stat-card.warning {
        border-left-color: #f0b849;
    }

    .stat-card.info {
        border-left-color: #2271b1;
    }

    .stat-card.debug {
        border-left-color: #666;
    }

    .stat-label {
        font-size: 12px;
        color: #666;
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .log-content-panel {
        background: #fff;
        border: 1px solid #c3c4c7;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
        padding: 20px;
    }

    .log-content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }

    .log-content-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .log-entries {
        font-family: 'Courier New', monospace;
        font-size: 12px;
        line-height: 1.6;
        max-height: 600px;
        overflow-y: auto;
        background: #1e1e1e;
        color: #d4d4d4;
        padding: 16px;
        border-radius: 4px;
    }

    .log-entry {
        padding: 8px 0;
        border-bottom: 1px solid #2d2d2d;
    }

    .log-entry:last-child {
        border-bottom: none;
    }

    .log-entry.error {
        background: rgba(220, 50, 50, 0.1);
        border-left: 3px solid #dc3232;
        padding-left: 12px;
    }

    .log-entry.warning {
        background: rgba(240, 184, 73, 0.1);
        border-left: 3px solid #f0b849;
        padding-left: 12px;
    }

    .log-entry.info {
        background: rgba(34, 113, 177, 0.1);
        border-left: 3px solid #2271b1;
        padding-left: 12px;
    }

    .log-entry.debug {
        color: #888;
    }

    .log-date {
        color: #888;
        margin-right: 8px;
    }

    .log-level {
        font-weight: 600;
        margin-right: 8px;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 11px;
    }

    .log-level.ERROR,
    .log-level.CRITICAL,
    .log-level.EMERGENCY {
        background: #dc3232;
        color: #fff;
    }

    .log-level.WARNING,
    .log-level.ALERT {
        background: #f0b849;
        color: #000;
    }

    .log-level.INFO,
    .log-level.NOTICE {
        background: #2271b1;
        color: #fff;
    }

    .log-level.DEBUG {
        background: #666;
        color: #fff;
    }

    .log-message {
        color: #d4d4d4;
        word-break: break-all;
        white-space: pre-wrap;
    }

    .empty-log {
        text-align: center;
        padding: 40px 20px;
        color: #666;
    }

    .pagination-wrapper {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    .user-stats-panel {
        background: #fff;
        border: 1px solid #c3c4c7;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
        padding: 20px;
        margin-bottom: 20px;
    }

    .user-stats-panel h3 {
        margin: 0 0 16px 0;
        font-size: 16px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .user-stats-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .user-stats-table thead {
        background-color: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }

    .user-stats-table th {
        padding: 10px 12px;
        text-align: left;
        font-weight: 600;
        color: #334155;
        font-size: 13px;
    }

    .user-stats-table td {
        padding: 12px;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
    }

    .user-stats-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .user-id-cell {
        font-weight: 600;
        color: #2271b1;
    }

    .user-id-cell.guest {
        color: #666;
        font-style: italic;
    }

    .count-cell {
        text-align: right;
        font-family: 'Courier New', monospace;
    }

    .count-cell.error {
        color: #dc3232;
        font-weight: 600;
    }

    .count-cell.warning {
        color: #f0b849;
    }

    .count-cell.info {
        color: #2271b1;
    }

    .count-cell.debug {
        color: #666;
    }

    .count-cell.total {
        font-weight: 600;
        color: #1a1a1a;
    }

    @media (max-width: 782px) {
        .log-viewer-container {
            gap: 16px;
        }

        .filter-group {
            flex-direction: column;
        }

        .filter-item {
            min-width: 100%;
        }

        .log-stats {
            flex-direction: column;
        }

        .stat-card {
            min-width: 100%;
        }

        .log-content-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
    }
</style>

<div class="log-viewer-container">
    <!-- 統計情報 -->
    <div class="log-stats">
        <div class="stat-card error">
            <div class="stat-label">エラー</div>
            <div class="stat-value">{{ $stats['error'] ?? 0 }}</div>
        </div>
        <div class="stat-card warning">
            <div class="stat-label">警告</div>
            <div class="stat-value">{{ $stats['warning'] ?? 0 }}</div>
        </div>
        <div class="stat-card info">
            <div class="stat-label">情報</div>
            <div class="stat-value">{{ $stats['info'] ?? 0 }}</div>
        </div>
        <div class="stat-card debug">
            <div class="stat-label">デバッグ</div>
            <div class="stat-value">{{ $stats['debug'] ?? 0 }}</div>
        </div>
    </div>

    <!-- フィルター -->
    <div class="log-filters">
        <h3>フィルター</h3>
        <form method="GET" action="{{ route('admin.logs.index') }}">
            <input type="hidden" name="file" value="{{ $selectedFile }}">
            
            <div class="filter-group">
                <div class="filter-item">
                    <label>ログファイル</label>
                    <select name="file" onchange="this.form.submit()">
                        @forelse($logFiles as $file)
                            <option value="{{ $file['name'] }}" {{ $selectedFile === $file['name'] ? 'selected' : '' }}>
                                {{ $file['name'] }} ({{ number_format($file['size'] / 1024, 2) }} KB)
                            </option>
                        @empty
                            <option value="">ログファイルが見つかりません</option>
                        @endforelse
                    </select>
                </div>
                
                <div class="filter-item">
                    <label>ログレベル</label>
                    <select name="level">
                        <option value="all" {{ isset($level) && $level === 'all' ? 'selected' : '' }}>すべて</option>
                        <option value="ERROR" {{ isset($level) && $level === 'ERROR' ? 'selected' : '' }}>エラー</option>
                        <option value="WARNING" {{ isset($level) && $level === 'WARNING' ? 'selected' : '' }}>警告</option>
                        <option value="INFO" {{ isset($level) && $level === 'INFO' ? 'selected' : '' }}>情報</option>
                        <option value="DEBUG" {{ isset($level) && $level === 'DEBUG' ? 'selected' : '' }}>デバッグ</option>
                    </select>
                </div>
                
                <div class="filter-item">
                    <label>日付（YYYY-MM-DD）</label>
                    <input type="date" name="date" value="{{ $date ?? '' }}" placeholder="例: 2024-01-01">
                </div>
                
                <div class="filter-item">
                    <label>検索キーワード</label>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="メッセージ内を検索...">
                </div>
                
                <div class="filter-item">
                    <label>ユーザーID/ユーザー名</label>
                    <input type="text" name="user_id" value="{{ $userIdFilter ?? '' }}" placeholder="ユーザーIDまたはユーザー名で検索">
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">適用</button>
                    <a href="{{ route('admin.logs.index', ['file' => $selectedFile]) }}" class="btn btn-secondary">リセット</a>
                </div>
            </div>
        </form>
    </div>

    <!-- ログファイル一覧（モバイル用） -->
    <div class="log-files-panel" style="display: none;">
        <h3>ログファイル</h3>
        <ul class="log-file-list">
            @foreach($logFiles as $file)
                <li class="log-file-item {{ $selectedFile === $file['name'] ? 'active' : '' }}">
                    <a href="{{ route('admin.logs.index', ['file' => $file['name']]) }}" style="text-decoration: none; color: inherit;">
                        <div class="log-file-name">{{ $file['name'] }}</div>
                        <div class="log-file-info">
                            {{ number_format($file['size'] / 1024, 2) }} KB | {{ $file['modified'] }}
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- ログ内容 -->
    <div class="log-content-panel">
        <div class="log-content-header">
            <h3>ログ内容: {{ $selectedFile ?? 'ログファイルを選択してください' }}</h3>
            <div style="display: flex; gap: 8px;">
                @if($selectedFile)
                    <a href="{{ route('admin.logs.download', $selectedFile) }}" class="btn btn-secondary">ダウンロード</a>
                    <form action="{{ route('admin.logs.delete', $selectedFile) }}" method="POST" style="display: inline;" onsubmit="return confirm('このログファイルを削除してもよろしいですか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">削除</button>
                    </form>
                @endif
            </div>
        </div>

        @if(empty($logContent))
            <div class="empty-log">
                ログが見つかりませんでした。
            </div>
        @else
            <div class="log-entries">
                @foreach($logContent as $entry)
                    <div class="log-entry {{ strtolower($entry['level']) }}">
                        <span class="log-date">{{ $entry['date'] }}</span>
                        <span class="log-level {{ $entry['level'] }}">{{ $entry['level'] }}</span>
                        <span class="log-message">{{ $entry['message'] }}</span>
                    </div>
                @endforeach
            </div>

            @if(isset($totalPages) && $totalPages > 1)
                <div class="pagination-wrapper">
                    <div style="display: flex; gap: 8px; align-items: center;">
                        @if(isset($currentPage) && $currentPage > 1)
                            <a href="{{ route('admin.logs.index', array_merge(request()->query(), ['page' => $currentPage - 1])) }}" class="btn btn-secondary">前へ</a>
                        @endif
                        
                        <span style="font-size: 13px; color: #666;">
                            ページ {{ $currentPage ?? 1 }} / {{ $totalPages ?? 1 }} (合計: {{ $total ?? 0 }} 件)
                        </span>
                        
                        @if(isset($currentPage) && isset($totalPages) && $currentPage < $totalPages)
                            <a href="{{ route('admin.logs.index', array_merge(request()->query(), ['page' => $currentPage + 1])) }}" class="btn btn-secondary">次へ</a>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

