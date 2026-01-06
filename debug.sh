#!/bin/bash

# FH Maison - Debug/Troubleshooting Script
# Run this to diagnose issues with the application

echo "=========================================="
echo "FH Maison - System Diagnostics"
echo "=========================================="
echo ""

APP_DIR="/var/www/fhmaison"
cd $APP_DIR

echo "1. PHP Version:"
php -v | head -n 1
echo ""

echo "2. Laravel Version:"
php artisan --version
echo ""

echo "3. Environment Configuration:"
echo "APP_ENV: $(grep APP_ENV .env | cut -d '=' -f2)"
echo "APP_DEBUG: $(grep APP_DEBUG .env | cut -d '=' -f2)"
echo "APP_URL: $(grep APP_URL .env | cut -d '=' -f2)"
echo ""

echo "4. Database Connection:"
php artisan db:show --database=mysql 2>&1 | head -n 5 || echo "Database connection failed"
echo ""

echo "5. Storage Permissions:"
ls -ld storage storage/logs storage/framework storage/framework/views
echo ""

echo "6. Recent Laravel Logs (last 20 lines):"
if [ -f "storage/logs/laravel.log" ]; then
    tail -20 storage/logs/laravel.log
else
    echo "No log file found"
fi
echo ""

echo "7. PHP-FPM Status:"
systemctl status php8.3-fpm --no-pager | head -n 5
echo ""

echo "8. Nginx Status:"
systemctl status nginx --no-pager | head -n 5
echo ""

echo "9. Routes (first 20):"
php artisan route:list | head -n 20
echo ""

echo "10. Disk Space:"
df -h /var/www/fhmaison
echo ""

echo "=========================================="
echo "Diagnostics Complete"
echo "=========================================="
