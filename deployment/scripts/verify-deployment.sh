#!/bin/bash

###############################################################################
# FH Maison - Post-Deployment Verification Script
# Verify that all localization and path configurations are correct
###############################################################################

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

APP_DIR="/var/www/fhmaison"
DOMAIN="fhmaison.fr"

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}FH Maison - Verification Script${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Check if application directory exists
echo -e "${BLUE}1. Checking application directory...${NC}"
if [ -d "$APP_DIR" ]; then
    echo -e "${GREEN}✓ Application directory exists: $APP_DIR${NC}"
else
    echo -e "${RED}✗ Application directory not found: $APP_DIR${NC}"
    exit 1
fi

# Check nginx configuration
echo -e "\n${BLUE}2. Checking Nginx configuration...${NC}"
NGINX_CONFIG="/etc/nginx/sites-available/fhmaison.conf"
if [ -f "$NGINX_CONFIG" ]; then
    if grep -q "/var/www/fhmaison/public" "$NGINX_CONFIG"; then
        echo -e "${GREEN}✓ Nginx root path is correct${NC}"
    else
        echo -e "${RED}✗ Nginx root path is incorrect${NC}"
        grep "root " "$NGINX_CONFIG" | head -1
    fi
    
    if grep -q "/var/www/fhmaison/storage" "$NGINX_CONFIG"; then
        echo -e "${GREEN}✓ Nginx storage path is correct${NC}"
    else
        echo -e "${YELLOW}⚠ Nginx storage path may be incorrect${NC}"
    fi
else
    echo -e "${YELLOW}⚠ Nginx config not found (may not be deployed yet)${NC}"
fi

# Check Laravel localization config
echo -e "\n${BLUE}3. Checking Laravel localization configuration...${NC}"
LOCALE_CONFIG="$APP_DIR/config/laravellocalization.php"
if [ -f "$LOCALE_CONFIG" ]; then
    # Check for enabled locales (lines without //)
    EN_ENABLED=$(grep -E "^\s*'en'" "$LOCALE_CONFIG" | wc -l)
    FR_ENABLED=$(grep -E "^\s*'fr'" "$LOCALE_CONFIG" | wc -l)
    AR_ENABLED=$(grep -E "^\s*'ar'" "$LOCALE_CONFIG" | wc -l)
    
    if [ $EN_ENABLED -gt 0 ]; then
        echo -e "${GREEN}✓ English locale (en) is enabled${NC}"
    else
        echo -e "${RED}✗ English locale (en) is not enabled${NC}"
    fi
    
    if [ $FR_ENABLED -gt 0 ]; then
        echo -e "${GREEN}✓ French locale (fr) is enabled${NC}"
    else
        echo -e "${RED}✗ French locale (fr) is not enabled${NC}"
    fi
    
    if [ $AR_ENABLED -gt 0 ]; then
        echo -e "${GREEN}✓ Arabic locale (ar) is enabled${NC}"
    else
        echo -e "${RED}✗ Arabic locale (ar) is not enabled${NC}"
    fi
else
    echo -e "${YELLOW}⚠ Localization config not found${NC}"
fi

# Check translation files
echo -e "\n${BLUE}4. Checking translation files...${NC}"
for lang in en fr ar; do
    TRANS_FILE="$APP_DIR/lang/$lang.json"
    if [ -f "$TRANS_FILE" ]; then
        echo -e "${GREEN}✓ Translation file exists: $lang.json${NC}"
    else
        echo -e "${YELLOW}⚠ Translation file missing: $lang.json${NC}"
    fi
done

# Check .env file
echo -e "\n${BLUE}5. Checking environment configuration...${NC}"
ENV_FILE="$APP_DIR/.env"
if [ -f "$ENV_FILE" ]; then
    echo -e "${GREEN}✓ .env file exists${NC}"
    
    # Check important settings
    APP_URL=$(grep "^APP_URL=" "$ENV_FILE" | cut -d'=' -f2)
    APP_LOCALE=$(grep "^APP_LOCALE=" "$ENV_FILE" | cut -d'=' -f2)
    DB_DATABASE=$(grep "^DB_DATABASE=" "$ENV_FILE" | cut -d'=' -f2)
    
    echo -e "  APP_URL: ${BLUE}$APP_URL${NC}"
    echo -e "  APP_LOCALE: ${BLUE}$APP_LOCALE${NC}"
    echo -e "  DB_DATABASE: ${BLUE}$DB_DATABASE${NC}"
else
    echo -e "${YELLOW}⚠ .env file not found${NC}"
fi

# Check file permissions
echo -e "\n${BLUE}6. Checking file permissions...${NC}"
if [ -d "$APP_DIR" ]; then
    OWNER=$(stat -c '%U:%G' "$APP_DIR")
    echo -e "  Application owner: ${BLUE}$OWNER${NC}"
    
    if [ -d "$APP_DIR/storage" ]; then
        STORAGE_PERMS=$(stat -c '%a' "$APP_DIR/storage")
        echo -e "  Storage permissions: ${BLUE}$STORAGE_PERMS${NC}"
        
        if [ "$STORAGE_PERMS" = "775" ] || [ "$STORAGE_PERMS" = "777" ]; then
            echo -e "${GREEN}✓ Storage permissions are correct${NC}"
        else
            echo -e "${YELLOW}⚠ Storage permissions should be 775${NC}"
        fi
    fi
fi

# Check SSL certificate
echo -e "\n${BLUE}7. Checking SSL certificate...${NC}"
if [ -d "/etc/letsencrypt/live/$DOMAIN" ]; then
    echo -e "${GREEN}✓ SSL certificate directory exists${NC}"
    
    CERT_FILE="/etc/letsencrypt/live/$DOMAIN/fullchain.pem"
    if [ -f "$CERT_FILE" ]; then
        EXPIRY=$(openssl x509 -enddate -noout -in "$CERT_FILE" | cut -d'=' -f2)
        echo -e "  Certificate expires: ${BLUE}$EXPIRY${NC}"
    fi
else
    echo -e "${YELLOW}⚠ SSL certificate not found (run 04-setup-ssl.sh)${NC}"
fi

# Test URLs (if server is running)
echo -e "\n${BLUE}8. Testing application URLs...${NC}"
if command -v curl &> /dev/null; then
    # Test root URL
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" -k "https://$DOMAIN/" 2>/dev/null || echo "000")
    if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "302" ]; then
        echo -e "${GREEN}✓ Root URL ($DOMAIN/) responds: $HTTP_CODE${NC}"
    else
        echo -e "${YELLOW}⚠ Root URL returned: $HTTP_CODE (may not be deployed yet)${NC}"
    fi
    
    # Test locale URLs
    for locale in en fr ar; do
        HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" -k "https://$DOMAIN/$locale" 2>/dev/null || echo "000")
        if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "302" ]; then
            echo -e "${GREEN}✓ Locale URL ($DOMAIN/$locale) responds: $HTTP_CODE${NC}"
        else
            echo -e "${YELLOW}⚠ Locale URL ($DOMAIN/$locale) returned: $HTTP_CODE${NC}"
        fi
    done
else
    echo -e "${YELLOW}⚠ curl not installed, skipping URL tests${NC}"
fi

# Check services
echo -e "\n${BLUE}9. Checking services status...${NC}"
if systemctl is-active --quiet nginx; then
    echo -e "${GREEN}✓ Nginx is running${NC}"
else
    echo -e "${RED}✗ Nginx is not running${NC}"
fi

if systemctl is-active --quiet php8.3-fpm; then
    echo -e "${GREEN}✓ PHP-FPM is running${NC}"
else
    echo -e "${RED}✗ PHP-FPM is not running${NC}"
fi

if systemctl is-active --quiet mysql; then
    echo -e "${GREEN}✓ MySQL is running${NC}"
else
    echo -e "${RED}✗ MySQL is not running${NC}"
fi

# Summary
echo -e "\n${GREEN}========================================${NC}"
echo -e "${GREEN}Verification Complete!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${BLUE}Next steps:${NC}"
echo "1. If locales are missing, check config/laravellocalization.php"
echo "2. If SSL not configured, run: sudo bash 04-setup-ssl.sh"
echo "3. Create admin user: cd $APP_DIR && sudo -u www-data php artisan make:admin"
echo "4. Clear cache: cd $APP_DIR && sudo -u www-data php artisan cache:clear"
echo "5. Test application in browser: https://$DOMAIN/"
echo ""
