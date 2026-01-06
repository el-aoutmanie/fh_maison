# FH Maison - Quick Start Guide

## 🚀 Rapid Deployment (30 minutes)

This quick start guide will get your application running on your VPS in under 30 minutes.

---

## Prerequisites Check

Before starting, ensure you have:
- ✅ VPS access: `ssh root@51.83.47.194`
- ✅ Domain pointing to server: `fhmaison.fr → 51.83.47.194`
- ✅ This deployment folder uploaded to server

---

## Step-by-Step Deployment

### 1. Connect to Server (1 min)

```bash
ssh root@51.83.47.194
```

### 2. Upload Deployment Files (2 mins)

From your local machine:

```bash
cd /path/to/nouni_laravel-master
scp -r deployment root@51.83.47.194:/root/
```

### 3. Run Server Setup (10 mins)

```bash
cd /root/deployment/scripts
chmod +x *.sh
./01-setup-server.sh
```

**☕ Take a coffee break** - This installs PHP 8.3, Nginx, MySQL, Redis, Node.js, and more.

### 4. Secure MySQL (2 mins)

```bash
mysql_secure_installation
```

Press `Y` for all prompts and set a strong root password.

```bash
./02-setup-database.sh
```

**⚠️ IMPORTANT:** Copy the database credentials shown at the end!

### 5. Upload Application Code (3 mins)

From your local machine:

```bash
# Create archive (exclude unnecessary files)
cd /path/to/nouni_laravel-master
tar -czf fhmaison.tar.gz \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='.git' \
    --exclude='storage/logs/*' \
    .

# Upload to server
scp fhmaison.tar.gz root@51.83.47.194:/tmp/
```

On server:

```bash
mkdir -p /var/www/fhmaison
tar -xzf /tmp/fhmaison.tar.gz -C /var/www/fhmaison
chown -R fhmaison:www-data /var/www/fhmaison
```

### 6. Deploy Application (5 mins)

```bash
cd /root/deployment/scripts
./03-deploy-application.sh
```

When prompted to edit `.env`:
1. Update database credentials (from step 4)
2. Set `APP_ENV=production`
3. Set `APP_DEBUG=false`
4. Set `APP_URL=https://fhmaison.fr`
5. Save and exit (Ctrl+X, Y, Enter)

### 7. Setup SSL Certificate (5 mins)

First, update email in the script:

```bash
nano /root/deployment/scripts/04-setup-ssl.sh
# Change EMAIL to your email
```

Then run:

```bash
./04-setup-ssl.sh
```

**🎉 Your site is now live at:** https://fhmaison.fr

### 8. Security Hardening (2 mins)

**CRITICAL:** Before running this, set up SSH keys!

On your local machine:

```bash
ssh-copy-id fhmaison@51.83.47.194
```

Test it works:

```bash
ssh fhmaison@51.83.47.194
exit
```

Now run security script on server:

```bash
./05-security-hardening.sh
```

---

## Post-Deployment (Optional)

### Create Admin User

```bash
cd /var/www/fhmaison
php artisan tinker
```

```php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@fhmaison.fr';
$user->password = bcrypt('YourSecurePassword');
$user->save();
$user->assignRole('admin');
exit
```

### Configure Email (Optional)

Edit `.env`:

```bash
nano /var/www/fhmaison/.env
```

Add:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host.com
MAIL_PORT=587
MAIL_USERNAME=your-email@fhmaison.fr
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

Then:

```bash
php artisan config:cache
```

---

## Verify Deployment

Check everything is running:

```bash
# Check services
systemctl status nginx
systemctl status php8.3-fpm
systemctl status mysql
systemctl status redis-server

# Check queue workers
supervisorctl status

# Check logs for errors
tail -50 /var/www/fhmaison/storage/logs/laravel.log

# Visit your site
curl -I https://fhmaison.fr
```

---

## Common Issues

### Issue: SSL certificate failed

**Solution:** Wait 5 minutes for DNS propagation, then retry:

```bash
./04-setup-ssl.sh
```

### Issue: 502 Bad Gateway

**Solution:** Restart PHP-FPM:

```bash
systemctl restart php8.3-fpm
```

### Issue: Permission errors

**Solution:** Fix permissions:

```bash
cd /var/www/fhmaison
chown -R fhmaison:www-data .
chmod -R 775 storage bootstrap/cache
```

---

## Next Steps

1. ✅ Test all site functionality
2. ✅ Set up regular backups
3. ✅ Configure monitoring
4. ✅ Add Stripe keys for payments
5. ✅ Test email sending

---

## Useful Commands

```bash
# View Laravel logs
tail -f /var/www/fhmaison/storage/logs/laravel.log

# View Nginx logs
tail -f /var/log/nginx/fhmaison_error.log

# Restart services
systemctl restart nginx
systemctl restart php8.3-fpm

# Clear Laravel cache
cd /var/www/fhmaison
php artisan cache:clear

# Update application
cd /root/deployment/scripts
./06-update-application.sh
```

---

## Support

For detailed information, see: `DEPLOYMENT_GUIDE.md`

For issues, check: `TROUBLESHOOTING.md`

---

**🎉 Congratulations! Your application is now live at https://fhmaison.fr**
