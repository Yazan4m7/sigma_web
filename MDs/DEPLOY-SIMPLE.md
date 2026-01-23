# Simple Deployment Guide

## Files to Upload

Upload these modified files to your cloud server at `/var/www/staging/`:

### 1. **View Files:**
```
resources/views/cases/viewOnly.blade.php
resources/views/layouts/navbars/navs/auth.blade.php
```

### 2. **Controller Files:**
```
app/Http/Controllers/OperationsUpgrade.php
```

### 3. **Migration File:**
```
database/migrations/2025_10_31_193132_drop_laravel_logger_activity_table.php
```

---

## SQL Queries to Run

### On your cloud database, run these queries:

```sql
-- Drop the legacy logger table
DROP TABLE IF EXISTS `laravel_logger_activity`;

-- Verify it's gone
SHOW TABLES LIKE '%logger%';
```

---

## Commands to Run on Server

After uploading files, SSH into your server and run:

```bash
cd /var/www/staging

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
rm -rf bootstrap/cache/*.php

# Fix permissions (IMPORTANT!)
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Run migration (optional, drops logger table)
php artisan migrate --force
```

---

## Quick Copy-Paste Commands

```bash
# Fix permissions
sudo chown -R www-data:www-data /var/www/staging/storage
sudo chown -R www-data:www-data /var/www/staging/bootstrap/cache
sudo chmod -R 775 /var/www/staging/storage
sudo chmod -R 775 /var/www/staging/bootstrap/cache

# Clear caches
cd /var/www/staging
php artisan config:clear && php artisan cache:clear && php artisan view:clear && rm -rf bootstrap/cache/*.php
```

That's it! Your changes are deployed.
