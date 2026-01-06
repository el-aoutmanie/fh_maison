# FH Maison - Production Deployment Guide

## 🚀 Complete VPS Deployment Guide for fhmaison.fr

This guide provides step-by-step instructions to deploy the FH Maison Laravel application on an Ubuntu 22.04 LTS VPS with PHP 8.3, Nginx, MySQL, and Redis.

---

## 📋 Table of Contents

1. [Prerequisites](#prerequisites)
2. [Server Information](#server-information)
3. [DNS Configuration](#dns-configuration)
4. [Deployment Steps](#deployment-steps)
5. [SSL Certificate Setup](#ssl-certificate-setup)
6. [Post-Deployment Tasks](#post-deployment-tasks)
7. [Maintenance & Updates](#maintenance--updates)
8. [Troubleshooting](#troubleshooting)
9. [Security Best Practices](#security-best-practices)

---

## Prerequisites

### Required Access
- SSH access to VPS (IP: 51.83.47.194)
- Root or sudo privileges
- Domain DNS access (OVH)

### Local Requirements
- Git installed
- SSH key pair generated
- Code editor (VS Code recommended)

---

## Server Information

| Component | Details |
|-----------|---------|
| **Domain** | fhmaison.fr |
| **Server IP** | 51.83.47.194 |
| **OS** | Ubuntu 22.04 LTS |
| **Web Server** | Nginx |
| **PHP Version** | 8.3 |
| **Database** | MySQL 8.0 |
| **Cache** | Redis |
| **SSL** | Let's Encrypt (Certbot) |

---

## DNS Configuration

Your OVH DNS is already configured:

```
fhmaison.fr.          A      51.83.47.194
www.fhmaison.fr.      CNAME  fhmaison.fr.
fhmaison.fr.          NS     dns110.ovh.net.
fhmaison.fr.          NS     ns110.ovh.net.
```

**DNS Propagation:** Allow 24-48 hours for global propagation. Check status at: https://dnschecker.org

---

## Deployment Steps

### Step 1: Initial Server Setup

Connect to your VPS:

```bash
ssh root@51.83.47.194
```

Run the server setup script:

```bash
# Upload the deployment folder to your server
# Then run:
cd /path/to/deployment/scripts
chmod +x *.sh
./01-setup-server.sh
```

This script will:
- ✅ Update system packages
- ✅ Install PHP 8.3 and required extensions
- ✅ Install Nginx web server
- ✅ Install MySQL 8.0
- ✅ Install Redis
- ✅ Install Composer and Node.js 20
- ✅ Create deployment user
- ✅ Configure firewall (UFW)

**Duration:** ~10-15 minutes

---

### Step 2: Secure MySQL

```bash
sudo mysql_secure_installation
```

Answer the prompts:
- Set root password: **YES** (use a strong password)
- Remove anonymous users: **YES**
- Disallow root login remotely: **YES**
- Remove test database: **YES**
- Reload privilege tables: **YES**

Then run the database setup script:

```bash
./02-setup-database.sh
```

This will:
- ✅ Create database: `fhmaison_db`
- ✅ Create database user: `fhmaison_user`
- ✅ Generate secure password
- ✅ Optimize MySQL configuration
- ✅ Save credentials to `/root/.fhmaison_db_credentials`

**⚠️ IMPORTANT:** Save the database credentials shown at the end!

---

### Step 3: Set Up SSH Keys

On your **local machine**, generate SSH keys if you haven't already:

```bash
ssh-keygen -t ed25519 -C "your_email@example.com"
```

Copy the public key to the server:

```bash
ssh-copy-id fhmaison@51.83.47.194
```

Test the connection:

```bash
ssh fhmaison@51.83.47.194
```

---

### Step 4: Deploy Application

Upload your application code to the server. You have two options:

#### Option A: Using Git (Recommended)

```bash
# On server, as root or with sudo
cd /var/www
sudo -u fhmaison git clone YOUR_REPOSITORY_URL fhmaison
```

#### Option B: Manual Upload

```bash
# On your local machine
cd /path/to/nouni_laravel-master
tar -czf fhmaison.tar.gz \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='.git' \
    --exclude='storage/logs/*' \
    .

# Upload to server
scp fhmaison.tar.gz fhmaison@51.83.47.194:/tmp/

# On server
sudo mkdir -p /var/www/fhmaison
sudo tar -xzf /tmp/fhmaison.tar.gz -C /var/www/fhmaison
sudo chown -R fhmaison:www-data /var/www/fhmaison
```

Now run the deployment script:

```bash
cd /var/www/fhmaison/deployment/scripts
sudo ./03-deploy-application.sh
```

This will:
- ✅ Install Composer dependencies
- ✅ Copy and configure .env file
- ✅ Generate application key
- ✅ Build frontend assets with Vite
- ✅ Run database migrations
- ✅ Optimize Laravel caches
- ✅ Set correct file permissions
- ✅ Configure Nginx
- ✅ Set up Laravel scheduler (cron)
- ✅ Set up Laravel queue workers (Supervisor)

**During this step, you'll be prompted to edit the .env file. Add your database credentials:**

```env
DB_DATABASE=fhmaison_db
DB_USERNAME=fhmaison_user
DB_PASSWORD=<password_from_step_2>
```

---

### Step 5: Configure SSL with Let's Encrypt

Before running the SSL setup, ensure:
1. DNS records are propagated (check with `dig fhmaison.fr`)
2. Ports 80 and 443 are open in firewall
3. Nginx is running

Update the email in the script:

```bash
nano /var/www/fhmaison/deployment/scripts/04-setup-ssl.sh
# Change: EMAIL="admin@fhmaison.fr" to your actual email
```

Run the SSL setup:

```bash
sudo ./04-setup-ssl.sh
```

This will:
- ✅ Install Certbot
- ✅ Obtain SSL certificates for fhmaison.fr and www.fhmaison.fr
- ✅ Configure Nginx for HTTPS
- ✅ Set up automatic certificate renewal
- ✅ Enable HSTS and security headers

**Your site will now be accessible at:** https://fhmaison.fr

---

### Step 6: Security Hardening

Run the security hardening script:

```bash
sudo ./05-security-hardening.sh
```

This will:
- ✅ Configure Fail2Ban (intrusion prevention)
- ✅ Harden SSH (disable root login, require keys)
- ✅ Enable automatic security updates
- ✅ Secure MySQL (local access only)
- ✅ Harden kernel parameters
- ✅ Configure log rotation
- ✅ Create security monitoring script

**⚠️ CRITICAL:** After this step:
1. Test SSH key access from a NEW terminal
2. Do NOT close your current root session until verified
3. Password authentication will be disabled

---

## Post-Deployment Tasks

### 1. Seed the Database (Optional)

If you need to populate with demo data:

```bash
cd /var/www/fhmaison
php artisan db:seed
```

### 2. Create Admin User

```bash
cd /var/www/fhmaison
php artisan tinker

# In tinker:
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@fhmaison.fr';
$user->password = bcrypt('your-secure-password');
$user->save();
$user->assignRole('admin');
exit
```

### 3. Configure Email (SMTP)

Update `.env` with your SMTP settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your-email@fhmaison.fr
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@fhmaison.fr"
MAIL_FROM_NAME="FH Maison"
```

After updating:

```bash
php artisan config:cache
```

### 4. Configure Stripe (Payment Gateway)

Update `.env` with your Stripe keys:

```env
STRIPE_PUBLIC_KEY=pk_live_your_key_here
STRIPE_SECRET_KEY=sk_live_your_key_here
```

After updating:

```bash
php artisan config:cache
```

### 5. Set Up Monitoring (Optional but Recommended)

Install Laravel Telescope for debugging (development only):

```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

Access at: https://fhmaison.fr/telescope

---

## Maintenance & Updates

### Updating the Application

Use the update script for deployments:

```bash
cd /var/www/fhmaison/deployment/scripts
sudo ./06-update-application.sh
```

This script:
- ✅ Creates automatic backup
- ✅ Enables maintenance mode
- ✅ Pulls latest changes
- ✅ Updates dependencies
- ✅ Runs migrations
- ✅ Clears and rebuilds caches
- ✅ Restarts services
- ✅ Disables maintenance mode

### Manual Backup

```bash
# Backup database
mysqldump -u fhmaison_user -p fhmaison_db > backup_$(date +%Y%m%d).sql

# Backup files
tar -czf backup_$(date +%Y%m%d).tar.gz -C /var/www/fhmaison .
```

### Viewing Logs

```bash
# Application logs
tail -f /var/www/fhmaison/storage/logs/laravel.log

# Nginx access logs
tail -f /var/log/nginx/fhmaison_access.log

# Nginx error logs
tail -f /var/log/nginx/fhmaison_error.log

# PHP-FPM logs
tail -f /var/log/php8.3-fpm.log

# Queue worker logs
tail -f /var/www/fhmaison/storage/logs/worker.log
```

### Managing Services

```bash
# PHP-FPM
sudo systemctl restart php8.3-fpm
sudo systemctl status php8.3-fpm

# Nginx
sudo systemctl restart nginx
sudo systemctl status nginx

# MySQL
sudo systemctl restart mysql
sudo systemctl status mysql

# Redis
sudo systemctl restart redis-server
sudo systemctl status redis-server

# Queue Workers
sudo supervisorctl status
sudo supervisorctl restart fhmaison-worker:*
```

### Clearing Caches

```bash
cd /var/www/fhmaison

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches (production)
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

---

## Troubleshooting

### Issue: 502 Bad Gateway

**Cause:** PHP-FPM not running or socket issue

**Solution:**
```bash
sudo systemctl restart php8.3-fpm
sudo systemctl status php8.3-fpm
```

---

### Issue: 500 Internal Server Error

**Cause:** Application error, check logs

**Solution:**
```bash
# Check Laravel logs
tail -100 /var/www/fhmaison/storage/logs/laravel.log

# Check Nginx error logs
tail -100 /var/log/nginx/fhmaison_error.log

# Ensure proper permissions
sudo chown -R fhmaison:www-data /var/www/fhmaison
sudo chmod -R 775 /var/www/fhmaison/storage
sudo chmod -R 775 /var/www/fhmaison/bootstrap/cache
```

---

### Issue: Permission Denied Errors

**Solution:**
```bash
cd /var/www/fhmaison
sudo chown -R fhmaison:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache
find storage -type f -exec chmod 664 {} \;
find storage -type d -exec chmod 775 {} \;
```

---

### Issue: Database Connection Failed

**Cause:** Wrong credentials or MySQL not running

**Solution:**
```bash
# Check MySQL status
sudo systemctl status mysql

# Test database connection
mysql -u fhmaison_user -p fhmaison_db

# Verify .env credentials match database
cat /root/.fhmaison_db_credentials
```

---

### Issue: SSL Certificate Not Working

**Cause:** Certificate not issued or Nginx misconfigured

**Solution:**
```bash
# Check certificate status
sudo certbot certificates

# Test Nginx configuration
sudo nginx -t

# Renew certificates manually
sudo certbot renew --dry-run
```

---

### Issue: Site Running Slow

**Solutions:**

1. **Enable OPcache:**
```bash
# Already configured in setup script, verify:
php -i | grep opcache
```

2. **Optimize Laravel:**
```bash
cd /var/www/fhmaison
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. **Check server resources:**
```bash
htop
df -h
free -h
```

4. **Optimize MySQL:**
```bash
# Install mysqltuner
sudo apt install mysqltuner
sudo mysqltuner
```

---

## Security Best Practices

### Regular Security Updates

The system is configured for automatic security updates, but manually check weekly:

```bash
sudo apt update
sudo apt upgrade -y
```

### Monitor Failed Login Attempts

```bash
# Check Fail2Ban status
sudo fail2ban-client status sshd

# View banned IPs
sudo fail2ban-client status

# Unban an IP if needed
sudo fail2ban-client set sshd unbanip IP_ADDRESS
```

### Regular Security Audits

Run the security check script:

```bash
sudo /usr/local/bin/fhmaison-security-check.sh
```

### Backup Strategy

Implement the **3-2-1 backup rule:**
- 3 copies of data
- 2 different storage types
- 1 copy off-site

**Recommended:**
1. Daily automated backups to server (`/var/backups/fhmaison`)
2. Weekly backups to external storage (AWS S3, Backblaze B2)
3. Monthly backups to local machine

**Set up automated backups:**

```bash
# Create backup script
sudo nano /usr/local/bin/fhmaison-backup.sh
```

```bash
#!/bin/bash
BACKUP_DIR="/var/backups/fhmaison/$(date +%Y%m%d)"
mkdir -p "$BACKUP_DIR"

# Backup database
mysqldump -u fhmaison_user -p"$DB_PASSWORD" fhmaison_db > "$BACKUP_DIR/database.sql"

# Backup files
tar -czf "$BACKUP_DIR/files.tar.gz" -C /var/www/fhmaison .

# Keep only last 30 days
find /var/backups/fhmaison -type d -mtime +30 -exec rm -rf {} \;
```

```bash
# Make executable
sudo chmod +x /usr/local/bin/fhmaison-backup.sh

# Add to crontab (run daily at 2 AM)
sudo crontab -e
# Add: 0 2 * * * /usr/local/bin/fhmaison-backup.sh
```

### SSL Best Practices

Test SSL configuration:
- https://www.ssllabs.com/ssltest/analyze.html?d=fhmaison.fr

Target: **A+ rating**

### Monitor Application

Set up application monitoring:
1. **Laravel Horizon** (for queue monitoring)
2. **Laravel Telescope** (for debugging - dev only)
3. **Sentry** (for error tracking)
4. **New Relic** or **DataDog** (for performance monitoring)

---

## Performance Optimization

### Enable Full Page Caching

For high traffic, consider:

1. **Laravel Response Cache:**
```bash
composer require spatie/laravel-responsecache
php artisan vendor:publish --provider="Spatie\ResponseCache\ResponseCacheServiceProvider"
```

2. **Redis Cache:**
Already configured in `.env`:
```env
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

3. **CDN for Static Assets:**
- Use Cloudflare (free tier available)
- Configure in Laravel for asset URLs

### Database Optimization

```bash
# Optimize tables
mysqlcheck -o fhmaison_db -u fhmaison_user -p

# Analyze queries
php artisan telescope:install  # if not installed
# Access: https://fhmaison.fr/telescope
```

### Nginx Caching

Already configured in `fhmaison.conf`:
- Static asset caching (1 year)
- Gzip compression enabled
- Browser caching headers

---

## Useful Commands Reference

### Laravel Artisan Commands

```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Maintenance mode
php artisan down --retry=60
php artisan up

# Queue management
php artisan queue:work
php artisan queue:restart
php artisan queue:failed

# Database
php artisan migrate
php artisan migrate:rollback
php artisan db:seed
php artisan migrate:fresh --seed
```

### System Commands

```bash
# Check disk space
df -h

# Check memory usage
free -h

# Check CPU usage
top
htop

# Check running processes
ps aux | grep php
ps aux | grep nginx

# Check open connections
ss -tulpn

# Check logs
journalctl -u nginx -f
journalctl -u php8.3-fpm -f
```

---

## Support & Resources

### Documentation
- Laravel: https://laravel.com/docs
- Nginx: https://nginx.org/en/docs/
- MySQL: https://dev.mysql.com/doc/
- Let's Encrypt: https://letsencrypt.org/docs/

### Monitoring Tools
- Server monitoring: `htop`, `iotop`, `nethogs`
- Log monitoring: `tail`, `less`, `grep`
- Security: `fail2ban-client`, `ufw status`

### Emergency Contacts
- Server provider: OVH Support
- Domain registrar: OVH
- SSL issues: Let's Encrypt Community

---

## Deployment Checklist

Use this checklist for each deployment:

- [ ] Backup current database and files
- [ ] Test changes in staging environment
- [ ] Enable maintenance mode
- [ ] Pull/upload latest code
- [ ] Run `composer install --no-dev`
- [ ] Run `npm ci && npm run build`
- [ ] Run database migrations
- [ ] Clear caches
- [ ] Rebuild caches
- [ ] Test application functionality
- [ ] Check error logs
- [ ] Disable maintenance mode
- [ ] Monitor application for 30 minutes
- [ ] Update documentation if needed

---

## Conclusion

Your FH Maison application is now deployed on a production-ready VPS with:

✅ **Security:** SSL/HTTPS, Fail2Ban, hardened SSH, firewall
✅ **Performance:** OPcache, Redis caching, optimized Nginx
✅ **Reliability:** Automated backups, queue workers, monitoring
✅ **Maintainability:** Deployment scripts, log rotation, documentation
✅ **Scalability:** Ready for growth with proper architecture

**Your application is live at:** https://fhmaison.fr

For questions or issues, refer to the troubleshooting section or check the application logs.

---

**Version:** 1.0  
**Last Updated:** January 2026  
**Author:** FH Maison DevOps Team
