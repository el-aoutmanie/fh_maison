# FH Maison - Production Deployment Package

## 📦 Overview

This deployment package contains everything you need to deploy the FH Maison Laravel e-commerce application to a production VPS running Ubuntu 22.04 LTS with PHP 8.3.

---

## 📁 Package Contents

```
deployment/
├── README.md                    # This file
├── QUICK_START.md              # 30-minute rapid deployment guide
├── DEPLOYMENT_GUIDE.md         # Comprehensive deployment documentation
├── TROUBLESHOOTING.md          # Common issues and solutions
├── nginx/
│   └── fhmaison.conf           # Production Nginx configuration
└── scripts/
    ├── 01-setup-server.sh      # Initial server setup
    ├── 02-setup-database.sh    # Database creation and configuration
    ├── 03-deploy-application.sh # Application deployment
    ├── 04-setup-ssl.sh         # SSL/HTTPS with Let's Encrypt
    ├── 05-security-hardening.sh # Security best practices
    └── 06-update-application.sh # Application update script
```

---

## 🚀 Quick Start

For rapid deployment (30 minutes), follow these steps:

1. **Read QUICK_START.md** - Essential quick guide
2. **Upload this folder** to your VPS
3. **Run scripts in order** (01 through 05)
4. **Configure your application** (.env, admin user, etc.)

```bash
# Example quick deployment
ssh root@51.83.47.194
cd /root/deployment/scripts
chmod +x *.sh
./01-setup-server.sh
./02-setup-database.sh
# ... upload application code ...
./03-deploy-application.sh
./04-setup-ssl.sh
./05-security-hardening.sh
```

---

## 📚 Documentation

### For First-Time Deployment

Start here: **[QUICK_START.md](QUICK_START.md)**
- 30-minute rapid deployment
- Step-by-step instructions
- Minimal configuration required

### For Detailed Understanding

Read this: **[DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)**
- Comprehensive documentation
- Best practices and optimization
- Security configuration
- Monitoring and maintenance
- Performance tuning

### When Things Go Wrong

Check here: **[TROUBLESHOOTING.md](TROUBLESHOOTING.md)**
- Common issues and solutions
- Error debugging guides
- Service recovery procedures
- Emergency recovery steps

---

## 🔧 Deployment Scripts

### 01-setup-server.sh
**Purpose:** Initial VPS setup and software installation

**What it does:**
- Updates system packages
- Installs PHP 8.3 + extensions
- Installs Nginx web server
- Installs MySQL 8.0 database
- Installs Redis cache server
- Installs Composer (PHP package manager)
- Installs Node.js 20 (for frontend builds)
- Creates deployment user
- Configures firewall (UFW)

**Duration:** ~10-15 minutes

**Usage:**
```bash
sudo ./01-setup-server.sh
```

---

### 02-setup-database.sh
**Purpose:** Create and configure MySQL database

**What it does:**
- Creates database: `fhmaison_db`
- Creates database user: `fhmaison_user`
- Generates secure password
- Sets proper permissions
- Optimizes MySQL configuration
- Saves credentials securely

**Duration:** ~2 minutes

**Usage:**
```bash
sudo ./02-setup-database.sh
```

**⚠️ Important:** Save the database credentials displayed at the end!

---

### 03-deploy-application.sh
**Purpose:** Deploy Laravel application to production

**What it does:**
- Installs PHP dependencies (Composer)
- Configures environment (.env)
- Generates application key
- Installs Node dependencies
- Builds frontend assets (Vite)
- Runs database migrations
- Optimizes Laravel for production
- Creates storage symlink
- Sets proper file permissions
- Configures Nginx
- Sets up Laravel scheduler (cron)
- Sets up queue workers (Supervisor)

**Duration:** ~5 minutes

**Usage:**
```bash
sudo ./03-deploy-application.sh
```

**Note:** You'll be prompted to configure the .env file during this process.

---

### 04-setup-ssl.sh
**Purpose:** Install SSL certificate and enable HTTPS

**What it does:**
- Installs Certbot (Let's Encrypt client)
- Obtains SSL certificates for domain
- Configures Nginx for HTTPS
- Sets up automatic certificate renewal
- Enables security headers (HSTS, etc.)

**Duration:** ~5 minutes

**Prerequisites:**
- DNS must point to server
- Ports 80 and 443 must be open

**Usage:**
```bash
# Edit script first to set your email
sudo nano ./04-setup-ssl.sh
# Then run
sudo ./04-setup-ssl.sh
```

---

### 05-security-hardening.sh
**Purpose:** Implement security best practices

**What it does:**
- Configures Fail2Ban (brute force protection)
- Hardens SSH configuration
- Disables root login
- Disables password authentication
- Enables automatic security updates
- Secures MySQL (local only)
- Hardens kernel parameters
- Configures log rotation
- Creates security monitoring script

**Duration:** ~2 minutes

**⚠️ Critical:** Set up SSH keys BEFORE running this script!

**Usage:**
```bash
# Set up SSH keys first!
ssh-copy-id fhmaison@51.83.47.194
# Then run
sudo ./05-security-hardening.sh
```

---

### 06-update-application.sh
**Purpose:** Update deployed application (use for subsequent deployments)

**What it does:**
- Creates automatic backup (database + files)
- Enables maintenance mode
- Pulls latest code changes
- Updates dependencies
- Rebuilds frontend assets
- Runs database migrations
- Clears and rebuilds caches
- Restarts services
- Disables maintenance mode

**Duration:** ~3-5 minutes

**Usage:**
```bash
sudo ./06-update-application.sh
```

---

## 🌐 Server Configuration

### Target Environment

| Component | Version/Details |
|-----------|----------------|
| **Operating System** | Ubuntu 22.04 LTS |
| **Web Server** | Nginx (latest) |
| **PHP** | 8.3 (with OPcache) |
| **Database** | MySQL 8.0 |
| **Cache** | Redis (latest) |
| **Process Manager** | Supervisor |
| **SSL Provider** | Let's Encrypt |
| **Domain** | fhmaison.fr |
| **Server IP** | 51.83.47.194 |

### Included PHP Extensions

- php8.3-fpm
- php8.3-cli
- php8.3-mysql
- php8.3-zip
- php8.3-gd
- php8.3-mbstring
- php8.3-curl
- php8.3-xml
- php8.3-bcmath
- php8.3-intl
- php8.3-redis
- php8.3-imagick
- php8.3-opcache

---

## 🔒 Security Features

### Implemented Security Measures

✅ **HTTPS/SSL**
- Let's Encrypt SSL certificates
- Automatic renewal
- TLS 1.2 and 1.3 only
- Strong cipher suites

✅ **Web Application Security**
- Security headers (HSTS, CSP, X-Frame-Options, etc.)
- CSRF protection
- XSS protection
- SQL injection prevention (via Laravel ORM)

✅ **Server Security**
- Fail2Ban (brute force protection)
- UFW firewall configured
- SSH hardening (key-only authentication)
- Root login disabled
- Automatic security updates

✅ **Database Security**
- Local access only (no remote connections)
- Strong password generation
- Principle of least privilege
- Regular backups

✅ **Application Security**
- Environment variables for secrets
- OPcache enabled (no source code exposure)
- Proper file permissions
- Hidden .env and sensitive files

---

## 📊 Performance Optimizations

### Included Optimizations

✅ **PHP**
- OPcache enabled and tuned
- Memory limit: 512MB
- Execution time: 300s

✅ **Laravel**
- Config caching
- Route caching
- View caching
- Autoloader optimization

✅ **Nginx**
- Gzip compression
- Static asset caching (1 year)
- FastCGI caching ready
- HTTP/2 enabled

✅ **Database**
- InnoDB buffer pool optimized
- Query cache configured
- Slow query logging enabled

✅ **Caching Strategy**
- Redis for sessions
- Redis for application cache
- Redis for queue jobs

---

## 🔄 Maintenance

### Regular Tasks

**Daily:**
- Monitor application logs
- Check queue workers status
- Review security logs (Fail2Ban)

**Weekly:**
- Check disk space
- Review slow query logs
- Update application if needed

**Monthly:**
- Review and rotate logs
- Check SSL certificate expiry
- Test backup restoration
- Security audit

### Commands Reference

```bash
# View logs
tail -f /var/www/fhmaison/storage/logs/laravel.log
tail -f /var/log/nginx/fhmaison_error.log

# Restart services
sudo systemctl restart nginx
sudo systemctl restart php8.3-fpm
sudo supervisorctl restart fhmaison-worker:*

# Clear caches
cd /var/www/fhmaison
php artisan cache:clear
php artisan config:cache

# Update application
cd /root/deployment/scripts
sudo ./06-update-application.sh

# Security check
sudo /usr/local/bin/fhmaison-security-check.sh

# Backup
mysqldump -u fhmaison_user -p fhmaison_db > backup.sql
tar -czf backup.tar.gz -C /var/www/fhmaison .
```

---

## 🆘 Support & Troubleshooting

### First Steps When Issues Occur

1. **Check application logs:**
   ```bash
   tail -100 /var/www/fhmaison/storage/logs/laravel.log
   ```

2. **Check web server logs:**
   ```bash
   tail -100 /var/log/nginx/fhmaison_error.log
   ```

3. **Check service status:**
   ```bash
   systemctl status nginx
   systemctl status php8.3-fpm
   systemctl status mysql
   ```

4. **Consult troubleshooting guide:**
   See [TROUBLESHOOTING.md](TROUBLESHOOTING.md)

### Common Issues

- **502 Bad Gateway** → PHP-FPM not running
- **500 Internal Error** → Check Laravel logs
- **403 Forbidden** → Permission issues
- **Database connection failed** → Check MySQL and credentials
- **SSL not working** → Check DNS and certificate

### Getting Help

- **Documentation:** See DEPLOYMENT_GUIDE.md and TROUBLESHOOTING.md
- **Laravel Docs:** https://laravel.com/docs
- **Community:** https://laracasts.com/discuss
- **Stack Overflow:** Tag with `laravel`, `nginx`, `php`

---

## ✅ Deployment Checklist

Use this checklist for deployment:

**Pre-Deployment:**
- [ ] VPS provisioned and accessible
- [ ] DNS records configured and propagated
- [ ] SSH keys generated and tested
- [ ] Application code ready
- [ ] Database credentials secured
- [ ] SMTP credentials ready (if using email)
- [ ] Stripe keys ready (if using payments)

**Deployment:**
- [ ] Run 01-setup-server.sh
- [ ] Run 02-setup-database.sh
- [ ] Upload application code
- [ ] Run 03-deploy-application.sh
- [ ] Configure .env file
- [ ] Run 04-setup-ssl.sh
- [ ] Set up SSH keys
- [ ] Run 05-security-hardening.sh

**Post-Deployment:**
- [ ] Create admin user
- [ ] Test application functionality
- [ ] Configure email settings
- [ ] Add Stripe keys
- [ ] Set up monitoring
- [ ] Test SSL certificate
- [ ] Configure backups
- [ ] Document any customizations

**Verification:**
- [ ] Site accessible at https://fhmaison.fr
- [ ] SSL certificate valid (A+ rating)
- [ ] All services running
- [ ] Queue workers processing jobs
- [ ] Emails sending correctly
- [ ] Payments working (if applicable)
- [ ] No errors in logs
- [ ] Backups configured and tested

---

## 📝 Notes

### Important Files

- **Application:** `/var/www/fhmaison`
- **Nginx Config:** `/etc/nginx/sites-available/fhmaison`
- **Environment:** `/var/www/fhmaison/.env`
- **DB Credentials:** `/root/.fhmaison_db_credentials`
- **SSL Certs:** `/etc/letsencrypt/live/fhmaison.fr/`
- **Logs:** `/var/www/fhmaison/storage/logs/`

### Default Credentials

**Database:**
- Database: `fhmaison_db`
- User: `fhmaison_user`
- Password: Generated during setup (see `/root/.fhmaison_db_credentials`)

**System User:**
- Username: `fhmaison`
- Home: `/home/fhmaison`
- Group: `www-data` (secondary)

### Ports

- **80:** HTTP (redirects to HTTPS)
- **443:** HTTPS (main application)
- **3306:** MySQL (localhost only)
- **6379:** Redis (localhost only)
- **22:** SSH

---

## 🔄 Version History

**Version 1.0** (January 2026)
- Initial production deployment package
- Complete automation scripts
- Comprehensive documentation
- Security hardening included
- Performance optimizations

---

## 📄 License

This deployment package is part of the FH Maison project.

---

## 🎉 Conclusion

This deployment package provides a **professional**, **secure**, and **scalable** solution for deploying your Laravel application to production.

**Key Benefits:**
- ✅ Automated setup (minimal manual configuration)
- ✅ Production-ready (security + performance)
- ✅ Well-documented (guides for every scenario)
- ✅ Maintainable (update scripts included)
- ✅ Secure (follows industry best practices)

**Your application will be:**
- Fast (OPcache, Redis, optimized Nginx)
- Secure (SSL, Fail2Ban, hardened SSH)
- Reliable (Supervisor, automatic restarts)
- Maintainable (clear documentation, update scripts)

---

**Need help? Start with [QUICK_START.md](QUICK_START.md) for a 30-minute deployment!**

**Questions? Check [TROUBLESHOOTING.md](TROUBLESHOOTING.md) for common issues.**

**Want details? Read [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) for everything.**

---

**Happy Deploying! 🚀**
