@echo off
echo Setting up Laravel Scheduler for Payroll Automation...
echo.

echo 1. Testing payroll command...
php artisan payroll:generate --help
echo.

echo 2. To enable automatic scheduling, add this to your system's task scheduler:
echo    Command: php artisan schedule:run
echo    Working Directory: %cd%
echo    Schedule: Every minute
echo.

echo 3. Or run manually each month:
echo    php artisan payroll:generate
echo    php artisan payroll:generate --auto-pay
echo.

echo 4. Test the automation:
php artisan payroll:generate --month=%date:~-4%-%date:~4,2%
echo.

pause