#!/bin/bash

# FH Maison - Complete Deployment Script
# This script will pull latest code, fix permissions, optimize, and restart services

set -e  # Exit on any error

echo "=========================================="
echo "FH Maison - Deployment Script"
echo "=========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
APP_DIR="/var/www/fhmaison"
PHP_VERSION="8.3"

echo -e "${YELLOW}Step 1/10: Entering application directory...${NC}"
cd $APP_DIR

echo -e "${YELLOW}Step 2/10: Putting application in maintenance mode...${NC}"
sudo php artisan down || true

echo -e "${YELLOW}Step 3/10: Pulling latest code from GitHub...${NC}"
sudo git fetch origin
sudo git reset --hard origin/master

echo -e "${YELLOW}Step 4/10: Installing/Updating Composer dependencies...${NC}"
sudo composer install --no-dev --optimize-autoloader --no-interaction

echo -e "${YELLOW}Step 5/10: Installing/Updating NPM dependencies...${NC}"
sudo npm ci --production=false

echo -e "${YELLOW}Step 6/10: Building frontend assets...${NC}"
sudo npm run build

echo -e "${YELLOW}Step 7/10: Running database migrations...${NC}"
sudo php artisan migrate --force

echo -e "${YELLOW}Step 8/10: Fixing file permissions...${NC}"
# Fix ownership
sudo chown -R www-data:www-data $APP_DIR
sudo chown -R www-data:www-data $APP_DIR/storage
sudo chown -R www-data:www-data $APP_DIR/bootstrap/cache

# Fix permissions
sudo find $APP_DIR/storage -type f -exec chmod 664 {} \;
sudo find $APP_DIR/storage -type d -exec chmod 775 {} \;
sudo find $APP_DIR/bootstrap/cache -type f -exec chmod 664 {} \;
sudo find $APP_DIR/bootstrap/cache -type d -exec chmod 775 {} \;

echo -e "${YELLOW}Step 9/10: Clearing and optimizing caches...${NC}"
sudo php artisan config:clear
sudo php artisan route:clear
sudo php artisan view:clear
sudo php artisan cache:clear

# Optimize for production
# Note: route:cache is NOT used because Laravel Localization doesn't work with cached routes
sudo php artisan config:cache
sudo php artisan view:cache

echo -e "${YELLOW}Step 10/10: Restarting services...${NC}"
sudo systemctl restart php${PHP_VERSION}-fpm
sudo systemctl restart nginx

echo -e "${YELLOW}Bringing application back online...${NC}"
sudo php artisan up

echo ""
echo -e "${GREEN}=========================================="
echo -e "Deployment completed successfully!"
echo -e "==========================================${NC}"
echo ""
echo "Application URL: https://fhmaison.fr"
echo ""
echo "Available locales:"
echo "  - English: https://fhmaison.fr/en"
echo "  - French:  https://fhmaison.fr/fr"
echo "  - Arabic:  https://fhmaison.fr/ar"
echo ""
echo "Admin panel: https://fhmaison.fr/en/admin/dashboard"
echo ""
