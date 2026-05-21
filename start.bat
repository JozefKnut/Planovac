@echo off
echo Spustam MySQL...
start "" "C:\Program Files\MySQL\MySQL Server 8.4\bin\mysqld.exe" --datadir="C:\ProgramData\MySQL\MySQL Server 8.4\Data" --log-error="C:\ProgramData\MySQL\mysqld.err" --port=3306

timeout /t 4 /nobreak >nul

echo Spustam Laravel server...
start "Laravel" cmd /k "cd /d C:\Users\jozef\laravel-app && C:\Users\jozef\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.4_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe artisan serve --port=8000"

echo Spustam Vite (frontend)...
start "Vite" cmd /k "cd /d C:\Users\jozef\laravel-app && npm run dev"

echo.
echo Vsetko bezi! Otvor http://localhost:8000 v prehliadaci.
pause
