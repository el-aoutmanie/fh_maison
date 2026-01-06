#!/bin/bash

###############################################################################
# FH Maison - Application Deployment Script
# Deploys Laravel application to production
###############################################################################

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration
APP_NAME="fhmaison"
APP_DIR="/var/www/fhmaison"
DEPLOY_USER="fhmaison"
REPO_URL="YOUR_GIT_REPOSITORY_URL"  # Update this
BRANCH="main"
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

echo -e "${YELLOW}[1/10] Creating application directory...${NC}"
mkdir -p "$APP_DIR"

echo -e "${YELLOW}[2/10] Setting up deployment...${NC}"
if [ -d "$APP_DIR/.git" ]; then
    echo -e "${GREEN}Repository exists, pulling latest changes...${NC}"
    cd "$APP_DIR"
    sudo -u "$DEPLOY_USER" git pull origin "$BRANCH"
else
    echo -e "${GREEN}Cloning repository...${NC}"
    # If using Git, uncomment the following:
    # sudo -u "$DEPLOY_USER" git clone -b "$BRANCH" "$REPO_URL" "$APP_DIR"
    
    # For manual deployment, copy files from uploaded archive
    echo -e "${YELLOW}Manual deployment mode - copy your files to $APP_DIR${NC}"
fi

cd "$APP_DIR"

echo -e "${YELLOW}[3/10] Installing Composer dependencies...${NC}"
sudo -u "$DEPLOY_USER" composer install --no-dev --optimize-autoloader --no-interaction

echo -e "${YELLOW}[4/10] Setting up environment file...${NC}"
if [ ! -f .env ]; then
    cp .env.production .env
    echo -e "${YELLOW}Please update .env file with your database credentials${NC}"
    nano .env
fi

echo -e "${YELLOW}[5/10] Generating application key...${NC}"
php artisan key:generate --force

echo -e "${YELLOW}[6/10] Installing Node dependencies and building assets...${NC}"
sudo -u "$DEPLOY_USER" npm ci
sudo -u "$DEPLOY_USER" npm run build

echo -e "${YELLOW}[7/10] Running database migrations...${NC}"
php artisan migrate --force

echo -e "${YELLOW}[8/10] Optimizing Laravel...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo -e "${YELLOW}[9/10] Creating storage link...${NC}"
php artisan storage:link

echo -e "${YELLOW}[10/10] Setting proper permissions...${NC}"
chown -R "$DEPLOY_USER":www-data "$APP_DIR"
chmod -R 755 "$APP_DIR"
chmod -R 775 "$APP_DIR/storage"
chmod -R 775 "$APP_DIR/bootstrap/cache"

# Set proper permissions for specific directories
find "$APP_DIR/storage" -type f -exec chmod 664 {} \;
find "$APP_DIR/storage" -type d -exec chmod 775 {} \;
find "$APP_DIR/bootstrap/cache" -type f -exec chmod 664 {} \;
find "$APP_DIR/bootstrap/cache" -type d -exec chmod 775 {} \;

echo -e "${YELLOW}Setting up Nginx configuration...${NC}"
if [ -f "$APP_DIR/deployment/nginx/fhmaison.conf" ]; then
    cp "$APP_DIR/deployment/nginx/fhmaison.conf" /etc/nginx/sites-available/fhmaison
    ln -sf /etc/nginx/sites-available/fhmaison /etc/nginx/sites-enabled/fhmaison
    rm -f /etc/nginx/sites-enabled/default
    nginx -t && systemctl reload nginx
fi

echo -e "${YELLOW}Setting up Laravel scheduler...${NC}"
CRON_CMD="* * * * * cd $APP_DIR && php artisan schedule:run >> /dev/null 2>&1"
(crontab -u "$DEPLOY_USER" -l 2>/dev/null | grep -v "artisan schedule:run"; echo "$CRON_CMD") | crontab -u "$DEPLOY_USER" -

echo -e "${YELLOW}Setting up Laravel queue worker...${NC}"
cat > /etc/supervisor/conf.d/fhmaison-worker.conf <<EOF
[program:fhmaison-worker]
process_name=%(program_name)s_%(process_num)02d
command=php $APP_DIR/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=$DEPLOY_USER
numprocs=2
redirect_stderr=true
stdout_logfile=$APP_DIR/storage/logs/worker.log
stopwaitsecs=3600
EOF

supervisorctl reread
supervisorctl update
supervisorctl start fhmaison-worker:*

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Application deployment completed!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Next steps:${NC}"
echo "1. Update .env file with correct credentials"
echo "2. Run database seeders if needed: php artisan db:seed"
echo "3. Set up SSL with Certbot"
echo "4. Test your application"
echo ""
echo -e "${YELLOW}Useful commands:${NC}"
echo "View logs: tail -f $APP_DIR/storage/logs/laravel.log"
echo "Restart workers: supervisorctl restart fhmaison-worker:*"
echo "Clear cache: php artisan cache:clear"
