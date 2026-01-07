# Deployment Fixes - January 7, 2026

## Issues Fixed

### 1. ✅ Price Filter Server Error
- **Problem**: Price sorting (low to high, high to low) caused server error due to duplicate joins
- **Solution**: Fixed ProductController to use LEFT JOIN with MIN/MAX aggregation and GROUP BY
- **Files**: `app/Http/Controllers/Frontend/ProductController.php`

### 2. ✅ Categories Not Loading in Navigation
- **Problem**: Categories dropdown showed "No categories available"
- **Solution**: Removed `show_in_menu` filter requirement, now only checks `is_active`
- **Files**: `resources/views/components/frontend/navigation.blade.php`

### 3. ✅ Checkout Success Page
- **Problem**: Help section not needed
- **Solution**: Removed "Need Help?" section from checkout success page
- **Files**: `resources/views/frontend/checkout/success.blade.php`

### 4. ✅ Order Details in Admin
- **Problem**: Tracking number and notes not displayed
- **Solution**: Added tracking information card and order notes section
- **Files**: `resources/views/admin/orders/show.blade.php`

### 5. ⚠️ 413 Request Entity Too Large (Nginx Issue)
- **Problem**: Cannot upload large category images
- **Solution Required**: Update nginx configuration on server

## Server Deployment Steps

### Step 1: Connect to Server
```bash
ssh ubuntu@fhmaison.fr
```

### Step 2: Pull Latest Code
```bash
cd /var/www/fhmaison
git pull origin master
```

### Step 3: Clear Caches
```bash
php artisan config:clear
php artisan view:clear
php artisan cache:clear
php artisan route:clear
```

### Step 4: Fix Nginx 413 Error
```bash
# Edit nginx configuration
sudo nano /etc/nginx/sites-available/fhmaison.conf

# Find the line with client_max_body_size and ensure it's set to 100M:
# client_max_body_size 100M;

# Also add to http block in main nginx config if not already there:
sudo nano /etc/nginx/nginx.conf

# Add inside http { } block:
# client_max_body_size 100M;

# Test nginx configuration
sudo nginx -t

# Reload nginx
sudo systemctl reload nginx
```

### Step 5: Update PHP Upload Limits (if needed)
```bash
# Find your PHP version
php -v

# Edit PHP-FPM config (adjust version if different)
sudo nano /etc/php/8.2/fpm/php.ini

# Update these values:
# upload_max_filesize = 100M
# post_max_size = 100M
# max_execution_time = 300

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

### Step 6: Verify Changes
- Visit https://fhmaison.fr
- Test category image upload in admin panel
- Test categories dropdown in navigation
- Test price filters (low to high, high to low)
- View an order in admin panel to see tracking info

## Testing Checklist

- [ ] Categories appear in navigation dropdown on hover
- [ ] Price sorting (low to high, high to low) works without error
- [ ] Category image upload accepts files up to 100MB
- [ ] Order show page displays tracking number (if exists)
- [ ] Order show page displays order notes (if exists)
- [ ] Checkout success page does not show help section

## Rollback (if needed)
```bash
cd /var/www/fhmaison
git reset --hard HEAD~1
php artisan cache:clear
```
