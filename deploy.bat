@echo off
REM SIGMA Quick Deploy - Windows

set SERVER=root@161.35.46.18
set PASSWORD=sigma$S1lab
set REMOTE_PATH=/var/www/sigma

echo =========================================
echo    SIGMA Quick Deploy
echo =========================================
echo.

REM Check if plink is available
where plink >nul 2>nul
if %errorlevel% neq 0 (
    echo ERROR: PuTTY plink not found!
    echo Download from: https://www.putty.org/
    echo Or use Git Bash: bash deploy.sh
    pause
    exit /b 1
)

echo [1/2] Syncing files to server...
echo.

REM Sync app, resources, routes, config
echo Syncing app/...
echo y | pscp -v -r -batch -pw "%PASSWORD%" app %SERVER%:%REMOTE_PATH%/
echo.
echo Syncing resources/...
echo y | pscp -v -r -batch -pw "%PASSWORD%" resources %SERVER%:%REMOTE_PATH%/
echo.
echo Syncing routes/...
echo y | pscp -v -r -batch -pw "%PASSWORD%" routes %SERVER%:%REMOTE_PATH%/
echo.
echo Syncing config/...
echo y | pscp -v -r -batch -pw "%PASSWORD%" config %SERVER%:%REMOTE_PATH%/
echo.
echo Syncing public/js/...
echo y | pscp -v -r -batch -pw "%PASSWORD%" public\js %SERVER%:%REMOTE_PATH%/public/
echo.
echo Syncing public/css/...
echo y | pscp -v -r -batch -pw "%PASSWORD%" public\css %SERVER%:%REMOTE_PATH%/public/

if errorlevel 1 (
    echo.
    echo ERROR: File sync failed!
    pause
    exit /b 1
)

echo.
echo [2/2] Clearing caches...
plink -batch -pw "%PASSWORD%" %SERVER% "cd %REMOTE_PATH% && php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear"

echo.
echo =========================================
echo    Deploy Complete!
echo =========================================
echo.
echo Synced:
echo   - app/
echo   - resources/
echo   - routes/
echo   - config/
echo   - public/js/
echo   - public/css/
echo.

pause
