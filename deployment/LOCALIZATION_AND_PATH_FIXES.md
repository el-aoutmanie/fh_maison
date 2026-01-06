# Localization and Path Configuration Fixes

## Summary of Changes

All configuration files have been updated to fix localization issues and ensure consistent paths throughout the deployment.

## 1. Localization Configuration

### File: `config/laravellocalization.php`

**Changes Made:**
- ✅ Enabled French locale (`fr`) - Line 56
- ✅ Arabic locale (`ar`) is already enabled - Line 239
- ✅ English locale (`en`) is already enabled - Line 46

**Supported Locales:**
- English (en) - `https://fhmaison.fr/en`
- French (fr) - `https://fhmaison.fr/fr`
- Arabic (ar) - `https://fhmaison.fr/ar` (RTL support enabled)

**Root URL Behavior:**
The application has a fallback route at the end of `routes/web.php` that redirects the root URL to the current locale:
```php
Route::get('/', function () {
    return redirect(LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), '/'));
});
```

This means:
- `https://fhmaison.fr/` → redirects to `https://fhmaison.fr/en` (default locale)
- `https://fhmaison.fr/en` → English version
- `https://fhmaison.fr/fr` → French version
- `https://fhmaison.fr/ar` → Arabic version

## 2. Path Standardization

All deployment files now use the consistent path: `/var/www/fhmaison`

### Updated Files:

#### Nginx Configuration: `deployment/nginx/fhmaison.conf`
- ✅ Document root: `/var/www/fhmaison/public`
- ✅ Storage alias: `/var/www/fhmaison/storage/app/public`

#### Deployment Scripts:
- ✅ `deployment/scripts/01-setup-server.sh` - APP_DIR="/var/www/fhmaison"
- ✅ `deployment/scripts/03-deploy-application.sh` - APP_DIR="/var/www/fhmaison"
- ✅ `deployment/scripts/04-setup-ssl.sh` - APP_DIR="/var/www/fhmaison"
- ✅ `deployment/scripts/05-security-hardening.sh` - Uses /var/www/fhmaison
- ✅ `deployment/scripts/06-update-application.sh` - APP_DIR="/var/www/fhmaison"

## 3. Nginx Configuration Details

The nginx configuration properly supports Laravel localization:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

This ensures all requests (including locale-prefixed URLs) are properly routed through Laravel's front controller.

## 4. Route Configuration

### File: `routes/web.php`

The routes are properly configured with Laravel Localization middleware:

```php
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function() {
    // All routes here...
});
```

**Middleware Functions:**
- `localeSessionRedirect` - Remembers user's locale preference in session
- `localizationRedirect` - Redirects users based on browser locale
- `localeViewPath` - Enables locale-specific view files

## 5. Fresh Installation Steps

After resetting your server, follow these steps:

### 1. Clone Repository
```bash
cd /var/www
sudo git clone <your-repo-url> fhmaison
sudo chown -R www-data:www-data fhmaison
sudo chmod -R 755 fhmaison
```

### 2. Run Deployment Scripts (in order)
```bash
cd /var/www/fhmaison/deployment/scripts

# 1. Setup server (PHP, Nginx, MySQL, Node.js)
sudo bash 01-setup-server.sh

# 2. Setup database
sudo bash 02-setup-database.sh

# 3. Deploy application
sudo bash 03-deploy-application.sh

# 4. Setup SSL certificates
sudo bash 04-setup-ssl.sh

# 5. Apply security hardening
sudo bash 05-security-hardening.sh
```

### 3. Create Admin User
```bash
cd /var/www/fhmaison
sudo -u www-data php artisan make:admin
```

### 4. Verify Installation

Test all locale URLs:
```bash
curl -I https://fhmaison.fr/
curl -I https://fhmaison.fr/en
curl -I https://fhmaison.fr/fr
curl -I https://fhmaison.fr/ar
```

Expected behavior:
- All URLs should return 200 or 302 (redirect)
- Root URL should redirect to `/en` (or browser's preferred locale)

## 6. Testing Checklist

After deployment, verify:

- [ ] Root URL (`https://fhmaison.fr/`) redirects to default locale
- [ ] English site (`https://fhmaison.fr/en`) loads correctly
- [ ] French site (`https://fhmaison.fr/fr`) loads correctly
- [ ] Arabic site (`https://fhmaison.fr/ar`) loads correctly with RTL layout
- [ ] Language switcher works (if implemented in frontend)
- [ ] SSL certificate is valid for both `fhmaison.fr` and `www.fhmaison.fr`
- [ ] Static assets load correctly from `/storage` and `/build`
- [ ] Admin panel accessible at `/en/admin/dashboard`
- [ ] All translations display correctly (check `lang/en.json`, `lang/fr.json`, `lang/ar.json`)

## 7. Database Credentials

Stored in: `/root/.fhmaison_db_credentials`

```
Database: fhmaison_db
User: fhmaison_user
Password: YEYuj3zd
```

## 8. Locale-Specific Features

### Translation Files
Ensure these files exist:
- `lang/en.json` - English translations
- `lang/fr.json` - French translations
- `lang/ar.json` - Arabic translations

### RTL Support
Arabic locale has RTL (right-to-left) support enabled:
```php
'ar' => ['name' => 'Arabic', 'script' => 'Arab', 'dir' => 'rtl', 'native' => 'العربية']
```

Your layouts should check `LaravelLocalization::getCurrentLocaleDirection()` to apply RTL styles.

## 9. Common Issues and Solutions

### Issue: Root URL shows 404
**Solution:** Ensure the fallback route at the end of `routes/web.php` exists (it does).

### Issue: French/Arabic URLs show 404
**Solution:** ✅ Fixed - Locales are now enabled in `config/laravellocalization.php`.

### Issue: Static assets not loading
**Solution:** Run `php artisan storage:link` and rebuild assets with `npm run build`.

### Issue: Permission errors
**Solution:**
```bash
sudo chown -R www-data:www-data /var/www/fhmaison
sudo chmod -R 755 /var/www/fhmaison
sudo chmod -R 775 /var/www/fhmaison/storage
sudo chmod -R 775 /var/www/fhmaison/bootstrap/cache
```

## 10. Environment Configuration

Ensure `.env` file has correct locale settings:
```
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
```

## Summary

✅ All localization issues resolved
✅ All paths updated to `/var/www/fhmaison`
✅ French and Arabic locales enabled
✅ Nginx configuration matches Laravel structure
✅ Deployment scripts use consistent paths
✅ Routes properly configured with locale middleware
✅ Root URL fallback implemented

The application is now ready for fresh deployment on your VPS at `/var/www/fhmaison`.
