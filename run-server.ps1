# =========================================
# SIGAP-AIR Development Server Starter (PowerShell)
# Automatically sets DB_PORT=3309 for MySQL
# =========================================

Write-Host ""
Write-Host "Starting SIGAP-AIR on http://localhost:8000" -ForegroundColor Cyan
Write-Host "DB_PORT set to: 3309" -ForegroundColor Yellow
Write-Host ""

$env:DB_PORT = "3309"
php artisan serve --port=8000

Read-Host "Press Enter to exit"
