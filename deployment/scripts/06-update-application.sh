#!/bin/bash

###############################################################################
# FH Maison - Update/Deployment Script
# Use this for updating the application after initial deployment
###############################################################################

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Configuration
APP_DIR="/var/www/fhmaison"
DEPLOY_USER="fhmaison"
BACKUP_DIR="/var/backups/fhmaison"
PHP_VERSION="8.3"

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}FH Maison - Application Update${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}Please run as root (use sudo)${NC}"
    exit 1
fi

# Create backup
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_PATH="${BACKUP_DIR}/${TIMESTAMP}"

echo -e "${YELLOW}[1/12] Creating backup...${NC}"
mkdir -p "$BACKUP_PATH"
cd "$APP_DIR"

# Backup database
echo -e "${BLUE}Backing up database...${NC}"
source .env
mysqldump -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" > "${BACKUP_PATH}/database.sql"

# Backup files
echo -e "${BLUE}Backing up files...${NC}"
tar -czf "${BACKUP_PATH}/files.tar.gz" \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    -C "$APP_DIR" .

echo -e "${GREEN}Backup created at: ${BACKUP_PATH}${NC}"

echo -e "${YELLOW}[2/12] Enabling maintenance mode...${NC}"
php artisan down --retry=60 --secret="fhmaison-maintenance-bypass"

echo -e "${YELLOW}[3/12] Pulling latest changes...${NC}"
sudo -u "$DEPLOY_USER" git pull origin main || echo "Git pull skipped (manual deployment)"

echo -e "${YELLOW}[4/12] Installing/updating Composer dependencies...${NC}"
sudo -u "$DEPLOY_USER" composer install --no-dev --optimize-autoloader --no-interaction

echo -e "${YELLOW}[5/12] Installing/updating Node dependencies...${NC}"
sudo -u "$DEPLOY_USER" npm ci

echo -e "${YELLOW}[6/12] Building frontend assets...${NC}"
sudo -u "$DEPLOY_USER" npm run build

echo -e "${YELLOW}[7/12] Running database migrations...${NC}"
php artisan migrate --force

echo -e "${YELLOW}[8/12] Clearing caches...${NC}"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo -e "${YELLOW}[9/12] Optimizing application...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo -e "${YELLOW}[10/12] Optimizing Composer autoloader...${NC}"
composer dump-autoload --optimize

echo -e "${YELLOW}[11/12] Restarting services...${NC}"
supervisorctl restart fhmaison-worker:*
systemctl reload php${PHP_VERSION}-fpm
systemctl reload nginx

echo -e "${YELLOW}[12/12] Disabling maintenance mode...${NC}"
php artisan up

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Application update completed!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Backup location: ${BACKUP_PATH}${NC}"
echo ""
echo -e "${BLUE}To restore from backup if needed:${NC}"
echo "1. Database: mysql -u\$DB_USERNAME -p\$DB_PASSWORD \$DB_DATABASE < ${BACKUP_PATH}/database.sql"
echo "2. Files: tar -xzf ${BACKUP_PATH}/files.tar.gz -C ${APP_DIR}"
echo ""
echo -e "${GREEN}Application is now live!${NC}"
echo "Visit: https://fhmaison.fr"
