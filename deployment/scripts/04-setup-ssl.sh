#!/bin/bash

###############################################################################
# FH Maison - SSL Certificate Setup with Certbot
# Installs and configures Let's Encrypt SSL certificates
###############################################################################

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration
DOMAIN="fhmaison.fr"
EMAIL="admin@fhmaison.fr"  # Update this with your email
APP_DIR="/var/www/laravel"

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}FH Maison - SSL Certificate Setup${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}Please run as root (use sudo)${NC}"
    exit 1
fi

echo -e "${YELLOW}[1/6] Installing Certbot...${NC}"
apt update
apt install -y certbot python3-certbot-nginx

echo -e "${YELLOW}[2/6] Creating webroot directory for validation...${NC}"
mkdir -p /var/www/certbot

echo -e "${YELLOW}[3/6] Obtaining SSL certificate...${NC}"
echo -e "${YELLOW}This will configure SSL for: ${DOMAIN} and www.${DOMAIN}${NC}"
echo ""

# Stop Nginx temporarily to allow Certbot to bind to port 80
systemctl stop nginx

# Obtain certificate
certbot certonly --standalone \
    --non-interactive \
    --agree-tos \
    --email "$EMAIL" \
    -d "$DOMAIN" \
    -d "www.$DOMAIN"

# Start Nginx again
systemctl start nginx

echo -e "${YELLOW}[4/6] Updating Nginx configuration...${NC}"
# The Nginx configuration already has SSL settings, just reload
nginx -t && systemctl reload nginx

echo -e "${YELLOW}[5/6] Setting up automatic renewal...${NC}"
# Certbot automatically creates a systemd timer for renewal
systemctl enable certbot.timer
systemctl start certbot.timer

# Add a post-renewal hook to reload Nginx
cat > /etc/letsencrypt/renewal-hooks/post/reload-nginx.sh <<'EOF'
#!/bin/bash
systemctl reload nginx
EOF

chmod +x /etc/letsencrypt/renewal-hooks/post/reload-nginx.sh

echo -e "${YELLOW}[6/6] Testing SSL configuration...${NC}"
echo -e "${GREEN}SSL certificate installed successfully!${NC}"
certbot certificates

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}SSL Setup completed!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Certificate details:${NC}"
echo "Domain: ${DOMAIN}"
echo "Certificate path: /etc/letsencrypt/live/${DOMAIN}/fullchain.pem"
echo "Private key path: /etc/letsencrypt/live/${DOMAIN}/privkey.pem"
echo ""
echo -e "${YELLOW}Automatic renewal:${NC}"
echo "Certbot timer status:"
systemctl status certbot.timer --no-pager | head -5
echo ""
echo -e "${YELLOW}Test your SSL:${NC}"
echo "https://www.ssllabs.com/ssltest/analyze.html?d=${DOMAIN}"
echo ""
echo -e "${GREEN}Your site is now available at: https://${DOMAIN}${NC}"
