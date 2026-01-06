# FH Maison - Quick Deployment Reference

## 🎯 What Was Fixed

### ✅ Localization Issues
- **Enabled French locale** in `config/laravellocalization.php`
- **Arabic locale** was already enabled (RTL support)
- **English locale** was already enabled
- All three locales now work: `/en`, `/fr`, `/ar`

### ✅ Path Consistency
All files now use `/var/www/fhmaison`:
- Nginx configuration
- All deployment scripts (01-06)
- Security hardening script
- Update script

## 📋 Fresh Deployment Checklist

### 1. Reset Server & Clone
```bash
# On VPS
cd /var/www
sudo git clone <your-repo> fhmaison
sudo chown -R www-data:www-data fhmaison
cd fhmaison
```

### 2. Run Scripts (in order)
```bash
cd deployment/scripts
sudo bash 01-setup-server.sh      # Install PHP, Nginx, MySQL, Node
sudo bash 02-setup-database.sh    # Create database and user
sudo bash 03-deploy-application.sh # Install dependencies, migrate DB
sudo bash 04-setup-ssl.sh         # Setup Let's Encrypt SSL
sudo bash 05-security-hardening.sh # Apply security settings
```

### 3. Verify Deployment
```bash
sudo bash verify-deployment.sh
```

### 4. Create Admin User
```bash
cd /var/www/fhmaison
sudo -u www-data php artisan make:admin
```

### 5. Test URLs
- https://fhmaison.fr/ (redirects to /en)
- https://fhmaison.fr/en (English)
- https://fhmaison.fr/fr (French)
- https://fhmaison.fr/ar (Arabic - RTL)

## 🔧 Database Credentials

Location: `/root/.fhmaison_db_credentials`

```
Database: fhmaison_db
User: fhmaison_user
Password: YEYuj3zd
```

## 📁 Important Paths

| Item | Path |
|------|------|
| Application | `/var/www/fhmaison` |
| Public files | `/var/www/fhmaison/public` |
| Storage | `/var/www/fhmaison/storage` |
| Environment | `/var/www/fhmaison/.env` |
| Nginx config | `/etc/nginx/sites-available/fhmaison.conf` |
| SSL certs | `/etc/letsencrypt/live/fhmaison.fr/` |
| Laravel logs | `/var/www/fhmaison/storage/logs/laravel.log` |
| Nginx logs | `/var/log/nginx/fhmaison_*.log` |

## 🌐 Supported Locales

| Locale | URL | Direction | Native Name |
|--------|-----|-----------|-------------|
| English | `/en` | LTR | English |
| French | `/fr` | LTR | Français |
| Arabic | `/ar` | RTL | العربية |

## 🛠️ Common Commands

### View Logs
```bash
# Laravel logs
sudo tail -f /var/www/fhmaison/storage/logs/laravel.log

# Nginx error logs
sudo tail -f /var/log/nginx/fhmaison_error.log

# Nginx access logs
sudo tail -f /var/log/nginx/fhmaison_access.log
```

### Clear Cache
```bash
cd /var/www/fhmaison
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan route:clear
```

### Rebuild Assets
```bash
cd /var/www/fhmaison
sudo -u www-data npm install
sudo -u www-data npm run build
```

### Update Application
```bash
cd /var/www/fhmaison/deployment/scripts
sudo bash 06-update-application.sh
```

### Fix Permissions
```bash
sudo chown -R www-data:www-data /var/www/fhmaison
sudo chmod -R 755 /var/www/fhmaison
sudo chmod -R 775 /var/www/fhmaison/storage
sudo chmod -R 775 /var/www/fhmaison/bootstrap/cache
```

### Restart Services
```bash
sudo systemctl restart nginx
sudo systemctl restart php8.3-fpm
sudo systemctl restart mysql
```

## 🔍 Verification Tests

### Test Localization
```bash
# Test all locale URLs
curl -I https://fhmaison.fr/
curl -I https://fhmaison.fr/en
curl -I https://fhmaison.fr/fr
curl -I https://fhmaison.fr/ar

# Should all return 200 or 302
```

### Check Configuration
```bash
# Verify paths in Nginx config
grep "root " /etc/nginx/sites-available/fhmaison.conf

# Verify enabled locales
grep "^\s*'[a-z]*'" /var/www/fhmaison/config/laravellocalization.php | grep -v "//"

# Check .env settings
grep "^APP_" /var/www/fhmaison/.env
grep "^DB_" /var/www/fhmaison/.env
```

## 🚨 Troubleshooting

### Issue: 404 on locale URLs
**Solution:** Locales are now enabled. Clear cache:
```bash
cd /var/www/fhmaison
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
```

### Issue: Static assets not loading
**Solution:**
```bash
cd /var/www/fhmaison
sudo -u www-data php artisan storage:link
sudo -u www-data npm run build
```

### Issue: Permission denied errors
**Solution:**
```bash
sudo chown -R www-data:www-data /var/www/fhmaison
sudo chmod -R 775 /var/www/fhmaison/storage
sudo chmod -R 775 /var/www/fhmaison/bootstrap/cache
```

### Issue: Database connection failed
**Solution:**
```bash
# Check credentials
cat /root/.fhmaison_db_credentials

# Update .env
sudo nano /var/www/fhmaison/.env

# Test connection
mysql -u fhmaison_user -pYEYuj3zd fhmaison_db -e "SELECT 1;"
```

## 📚 Additional Documentation

- `LOCALIZATION_AND_PATH_FIXES.md` - Detailed changes
- `DEPLOYMENT_GUIDE.md` - Complete deployment guide
- `TROUBLESHOOTING.md` - Comprehensive troubleshooting
- `FRESH_INSTALL.md` - Fresh installation guide

## ✅ Deployment Verification Checklist

- [ ] All scripts run without errors
- [ ] Nginx is running: `systemctl status nginx`
- [ ] PHP-FPM is running: `systemctl status php8.3-fpm`
- [ ] MySQL is running: `systemctl status mysql`
- [ ] SSL certificate installed: `ls /etc/letsencrypt/live/fhmaison.fr/`
- [ ] Root URL redirects correctly
- [ ] English site (`/en`) loads
- [ ] French site (`/fr`) loads
- [ ] Arabic site (`/ar`) loads
- [ ] Admin panel accessible (`/en/admin/dashboard`)
- [ ] Can login as admin
- [ ] Products display correctly
- [ ] Cart functionality works
- [ ] Checkout process works
- [ ] File uploads work

## 🎉 Success!

If all checks pass, your FH Maison application is successfully deployed!

Visit: https://fhmaison.fr/

---
*Last updated: $(date +%Y-%m-%d)*
