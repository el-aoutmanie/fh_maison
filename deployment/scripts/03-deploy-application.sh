#!/bin/bash

###############################################################################
# FH Maison - Application Deployment Script
###############################################################################

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration
APP_DIR="/var/www/fhmaison"
DEPLOY_USER="www-data"
PHP_VERSION="8.3"

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}FH Maison - Application Deployment${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}Please run as root (use sudo)${NC}"
    exit 1
fi

echo -e "${YELLOW}[1/11] Creating application directory...${NC}"
mkdir -p "${APP_DIR}"

echo -e "${YELLOW}[2/11] Copying application files...${NC}"
if [ -d "/home/ubuntu/fh_maison" ]; then
    cp -r /home/ubuntu/fh_maison/* "${APP_DIR}/"
    cp /home/ubuntu/fh_maison/.env.example "${APP_DIR}/" 2>/dev/null || true
    # Copy hidden files
    cp /home/ubuntu/fh_maison/.gitignore "${APP_DIR}/" 2>/dev/null || true
    echo -e "${GREEN}Files copied from /home/ubuntu/fh_maison${NC}"
else
    echo -e "${RED}ERROR: Source files not found in /home/ubuntu/fh_maison${NC}"
    exit 1
fi

echo -e "${YELLOW}[3/11] Setting initial permissions...${NC}"
chown -R www-data:www-data "${APP_DIR}"
chmod -R 755 "${APP_DIR}"

echo -e "${YELLOW}[4/11] Creating .env file...${NC}"
if [ ! -f "${APP_DIR}/.env" ]; then
    cp "${APP_DIR}/.env.example" "${APP_DIR}/.env"
    
    # Load database credentials if available
    if [ -f "/root/.fhmaison_db_credentials" ]; then
        source /root/.fhmaison_db_credentials
        sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=mysql/" "${APP_DIR}/.env"
        sed -i "s/^DB_HOST=.*/DB_HOST=127.0.0.1/" "${APP_DIR}/.env"
        sed -i "s/^DB_PORT=.*/DB_PORT=3306/" "${APP_DIR}/.env"
        sed -i "s/^DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE}/" "${APP_DIR}/.env"
        sed -i "s/^DB_USERNAME=.*/DB_USERNAME=${DB_USERNAME}/" "${APP_DIR}/.env"
        sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD}/" "${APP_DIR}/.env"
        sed -i "s/^APP_ENV=.*/APP_ENV=production/" "${APP_DIR}/.env"
        sed -i "s/^APP_DEBUG=.*/APP_DEBUG=false/" "${APP_DIR}/.env"
        sed -i "s|^APP_URL=.*|APP_URL=https://fhmaison.fr|" "${APP_DIR}/.env"
        echo -e "${GREEN}Database credentials configured${NC}"
    fi
    
    chown www-data:www-data "${APP_DIR}/.env"
    chmod 640 "${APP_DIR}/.env"
    echo -e "${GREEN}.env file created${NC}"
else
    echo -e "${GREEN}.env file already exists${NC}"
fi

echo -e "${YELLOW}[5/11] Installing Composer dependencies...${NC}"
cd "${APP_DIR}"
# Run composer as www-data user
sudo -u www-data composer install --no-dev --optimize-autoloader --no-interaction

echo -e "${YELLOW}[6/11] Generating application key...${NC}"
php artisan key:generate --force

echo -e "${YELLOW}[7/11] Installing NPM dependencies...${NC}"
# Create npm cache directory for www-data
mkdir -p /var/www/.npm
chown -R www-data:www-data /var/www/.npm

# Use npm install instead of npm ci (generates package-lock.json)
if [ ! -f "${APP_DIR}/package-lock.json" ]; then
    echo -e "${YELLOW}Generating package-lock.json...${NC}"
    sudo -u www-data npm install --omit=dev
else
    sudo -u www-data npm ci --omit=dev
fi

echo -e "${YELLOW}[8/11] Building assets...${NC}"
sudo -u www-data npm run build

echo -e "${YELLOW}[9/11] Running database migrations...${NC}"
php artisan migrate --force

echo -e "${YELLOW}[10/11] Optimizing application...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo -e "${YELLOW}[11/11] Setting final permissions...${NC}"
# Storage and cache directories need write permissions
chmod -R 775 "${APP_DIR}/storage"
chmod -R 775 "${APP_DIR}/bootstrap/cache"

# Create storage link
php artisan storage:link || true

# Set proper ownership
chown -R www-data:www-data "${APP_DIR}"

# Clean up npm cache
rm -rf /var/www/.npm

# Restart services
systemctl restart php${PHP_VERSION}-fpm
systemctl restart nginx

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Deployment Complete!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Application URL:${NC} http://fhmaison.fr"
echo ""
echo -e "${YELLOW}Next steps:${NC}"
echo "1. Test the application: curl http://fhmaison.fr"
echo "2. Set up SSL: sudo bash ${APP_DIR}/deployment/scripts/04-setup-ssl.sh"
echo "3. Review logs: tail -f ${APP_DIR}/storage/logs/laravel.log"
echo ""