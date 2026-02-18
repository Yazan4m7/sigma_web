# Deployment Guide

**Server:** root@161.35.46.18
**Path:** /var/www/sigma

---

## Quick Deploy (Direct File Sync - NO GIT NEEDED)

```bash
bash deploy-to-cloud.sh
```

**What it does:**
1. Tests SSH connection
2. Creates backup on server
3. Puts site in maintenance mode
4. Syncs files via rsync (excludes .git)
5. Installs dependencies
6. Runs migrations
7. Clears & optimizes caches
8. Brings site back online

**Use when:** Git repository is not available or you want to sync files directly

---

## Manual Deployment Steps

If scripts don't work, deploy manually:

### 1. Commit & Push Changes
```bash
git add .
git commit -m "Removed unused Vue.js and npm packages"
git push origin master
```

### 2. SSH to Server
```bash
ssh root@161.35.46.18
```

### 3. Deploy on Server
```bash
cd /var/www/sigma

# Maintenance mode
php artisan down

# Pull changes
git pull origin master

# Update dependencies (IMPORTANT after package.json changes)
composer install --no-dev --optimize-autoloader
npm install --production

# Database
php artisan migrate --force

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
rm -rf bootstrap/cache/*.php

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Back online
php artisan up
```

---

## IMPORTANT: After This Cleanup

Since you removed Vue.js and npm packages, you need to:

```bash
# On the server, clean npm packages
cd /var/www/sigma
npm install   # This will remove unused Vue packages

# Optional: Clean node_modules completely
rm -rf node_modules
npm install
```

---

## Verification

After deployment, verify:

1. ✓ Site loads correctly
2. ✓ No JavaScript errors in browser console
3. ✓ Application functionality works
4. ✓ Check Laravel logs: `tail -f storage/logs/laravel.log`

---

## Rollback (If Issues)

```bash
ssh root@161.35.46.18
cd /var/www/sigma

# Revert to previous commit
git log --oneline  # Find commit hash
git reset --hard <previous-commit-hash>

# Or restore from backup
cd ..
tar -xzf backups/sigma_backup_*.tar.gz -C sigma/

# Clear caches and bring back online
cd sigma
php artisan config:clear
php artisan cache:clear
php artisan up
```

---

## PhpStorm Deployment

You have PhpStorm configured (see `.idea/deployment.xml`):
- Server: "Staging"
- Path: `/var/www/staging`
- Auto-upload: Disabled

**To use:**
1. Tools → Deployment → Upload to Staging
2. Or: Right-click file → Deployment → Upload to Staging

---

## Summary

**For servers WITHOUT git (your case):**
```bash
bash deploy-to-cloud.sh
```

**After deployment, verify on server:**
```bash
ssh root@161.35.46.18
cd /var/www/sigma
npm install  # Update node_modules after package.json changes
php artisan config:clear
php artisan cache:clear
php artisan up  # Ensure site is live
```
