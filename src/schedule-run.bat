@echo off
REM Laravelスケジューラーを実行するバッチファイル
REM このファイルはWindowsタスクスケジューラから呼び出されます

REM このスクリプトがあるディレクトリに移動
cd /d "%~dp0"

REM Laravelスケジューラーを実行
REM ログは storage\logs\schedule.log に出力されます
php artisan schedule:run >> storage\logs\schedule.log 2>&1

REM エラーコードを返す（オプション）
if %ERRORLEVEL% NEQ 0 (
    echo [%date% %time%] Schedule run failed with error code %ERRORLEVEL% >> storage\logs\schedule.log
    exit /b %ERRORLEVEL%
)

