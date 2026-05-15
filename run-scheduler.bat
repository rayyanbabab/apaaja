@echo off
:: ============================================================
:: Artilia Inventory - Daily Scheduler Runner
:: Jalankan: php artisan schedule:run
:: Dipanggil oleh Windows Task Scheduler setiap menit
:: ============================================================

cd /d "c:\laragon\www\artilia"
php artisan schedule:run >> storage\logs\scheduler.log 2>&1
