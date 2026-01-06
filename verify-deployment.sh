#!/bin/bash

###############################################################################
# FH Maison - Pre-Deployment Verification Script
# Run this locally before deploying to verify everything is ready
###############################################################################

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}FH Maison - Deployment Verification${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""

# Check if deployment files exist
check_file() {
    if [ -f "$1" ]; then
        echo -e "${GREEN}✓${NC} Found: $1"
        return 0
    else
        echo -e "${RED}✗${NC} Missing: $1"
        return 1
    fi
}

check_dir() {
    if [ -d "$1" ]; then
        echo -e "${GREEN}✓${NC} Found: $1"
        return 0
    else
        echo -e "${RED}✗${NC} Missing: $1"
        return 1
    fi
}

ERRORS=0

echo -e "${YELLOW}Checking deployment files...${NC}"
echo ""

# Check deployment directory
echo "📁 Deployment Structure:"
check_dir "deployment" || ((ERRORS++))
check_dir "deployment/scripts" || ((ERRORS++))
check_dir "deployment/nginx" || ((ERRORS++))
echo ""

# Check documentation
echo "📚 Documentation:"
check_file "deployment/README.md" || ((ERRORS++))
check_file "deployment/QUICK_START.md" || ((ERRORS++))
check_file "deployment/DEPLOYMENT_GUIDE.md" || ((ERRORS++))
check_file "deployment/TROUBLESHOOTING.md" || ((ERRORS++))
check_file "DEPLOYMENT_SUMMARY.md" || ((ERRORS++))
echo ""

# Check scripts
echo "🔧 Deployment Scripts:"
check_file "deployment/scripts/01-setup-server.sh" || ((ERRORS++))
check_file "deployment/scripts/02-setup-database.sh" || ((ERRORS++))
check_file "deployment/scripts/03-deploy-application.sh" || ((ERRORS++))
check_file "deployment/scripts/04-setup-ssl.sh" || ((ERRORS++))
check_file "deployment/scripts/05-security-hardening.sh" || ((ERRORS++))
check_file "deployment/scripts/06-update-application.sh" || ((ERRORS++))
echo ""

# Check configurations
echo "⚙️  Configuration Files:"
check_file ".env.production" || ((ERRORS++))
check_file "deployment/nginx/fhmaison.conf" || ((ERRORS++))
check_file "docker-compose.yml" || ((ERRORS++))
echo ""

# Check application files
echo "📦 Application Files:"
check_file "composer.json" || ((ERRORS++))
check_file "package.json" || ((ERRORS++))
check_file "artisan" || ((ERRORS++))
check_dir "app" || ((ERRORS++))
check_dir "resources" || ((ERRORS++))
check_dir "database/migrations" || ((ERRORS++))
echo ""

# Check if scripts are executable
echo "🔐 Script Permissions:"
if [ -x "deployment/scripts/01-setup-server.sh" ]; then
    echo -e "${GREEN}✓${NC} Scripts are executable"
else
    echo -e "${YELLOW}⚠${NC}  Scripts need execute permission (run: chmod +x deployment/scripts/*.sh)"
fi
echo ""

# Summary
echo -e "${BLUE}========================================${NC}"
if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}✓ All checks passed!${NC}"
    echo ""
    echo -e "${YELLOW}Next steps:${NC}"
    echo "1. Review DEPLOYMENT_SUMMARY.md"
    echo "2. Upload deployment folder to VPS"
    echo "3. Follow QUICK_START.md or DEPLOYMENT_GUIDE.md"
    echo ""
    echo -e "${BLUE}Upload command:${NC}"
    echo "scp -r deployment root@51.83.47.194:/root/"
    echo ""
else
    echo -e "${RED}✗ Found $ERRORS issue(s)${NC}"
    echo "Please ensure all files are present before deployment."
fi
echo -e "${BLUE}========================================${NC}"
