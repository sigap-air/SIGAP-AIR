@echo off
REM =========================================
REM SIGAP-AIR Development Server Starter
REM Automatically sets DB_PORT=3309 for MySQL
REM =========================================

echo.
echo Starting SIGAP-AIR on http://localhost:8000
echo DB_PORT set to: 3309
echo.

set DB_PORT=3309
php artisan serve --port=8000

pause
