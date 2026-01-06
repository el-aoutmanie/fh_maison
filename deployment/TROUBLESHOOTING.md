# FH Maison - Troubleshooting Guide

## 🔧 Common Issues and Solutions

This guide covers common issues you might encounter and how to resolve them.

---

## Table of Contents

1. [Web Server Issues](#web-server-issues)
2. [Application Errors](#application-errors)
3. [Database Issues](#database-issues)
4. [SSL/HTTPS Issues](#sslhttps-issues)
5. [Performance Issues](#performance-issues)
6. [Queue Worker Issues](#queue-worker-issues)
7. [Permission Issues](#permission-issues)
8. [Email Issues](#email-issues)

---

## Web Server Issues

### 502 Bad Gateway Error

**Symptoms:**
- Nginx shows "502 Bad Gateway" error
- Site is inaccessible

**Causes:**
- PHP-FPM service is down or crashed
- PHP-FPM socket issue
- PHP script timeout

**Solutions:**

```bash
# Check PHP-FPM status
sudo systemctl status php8.3-fpm

# If not running, start it
sudo systemctl start php8.3-fpm

# Check PHP-FPM logs
sudo tail -50 /var/log/php8.3-fpm.log

# Restart PHP-FPM
sudo systemctl restart php8.3-fpm

# Check if socket exists
ls -la /var/run/php/php8.3-fpm.sock

# If socket permission issue
sudo chmod 666 /var/run/php/php8.3-fpm.sock
```

---

### 504 Gateway Timeout

**Symptoms:**
- Request takes too long and times out
- Nginx returns 504 error

**Solutions:**

```bash
# Increase PHP execution time
sudo nano /etc/php/8.3/fpm/php.ini
# Set: max_execution_time = 300

# Increase Nginx timeout
sudo nano /etc/nginx/sites-available/fhmaison
# Add in location ~ \.php$ block:
# fastcgi_read_timeout 300;

# Restart services
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx
```

---

### 403 Forbidden Error

**Symptoms:**
- Cannot access certain files or directories
- Nginx returns 403 error

**Solutions:**

```bash
# Check file permissions
ls -la /var/www/fhmaison

# Fix permissions
sudo chown -R fhmaison:www-data /var/www/fhmaison
sudo chmod -R 755 /var/www/fhmaison
sudo chmod -R 775 /var/www/fhmaison/storage
sudo chmod -R 775 /var/www/fhmaison/bootstrap/cache

# Check Nginx configuration
sudo nginx -t

# Check SELinux (if enabled)
sudo setenforce 0  # Temporarily disable to test
```

---

### 404 Not Found (except homepage)

**Symptoms:**
- Homepage works but other pages show 404
- URLs not being rewritten correctly

**Solutions:**

```bash
# Check Nginx configuration has try_files
sudo nano /etc/nginx/sites-available/fhmaison

# Ensure this line exists in location / block:
# try_files $uri $uri/ /index.php?$query_string;

# Test and reload Nginx
sudo nginx -t
sudo systemctl reload nginx

# Clear Laravel route cache
cd /var/www/fhmaison
php artisan route:clear
php artisan route:cache
```

---

## Application Errors

### 500 Internal Server Error

**Symptoms:**
- White page or generic error message
- Application not working

**Solutions:**

```bash
# Check Laravel logs
sudo tail -100 /var/www/fhmaison/storage/logs/laravel.log

# Check Nginx error logs
sudo tail -100 /var/log/nginx/fhmaison_error.log

# Check PHP error logs
sudo tail -100 /var/log/php8.3-fpm.log

# Enable debug mode temporarily (ONLY for troubleshooting)
cd /var/www/fhmaison
sudo nano .env
# Set: APP_DEBUG=true
php artisan config:clear

# After fixing, disable debug mode
# Set: APP_DEBUG=false
php artisan config:cache
```

---

### "The page has expired" Error (CSRF Token Mismatch)

**Symptoms:**
- Form submission fails with "419 Page Expired"
- CSRF token mismatch errors

**Solutions:**

```bash
# Clear cache
cd /var/www/fhmaison
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Check session configuration
php artisan config:show session

# Verify session files are writable
sudo chmod -R 775 storage/framework/sessions
sudo chown -R fhmaison:www-data storage/framework/sessions

# Check Redis connection if using Redis for sessions
redis-cli ping  # Should return PONG
```

---

### Application Key Missing

**Symptoms:**
- "No application encryption key has been specified"

**Solutions:**

```bash
cd /var/www/fhmaison

# Generate new key
php artisan key:generate

# Clear cache
php artisan config:cache
```

---

### Mixed Content Warnings (HTTP/HTTPS)

**Symptoms:**
- Browser shows "Not Secure" or mixed content warnings
- Some assets load over HTTP instead of HTTPS

**Solutions:**

```bash
cd /var/www/fhmaison

# Update .env
sudo nano .env
# Ensure: APP_URL=https://fhmaison.fr

# Add to AppServiceProvider boot method
# URL::forceScheme('https');

# Clear cache
php artisan config:cache
php artisan cache:clear

# Check assets are using asset() helper
# In Blade: {{ asset('path/to/file') }}
```

---

## Database Issues

### Connection Refused

**Symptoms:**
- "Connection refused" error
- Cannot connect to MySQL

**Solutions:**

```bash
# Check MySQL is running
sudo systemctl status mysql

# Start MySQL if stopped
sudo systemctl start mysql

# Check MySQL is listening
sudo ss -tlnp | grep 3306

# Test connection
mysql -u fhmaison_user -p fhmaison_db

# Check credentials in .env
cat /var/www/fhmaison/.env | grep DB_
cat /root/.fhmaison_db_credentials

# Restart MySQL
sudo systemctl restart mysql
```

---

### Access Denied for User

**Symptoms:**
- "Access denied for user 'fhmaison_user'@'localhost'"

**Solutions:**

```bash
# Verify user exists
sudo mysql -e "SELECT User, Host FROM mysql.user WHERE User='fhmaison_user';"

# Reset user password
sudo mysql
```

```sql
ALTER USER 'fhmaison_user'@'localhost' IDENTIFIED BY 'new_password';
FLUSH PRIVILEGES;
EXIT;
```

```bash
# Update .env with new password
cd /var/www/fhmaison
sudo nano .env
# Update: DB_PASSWORD=new_password

# Clear cache
php artisan config:cache
```

---

### Too Many Connections

**Symptoms:**
- "Too many connections" error
- Database becomes unavailable

**Solutions:**

```bash
# Check current connections
sudo mysql -e "SHOW PROCESSLIST;"

# Kill specific connection
sudo mysql -e "KILL <connection_id>;"

# Increase max connections temporarily
sudo mysql -e "SET GLOBAL max_connections = 200;"

# Permanently increase max connections
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
# Add/update: max_connections = 200

# Restart MySQL
sudo systemctl restart mysql

# Check Laravel connection settings
cd /var/www/fhmaison
nano config/database.php
# Check: 'pool' => [
#     'min' => 2,
#     'max' => 10,
# ]
```

---

### Database Migration Errors

**Symptoms:**
- Migration fails with errors
- Tables not created

**Solutions:**

```bash
cd /var/www/fhmaison

# Check migration status
php artisan migrate:status

# Rollback last migration
php artisan migrate:rollback

# Rollback all and re-migrate
php artisan migrate:fresh  # WARNING: Deletes all data!

# Migrate with specific step
php artisan migrate --step=1

# Check for syntax errors in migration files
php artisan migrate --pretend
```

---

## SSL/HTTPS Issues

### Certificate Not Valid

**Symptoms:**
- Browser shows "Your connection is not private"
- SSL certificate errors

**Solutions:**

```bash
# Check certificate status
sudo certbot certificates

# Check certificate expiry
echo | openssl s_client -servername fhmaison.fr -connect fhmaison.fr:443 2>/dev/null | openssl x509 -noout -dates

# Renew certificate
sudo certbot renew

# Force certificate renewal
sudo certbot renew --force-renewal

# Restart Nginx
sudo systemctl restart nginx

# Check Nginx SSL configuration
sudo nginx -t
```

---

### DNS Not Pointing to Server

**Symptoms:**
- Domain not resolving to server IP
- SSL certificate validation fails

**Solutions:**

```bash
# Check DNS resolution
dig fhmaison.fr
nslookup fhmaison.fr

# Check from external DNS
dig @8.8.8.8 fhmaison.fr

# Verify A record points to correct IP
# Should return: 51.83.47.194

# Wait for DNS propagation (24-48 hours)
# Check propagation: https://dnschecker.org
```

---

### Certificate Auto-Renewal Failing

**Symptoms:**
- Certificate expires
- Auto-renewal doesn't work

**Solutions:**

```bash
# Check Certbot timer status
sudo systemctl status certbot.timer

# Enable timer if disabled
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer

# Test renewal
sudo certbot renew --dry-run

# Check renewal logs
sudo tail -50 /var/log/letsencrypt/letsencrypt.log

# Manually renew
sudo certbot renew
```

---

## Performance Issues

### Slow Page Load Times

**Symptoms:**
- Pages take several seconds to load
- High server load

**Solutions:**

```bash
# Check server resources
htop
free -h
df -h

# Enable OPcache (should already be enabled)
php -i | grep opcache.enable

# Clear and rebuild Laravel caches
cd /var/www/fhmaison
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check slow queries in MySQL
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
# Add:
# slow_query_log = 1
# long_query_time = 2
# slow_query_log_file = /var/log/mysql/slow-query.log

sudo systemctl restart mysql

# Analyze slow queries
sudo mysqldumpslow -t 10 /var/log/mysql/slow-query.log

# Check Nginx access patterns
sudo tail -100 /var/log/nginx/fhmaison_access.log
```

---

### High Memory Usage

**Symptoms:**
- Server running out of memory
- Applications crashing

**Solutions:**

```bash
# Check memory usage
free -h
ps aux --sort=-%mem | head -10

# Identify memory-hungry processes
ps aux | awk '{print $2, $4, $11}' | sort -k2rn | head -10

# Restart PHP-FPM to free memory
sudo systemctl restart php8.3-fpm

# Adjust PHP-FPM workers
sudo nano /etc/php/8.3/fpm/pool.d/www.conf
# Adjust:
# pm = dynamic
# pm.max_children = 10
# pm.start_servers = 2
# pm.min_spare_servers = 1
# pm.max_spare_servers = 3

# Restart PHP-FPM
sudo systemctl restart php8.3-fpm

# Add swap space if needed
sudo fallocate -l 2G /swapfile
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile
```

---

### Database Performance Issues

**Symptoms:**
- Queries are slow
- High database CPU usage

**Solutions:**

```bash
# Check database indexes
sudo mysql fhmaison_db -e "SHOW INDEX FROM products;"

# Analyze and optimize tables
sudo mysqlcheck -o fhmaison_db -u fhmaison_user -p

# Check database size
sudo mysql -e "SELECT table_schema AS 'Database', ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)' FROM information_schema.tables GROUP BY table_schema;"

# Install and run mysqltuner
sudo apt install mysqltuner
sudo mysqltuner

# Enable query cache (if MySQL 5.7)
# Note: Removed in MySQL 8.0

# Optimize specific table
sudo mysql fhmaison_db -e "OPTIMIZE TABLE orders;"
```

---

## Queue Worker Issues

### Queue Not Processing Jobs

**Symptoms:**
- Jobs stuck in queue
- Background tasks not running

**Solutions:**

```bash
# Check queue worker status
sudo supervisorctl status fhmaison-worker:*

# Restart queue workers
sudo supervisorctl restart fhmaison-worker:*

# Check queue size
cd /var/www/fhmaison
php artisan queue:work --stop-when-empty

# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Check Redis connection
redis-cli ping  # Should return PONG

# Check worker logs
tail -50 /var/www/fhmaison/storage/logs/worker.log
```

---

### Queue Worker Dying/Crashing

**Symptoms:**
- Worker stops processing
- Supervisor keeps restarting worker

**Solutions:**

```bash
# Check supervisor logs
sudo tail -50 /var/log/supervisor/supervisord.log

# Check worker log
tail -100 /var/www/fhmaison/storage/logs/worker.log

# Increase worker timeout
sudo nano /etc/supervisor/conf.d/fhmaison-worker.conf
# Update: stopwaitsecs=3600

# Reload supervisor
sudo supervisorctl reread
sudo supervisorctl update

# Check memory limits
sudo nano /etc/php/8.3/cli/php.ini
# Increase: memory_limit = 512M

# Restart workers
sudo supervisorctl restart fhmaison-worker:*
```

---

## Permission Issues

### Storage Not Writable

**Symptoms:**
- "Unable to write to storage" errors
- Log files not being created
- File upload failures

**Solutions:**

```bash
# Fix storage permissions
cd /var/www/fhmaison
sudo chown -R fhmaison:www-data storage
sudo chmod -R 775 storage
find storage -type f -exec chmod 664 {} \;
find storage -type d -exec chmod 775 {} \;

# Fix bootstrap/cache permissions
sudo chown -R fhmaison:www-data bootstrap/cache
sudo chmod -R 775 bootstrap/cache
find bootstrap/cache -type f -exec chmod 664 {} \;

# Recreate storage link
php artisan storage:link

# Check disk space
df -h
```

---

### File Upload Issues

**Symptoms:**
- Cannot upload files
- Upload fails silently
- File size limit errors

**Solutions:**

```bash
# Check PHP upload limits
php -i | grep upload_max_filesize
php -i | grep post_max_size

# Increase upload limits
sudo nano /etc/php/8.3/fpm/php.ini
# Set:
# upload_max_filesize = 100M
# post_max_size = 100M

# Restart PHP-FPM
sudo systemctl restart php8.3-fpm

# Check Nginx client body size
sudo nano /etc/nginx/sites-available/fhmaison
# Ensure: client_max_body_size 100M;

# Restart Nginx
sudo systemctl reload nginx

# Check storage permissions
ls -la /var/www/fhmaison/storage/app
```

---

## Email Issues

### Emails Not Sending

**Symptoms:**
- No emails received
- Mail queue building up

**Solutions:**

```bash
# Check mail configuration
cd /var/www/fhmaison
php artisan config:show mail

# Test mail connection
php artisan tinker
```

```php
Mail::raw('Test email', function($msg) {
    $msg->to('test@example.com')->subject('Test');
});
```

```bash
# Check mail logs
tail -50 storage/logs/laravel.log | grep -i mail

# If using queue for mail
php artisan queue:work --queue=emails

# Check SMTP connectivity
telnet smtp.example.com 587

# Test with swaks
sudo apt install swaks
swaks --to test@example.com --from noreply@fhmaison.fr --server smtp.example.com:587 --auth-user username --auth-password password
```

---

### Emails Going to Spam

**Symptoms:**
- Emails delivered but in spam folder

**Solutions:**

```bash
# Set up SPF record in DNS
# Add TXT record:
# v=spf1 a mx ip4:51.83.47.194 ~all

# Set up DKIM (Domain Keys Identified Mail)
sudo apt install opendkim opendkim-tools
sudo opendkim-genkey -t -s mail -d fhmaison.fr
sudo cat /etc/opendkim/keys/fhmaison.fr/mail.txt
# Add DKIM TXT record to DNS

# Set up DMARC
# Add TXT record: _dmarc.fhmaison.fr
# v=DMARC1; p=quarantine; rua=mailto:dmarc@fhmaison.fr

# Test email deliverability
# Use: https://www.mail-tester.com/
```

---

## Emergency Recovery

### Complete Application Reset

**Use only as last resort:**

```bash
# Backup first!
cd /var/www/fhmaison
php artisan down

# Backup database
mysqldump -u fhmaison_user -p fhmaison_db > ~/fhmaison_backup_$(date +%Y%m%d).sql

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
redis-cli FLUSHALL

# Reinstall dependencies
composer install --no-dev
npm ci
npm run build

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
sudo chown -R fhmaison:www-data .
sudo chmod -R 775 storage bootstrap/cache

# Restart services
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx
sudo systemctl restart redis-server
sudo supervisorctl restart fhmaison-worker:*

# Bring app back up
php artisan up
```

---

## Getting Help

### Collect Diagnostic Information

Before asking for help, collect this information:

```bash
# System information
uname -a
cat /etc/os-release

# Service status
systemctl status nginx
systemctl status php8.3-fpm
systemctl status mysql
systemctl status redis-server

# Error logs
tail -100 /var/www/fhmaison/storage/logs/laravel.log
tail -100 /var/log/nginx/fhmaison_error.log
tail -50 /var/log/php8.3-fpm.log

# Configuration
php -v
nginx -v
mysql --version
redis-server --version

# Resources
free -h
df -h
top -bn1 | head -20
```

### Where to Get Support

1. **Laravel Documentation:** https://laravel.com/docs
2. **Laravel Community:** https://laracasts.com/discuss
3. **Stack Overflow:** Tag questions with `laravel`, `nginx`, `mysql`
4. **GitHub Issues:** For framework bugs
5. **Server Provider:** OVH support for server issues

---

**Last Updated:** January 2026
