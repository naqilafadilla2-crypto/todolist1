@echo off
echo Starting Laravel Scheduler...
echo This will run every minute to check device status
echo Press Ctrl+C to stop

:loop
cd /d "%~dp0"
php artisan schedule:run
timeout /t 60 /nobreak >nul
goto loop
