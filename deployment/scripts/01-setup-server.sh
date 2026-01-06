#!/bin/bash

###############################################################################
# FH Maison - VPS Server Setup Script
# This script prepares Ubuntu 22.04 LTS for PHP 8.3 Laravel application
# Domain: fhmaison.fr
# Server IP: 51.83.47.194
###############################################################################

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
APP_NAME="fhmaison"
DOMAIN="fhmaison.fr"
APP_DIR="/var/www/fhmaison"
DEPLOY_USER="fhmaison"
DB_NAME="fhmaison_db"
DB_USER="fhmaison_user"
PHP_VERSION="8.3"

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}FH Maison - VPS Server Setup${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}Please run as root (use sudo)${NC}"
    exit 1
fi

echo -e "${YELLOW}[1/12] Updating system packages...${NC}"
apt update && apt upgrade -y

echo -e "${YELLOW}[2/12] Installing essential packages...${NC}"
apt install -y software-properties-common curl wget git unzip supervisor ufw fail2ban

echo -e "${YELLOW}[3/12] Adding PHP 8.3 repository...${NC}"
add-apt-repository ppa:ondrej/php -y
apt update

echo -e "${YELLOW}[4/12] Installing PHP 8.3 and extensions...${NC}"
apt install -y \
    php${PHP_VERSION}-fpm \
    php${PHP_VERSION}-cli \
    php${PHP_VERSION}-common \
    php${PHP_VERSION}-mysql \
    php${PHP_VERSION}-zip \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-xml \
    php${PHP_VERSION}-bcmath \
    php${PHP_VERSION}-intl \
    php${PHP_VERSION}-redis \
    php${PHP_VERSION}-imagick \
    php${PHP_VERSION}-opcache

echo -e "${YELLOW}[5/12] Installing Nginx...${NC}"
apt install -y nginx

echo -e "${YELLOW}[6/12] Installing MySQL 8.0...${NC}"
apt install -y mysql-server

echo -e "${YELLOW}[7/12] Installing Redis...${NC}"
apt install -y redis-server
systemctl enable redis-server
systemctl start redis-server

echo -e "${YELLOW}[8/12] Installing Composer...${NC}"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

echo -e "${YELLOW}[9/12] Installing Node.js 20 and npm...${NC}"
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

echo -e "${YELLOW}[10/12] Creating deployment user...${NC}"
if id "$DEPLOY_USER" &>/dev/null; then
    echo -e "${GREEN}User $DEPLOY_USER already exists${NC}"
else
    useradd -m -s /bin/bash "$DEPLOY_USER"
    usermod -aG www-data "$DEPLOY_USER"
    echo -e "${GREEN}User $DEPLOY_USER created${NC}"
fi

echo -e "${YELLOW}[11/12] Configuring PHP-FPM...${NC}"
# Optimize PHP-FPM configuration
sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php/${PHP_VERSION}/fpm/php.ini
sed -i "s/upload_max_filesize = .*/upload_max_filesize = 100M/" /etc/php/${PHP_VERSION}/fpm/php.ini
sed -i "s/post_max_size = .*/post_max_size = 100M/" /etc/php/${PHP_VERSION}/fpm/php.ini
sed -i "s/max_execution_time = .*/max_execution_time = 300/" /etc/php/${PHP_VERSION}/fpm/php.ini
sed -i "s/memory_limit = .*/memory_limit = 512M/" /etc/php/${PHP_VERSION}/fpm/php.ini

# Enable OPcache for production
cat > /etc/php/${PHP_VERSION}/mods-available/opcache.ini <<EOF
; OPcache configuration for production
zend_extension=opcache.so
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.validate_timestamps=0
opcache.fast_shutdown=1
EOF

systemctl restart php${PHP_VERSION}-fpm

echo -e "${YELLOW}[12/12] Configuring firewall...${NC}"
ufw --force enable
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow 'Nginx Full'
ufw allow 80/tcp
ufw allow 443/tcp

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Server setup completed!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Next steps:${NC}"
echo "1. Secure MySQL: sudo mysql_secure_installation"
echo "2. Create database and user"
echo "3. Configure SSH keys for $DEPLOY_USER"
echo "4. Run the application deployment script"
echo "5. Set up SSL with Certbot"
echo ""
echo -e "${YELLOW}Service status:${NC}"
systemctl status php${PHP_VERSION}-fpm --no-pager | head -3
systemctl status nginx --no-pager | head -3
systemctl status mysql --no-pager | head -3
systemctl status redis-server --no-pager | head -3
