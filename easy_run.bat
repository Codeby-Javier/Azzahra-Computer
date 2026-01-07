@echo off
chcp 65001 > nul
setlocal enabledelayedexpansion

color 0A
echo ========================================
echo   CV AZZAHRA COMPUTER - Web Server
echo ========================================
echo.
echo Server akan berjalan di: http://localhost:8000
echo.

REM Kill any existing PHP server on port 8000
echo [CLEANUP] Membersihkan proses lama...
for /f "tokens=5" %%a in ('netstat -aon ^| find ":8000" ^| find "LISTENING"') do (
    taskkill /F /PID %%a >nul 2>&1
)

echo.
echo INSTRUKSI:
echo - Tekan ENTER untuk menjalankan server
echo - Browser akan terbuka otomatis
echo - Tekan Ctrl+C untuk menghentikan server
echo.
echo CATATAN:
echo - Pastikan database sudah disetup: mysql -u root -p azzahra ^< hr_database.sql
echo - Jika ada masalah HR input, baca PANDUAN_PERBAIKAN_HR.md
echo.
echo ========================================
echo.
pause

cd /d "%~dp0"

echo.
echo [INFO] Memulai server...
echo [INFO] Membuka browser...
echo.

REM Buka browser otomatis
start http://localhost:8000

REM Delay singkat agar browser sempat terbuka
timeout /t 1 /nobreak > nul

echo [RUNNING] Server berjalan di http://localhost:8000
echo [RUNNING] Semua asset (HTML, CSS, JS, images) akan dimuat
echo.
echo Tekan Ctrl+C untuk menghentikan...
echo ========================================
echo.

REM Jalankan PHP built-in server dengan router untuk CodeIgniter
php -S localhost:8000 router.php

echo.
echo [STOPPED] Web server telah dihentikan.
echo.
pause