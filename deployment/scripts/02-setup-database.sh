#!/bin/bash

###############################################################################
# FH Maison - Database Setup Script
# Creates database and user with proper permissions
###############################################################################

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration
DB_NAME="fhmaison_db"
DB_USER="fhmaison_user"

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}FH Maison - Database Setup${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}Please run as root (use sudo)${NC}"
    exit 1
fi

# Generate a secure password
DB_PASSWORD=$(openssl rand -base64 32)

echo -e "${YELLOW}[1/3] Creating database...${NC}"
mysql -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo -e "${GREEN}Database created: ${DB_NAME}${NC}"

echo -e "${YELLOW}[2/3] Creating database user...${NC}"
mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';"
mysql -e "GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"
echo -e "${GREEN}Database user created: ${DB_USER}${NC}"

echo -e "${YELLOW}[3/3] Optimizing MySQL configuration...${NC}"

# Create MySQL configuration file for optimization
cat > /etc/mysql/conf.d/fhmaison.cnf <<EOF
[mysqld]
# Performance tuning
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT
max_connections = 200

# Query cache (disabled in MySQL 8.0+)
# query_cache_type = 1
# query_cache_size = 32M

# Logging
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow-query.log
long_query_time = 2

# Character set
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci
EOF

systemctl restart mysql

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Database setup completed!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Database credentials:${NC}"
echo "Database: ${DB_NAME}"
echo "Username: ${DB_USER}"
echo "Password: ${DB_PASSWORD}"
echo ""
echo -e "${RED}IMPORTANT: Save these credentials securely!${NC}"
echo -e "${YELLOW}Add them to your .env file:${NC}"
echo ""
echo "DB_DATABASE=${DB_NAME}"
echo "DB_USERNAME=${DB_USER}"
echo "DB_PASSWORD=${DB_PASSWORD}"
echo ""

# Save credentials to a file
CREDS_FILE="/root/.fhmaison_db_credentials"
cat > "$CREDS_FILE" <<EOF
# FH Maison Database Credentials
# Generated: $(date)
DB_DATABASE=${DB_NAME}
DB_USERNAME=${DB_USER}
DB_PASSWORD=${DB_PASSWORD}
EOF

chmod 600 "$CREDS_FILE"
echo -e "${GREEN}Credentials saved to: ${CREDS_FILE}${NC}"
