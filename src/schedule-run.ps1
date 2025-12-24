# Laravelスケジューラーを実行するPowerShellスクリプト
# このファイルはWindowsタスクスケジューラから呼び出されます

# このスクリプトがあるディレクトリに移動
Set-Location $PSScriptRoot

# ログファイルのパス
$logFile = Join-Path $PSScriptRoot "storage\logs\schedule.log"

# Laravelスケジューラーを実行
# ログは storage\logs\schedule.log に出力されます
try {
    php artisan schedule:run >> $logFile 2>&1
    
    if ($LASTEXITCODE -ne 0) {
        $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
        "[$timestamp] Schedule run failed with error code $LASTEXITCODE" | Out-File -FilePath $logFile -Append -Encoding UTF8
        exit $LASTEXITCODE
    }
} catch {
    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    "[$timestamp] Schedule run error: $_" | Out-File -FilePath $logFile -Append -Encoding UTF8
    exit 1
}

