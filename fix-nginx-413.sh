#!/bin/bash
# Fix 413 Request Entity Too Large on Production Server
# Run this script on the server: bash fix-nginx-413.sh

echo "=== Fixing Nginx 413 Error ==="

# Backup current nginx config
echo "1. Backing up nginx configuration..."
sudo cp /etc/nginx/sites-available/fhmaison.conf /etc/nginx/sites-available/fhmaison.conf.backup.$(date +%Y%m%d_%H%M%S)

# Check if client_max_body_size exists in site config
if sudo grep -q "client_max_body_size" /etc/nginx/sites-available/fhmaison.conf; then
    echo "2. Updating existing client_max_body_size..."
    sudo sed -i 's/client_max_body_size.*$/client_max_body_size 100M;/' /etc/nginx/sites-available/fhmaison.conf
else
    echo "2. Adding client_max_body_size to server block..."
    sudo sed -i '/server_name fhmaison.fr/a \    client_max_body_size 100M;' /etc/nginx/sites-available/fhmaison.conf
fi

# Also update main nginx.conf
echo "3. Updating main nginx configuration..."
if ! sudo grep -q "client_max_body_size" /etc/nginx/nginx.conf; then
    sudo sed -i '/http {/a \    client_max_body_size 100M;' /etc/nginx/nginx.conf
fi

# Update PHP settings
echo "4. Updating PHP-FPM settings..."
PHP_VERSION=$(php -v | grep -oP 'PHP \K[0-9]\.[0-9]' | head -1)
PHP_INI="/etc/php/${PHP_VERSION}/fpm/php.ini"

if [ -f "$PHP_INI" ]; then
    sudo sed -i 's/^upload_max_filesize.*$/upload_max_filesize = 100M/' "$PHP_INI"
    sudo sed -i 's/^post_max_size.*$/post_max_size = 100M/' "$PHP_INI"
    sudo sed -i 's/^max_execution_time.*$/max_execution_time = 300/' "$PHP_INI"
    echo "   Updated PHP settings in $PHP_INI"
else
    echo "   PHP config not found at $PHP_INI"
fi

# Test nginx configuration
echo "5. Testing nginx configuration..."
if sudo nginx -t; then
    echo "   ✓ Nginx configuration is valid"
    
    # Reload nginx
    echo "6. Reloading nginx..."
    sudo systemctl reload nginx
    echo "   ✓ Nginx reloaded"
    
    # Restart PHP-FPM
    echo "7. Restarting PHP-FPM..."
    sudo systemctl restart php${PHP_VERSION}-fpm
    echo "   ✓ PHP-FPM restarted"
    
    echo ""
    echo "=== SUCCESS! ==="
    echo "Upload limit has been increased to 100MB"
    echo "You can now upload larger images in the admin panel"
else
    echo "   ✗ Nginx configuration test failed!"
    echo "   Restoring backup..."
    sudo cp /etc/nginx/sites-available/fhmaison.conf.backup.* /etc/nginx/sites-available/fhmaison.conf
    echo "   Please check the configuration manually"
fi
