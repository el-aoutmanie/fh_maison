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

echo -e "${YELLOW}[1/12] Creating application directory...${NC}"
mkdir -p "${APP_DIR}"

echo -e "${YELLOW}[2/12] Checking application files...${NC}"
if [ ! -f "${APP_DIR}/composer.json" ]; then
    echo -e "${RED}ERROR: Application not found in ${APP_DIR}${NC}"
    echo -e "${YELLOW}Please clone your repository first:${NC}"
    echo "  cd /var/www"
    echo "  sudo git clone YOUR_REPO_URL fhmaison"
    echo "  sudo chown -R www-data:www-data fhmaison"
    exit 1
fi
echo -e "${GREEN}Application files found in ${APP_DIR}${NC}"

echo -e "${YELLOW}[3/12] Setting initial permissions...${NC}"
chown -R www-data:www-data "${APP_DIR}"
chmod -R 755 "${APP_DIR}"

echo -e "${YELLOW}[4/12] Configuring Nginx...${NC}"
# Use the config from deployment folder or create default
if [ -f "${APP_DIR}/deployment/nginx/fhmaison.conf" ]; then
    cp "${APP_DIR}/deployment/nginx/fhmaison.conf" /etc/nginx/sites-available/fhmaison.fr
    echo -e "${GREEN}Nginx configuration copied from deployment folder${NC}"
else
    cat > /etc/nginx/sites-available/fhmaison.fr <<'NGINXEOF'
server {
    listen 80;
    listen [::]:80;
    server_name fhmaison.fr www.fhmaison.fr;
    
    root /var/www/fhmaison/public;
    index index.php index.html;
    
    charset utf-8;
    
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
    
    location /storage {
        alias /var/www/fhmaison/storage/app/public;
    }
    
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
    
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt { access_log off; log_not_found off; }
    
    error_page 404 /index.php;
    
    access_log /var/log/nginx/fhmaison-access.log;
    error_log /var/log/nginx/fhmaison-error.log;
}
NGINXEOF
    echo -e "${GREEN}Default nginx configuration created${NC}"
fi

# Remove default site and enable fhmaison
rm -f /etc/nginx/sites-enabled/default
ln -sf /etc/nginx/sites-available/fhmaison.fr /etc/nginx/sites-enabled/

# Test nginx configuration
nginx -t
if [ $? -ne 0 ]; then
    echo -e "${RED}Nginx configuration test failed!${NC}"
    exit 1
fi
systemctl reload nginx
echo -e "${GREEN}Nginx configured and reloaded${NC}"

echo -e "${YELLOW}[5/12] Creating .env file...${NC}"
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

echo -e "${YELLOW}[6/12] Installing Composer dependencies...${NC}"
cd "${APP_DIR}"
# Run composer as www-data user
sudo -u www-data composer install --no-dev --optimize-autoloader --no-interaction

echo -e "${YELLOW}[7/12] Generating application key...${NC}"
php artisan key:generate --force

echo -e "${YELLOW}[8/12] Installing NPM dependencies (including devDependencies for build)...${NC}"
# Create npm cache directory for www-data
mkdir -p /var/www/.npm
chown -R www-data:www-data /var/www/.npm

# Install ALL dependencies (including devDependencies) to build assets
if [ ! -f "${APP_DIR}/package-lock.json" ]; then
    echo -e "${YELLOW}Generating package-lock.json...${NC}"
    sudo -u www-data npm install
else
    sudo -u www-data npm ci
fi

echo -e "${YELLOW}[9/12] Building assets...${NC}"
sudo -u www-data npm run build

echo -e "${YELLOW}[10/12] Cleaning up dev dependencies...${NC}"
# Remove devDependencies after build
sudo -u www-data npm prune --omit=dev

echo -e "${YELLOW}[11/12] Running database migrations...${NC}"
php artisan migrate --force

echo -e "${YELLOW}[12/12] Optimizing application...${NC}"
php artisan config:cache
php artisan route:cache
php artisan optimize

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