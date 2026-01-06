# FH Maison - Fresh VPS Installation Guide

## Quick Setup for Fresh Ubuntu 22.04 VPS

Since you've reinstalled your VPS and cloned the repository to `/var/www/laravel`, follow these steps:

---

## Step 1: Initial Server Setup (15 mins)

````bash
# Connect to your VPS
ssh root@51.83.47.194

# Upload deployment folder
# From your local machine:
cd /home/el_aoutmanie/Downloads/nouni_laravel-master/deployment
scp -r scripts root@51.83.47.194:/root/
scp -r nginx root@51.83.47.194:/root/

# On the server, run setup
cd /root/scripts
chmod +x *.sh
sudo ./01-setup-server.sh
````

---

## Step 2: Setup Database (2 mins)

````bash
cd /root/scripts
sudo ./02-setup-database.sh

# Save the credentials shown!
# They're also saved to: /root/.fhmaison_db_credentials
````

---

## Step 3: Clone Your Repository (if not done)

````bash
# If you haven't cloned yet:
cd /var/www
sudo git clone YOUR_REPOSITORY_URL laravel

# If already cloned, just ensure proper ownership:
sudo chown -R www-data:www-data /var/www/laravel
sudo chmod -R 755 /var/www/laravel
````

---

## Step 4: Deploy Application (5 mins)

````bash
cd /root/scripts
sudo ./03-deploy-application.sh
````

This will:
- ✅ Install Composer dependencies
- ✅ Create and configure `.env` file
- ✅ Generate application key
- ✅ Install NPM dependencies and build assets
- ✅ Run database migrations
- ✅ Optimize Laravel
- ✅ Set proper permissions
- ✅ Configure Nginx

---

## Step 5: Configure Nginx

````bash
# Copy the Nginx configuration
sudo cp /root/nginx/fhmaison.conf /etc/nginx/sites-available/fhmaison.fr

# Remove default site
sudo rm -f /etc/nginx/sites-enabled/default

# Enable your site
sudo ln -sf /etc/nginx/sites-available/fhmaison.fr /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx

# Test the application
curl http://fhmaison.fr
````

---

## Step 6: Setup SSL/HTTPS (5 mins)

````bash
cd /root/scripts

# Edit email in the script first
sudo nano 04-setup-ssl.sh
# Change: EMAIL="admin@fhmaison.fr" to your actual email

# Run SSL setup
sudo ./04-setup-ssl.sh

# Test HTTPS
curl -I https://fhmaison.fr
````

---

## Step 7: Create Admin User

````bash
cd /var/www/laravel

sudo php artisan tinker
````

In tinker, run:

````php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@fhmaison.fr';
$user->password = bcrypt('YourSecurePassword123!');
$user->email_verified_at = now();
$user->save();

// Assign admin role if using Spatie Permission
$user->assignRole('admin');

exit
````

---

## Step 8: Security Hardening (Optional)

**⚠️ WARNING: Only run this if you have SSH keys set up!**

````bash
# Set up SSH keys first (from local machine)
ssh-copy-id ubuntu@51.83.47.194
# or
ssh-copy-id root@51.83.47.194

# Test key login works
ssh ubuntu@51.83.47.194

# Then run security hardening
cd /root/scripts
sudo ./05-security-hardening.sh
````

---

## Verification Checklist

After setup, verify everything works:

````bash
# Check services
sudo systemctl status nginx
sudo systemctl status php8.3-fpm
sudo systemctl status mysql
sudo systemctl status redis-server

# Test application
curl -I https://fhmaison.fr

# Check logs
sudo tail -50 /var/www/laravel/storage/logs/laravel.log
sudo tail -50 /var/log/nginx/fhmaison_error.log

# Check SSL certificate
sudo certbot certificates
````

---

## Important File Locations

| What | Location |
|------|----------|
| Application | `/var/www/laravel` |
| Nginx Config | `/etc/nginx/sites-available/fhmaison.fr` |
| Environment File | `/var/www/laravel/.env` |
| Database Credentials | `/root/.fhmaison_db_credentials` |
| SSL Certificates | `/etc/letsencrypt/live/fhmaison.fr/` |
| Application Logs | `/var/www/laravel/storage/logs/` |
| Nginx Logs | `/var/log/nginx/fhmaison_*.log` |

---

## Quick Commands

````bash
# Restart services
sudo systemctl restart nginx
sudo systemctl restart php8.3-fpm

# Clear Laravel cache
cd /var/www/laravel
sudo php artisan cache:clear
sudo php artisan config:cache

# View logs
sudo tail -f /var/www/laravel/storage/logs/laravel.log

# Update application
cd /root/scripts
sudo ./06-update-application.sh

# Manual backup
mysqldump -u fhmaison_user -p fhmaison_db > backup.sql
tar -czf backup.tar.gz /var/www/laravel
````

---

## Troubleshooting

### Application not loading
````bash
# Check Nginx
sudo nginx -t
sudo systemctl status nginx

# Check PHP-FPM
sudo systemctl status php8.3-fpm

# Check permissions
sudo chown -R www-data:www-data /var/www/laravel
sudo chmod -R 775 /var/www/laravel/storage
````

### Database connection issues
````bash
# Check credentials
cat /root/.fhmaison_db_credentials
cat /var/www/laravel/.env | grep DB_

# Test connection
mysql -u fhmaison_user -p fhmaison_db
````

### SSL issues
````bash
# Check certificate
sudo certbot certificates

# Renew certificate
sudo certbot renew

# Test renewal
sudo certbot renew --dry-run
````

---

## Next Steps

1. ✅ Application is running
2. ✅ SSL is configured
3. ✅ Admin user created
4. Configure email settings in `.env`
5. Add Stripe keys (if using payments)
6. Set up automated backups
7. Configure monitoring
8. Test all functionality

---

**Your application should now be live at:** https://fhmaison.fr

For detailed documentation, see:
- `DEPLOYMENT_GUIDE.md` - Complete deployment guide
- `TROUBLESHOOTING.md` - Common issues and solutions
- `QUICK_START.md` - 30-minute deployment guide
