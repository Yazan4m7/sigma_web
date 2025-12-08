# SIGMA Cloud Deployment Guide

## Quick Start

### Option 1: Git-based Deployment (Recommended)
If your cloud server has Git repository access:

```bash
./deploy-git.sh
```

### Option 2: Direct File Sync
If you want to sync files directly via SSH/rsync:

```bash
./deploy-to-cloud.sh
```

---

## Configuration

Before running the scripts, update these values in the deployment script:

### In `deploy-to-cloud.sh` or `deploy-git.sh`:
```bash
CLOUD_USER="root"              # Your SSH username
CLOUD_HOST="161.35.46.18"      # Your server IP or domain
CLOUD_PATH="/var/www/sigma"    # Application path on server
SSH_KEY=""                      # Optional: SSH key path
```

---

## What Gets Deployed

### Recent Changes:
1. **Case History Logging Fixes**
   - Fixed 2-phase stages (Design, Finishing, QC, Pressing)
   - Fixed 3-phase stages (Milling, 3D Printing, Sintering)
   - Removed duplicate log entries
   - Fixed incorrect integer stage logs

2. **User Dropdown Enhancement**
   - Added employee name display
   - Added professional styling
   - Shows user role

3. **Laravel Logger Disabled**
   - Removed legacy logger package errors
   - Created migration to drop `laravel_logger_activity` table

---

## Deployment Process

Both scripts perform these steps:

1. ✓ Test connection to server
2. ✓ Create backup (auto-backup before deployment)
3. ✓ Enable maintenance mode
4. ✓ Sync files / Pull from Git
5. ✓ Install dependencies (`composer install`)
6. ✓ Run database migrations
7. ✓ Clear all caches
8. ✓ Optimize caches
9. ✓ Disable maintenance mode

---

## Manual Deployment (If Scripts Don't Work)

### 1. SSH into your server:
```bash
ssh root@161.35.46.18
cd /var/www/sigma
```

### 2. Enable maintenance mode:
```bash
php artisan down
```

### 3. Pull changes (if using Git):
```bash
git pull origin master
```

### 4. Update dependencies:
```bash
composer install --no-dev --optimize-autoloader
```

### 5. Run migrations:
```bash
php artisan migrate --force
```

### 6. Clear caches:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
rm -rf bootstrap/cache/*.php
```

### 7. Optimize:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 8. Disable maintenance mode:
```bash
php artisan up
```

---

## Troubleshooting

### Permission Issues
```bash
# On the server, fix permissions:
sudo chown -R www-data:www-data /var/www/sigma
sudo chmod -R 775 /var/www/sigma/storage
sudo chmod -R 775 /var/www/sigma/bootstrap/cache
```

### Database Connection Issues
Make sure `.env` on the server has correct database credentials:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sigma
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

### Laravel Logger Error Persists
Manually drop the table on the server:
```bash
php artisan tinker --execute="Schema::dropIfExists('laravel_logger_activity');"
```

Or run the migration:
```bash
php artisan migrate --force
```

---

## Rollback (If Something Goes Wrong)

The deployment script creates automatic backups. To rollback:

```bash
ssh root@161.35.46.18
cd /var/www
# List backups
ls -lh backups/

# Restore a backup
tar -xzf backups/sigma_backup_YYYYMMDD_HHMMSS.tar.gz -C sigma/
cd sigma
php artisan config:clear
php artisan cache:clear
php artisan up
```

---

## Security Notes

- Always test deployments on staging first
- Backups are created automatically before each deployment
- Maintenance mode is enabled during deployment
- Use SSH keys instead of passwords for better security

---

## Support

For issues, check:
- Laravel logs: `/var/www/sigma/storage/logs/laravel.log`
- Nginx/Apache error logs
- PHP-FPM error logs
