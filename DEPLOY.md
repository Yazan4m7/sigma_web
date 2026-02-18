# SIGMA Quick Deployment

**Server:** root@161.35.46.18
**Password:** sigma$S1lab
**Path:** /var/www/sigma

---

## ⚡ ONE-CLICK DEPLOY

### Git Bash / Linux / Mac:
```bash
bash deploy.sh
```
Enter password when prompted: `sigma$S1lab`

### Windows (with PuTTY):
```cmd
deploy.bat
```
Double-click or run from CMD (password hardcoded)

---

## 📋 What Gets Deployed

**Synced to server:**
- ✓ `app/` - Controllers, Models, Middleware
- ✓ `resources/` - Views (Blade), CSS, JS
- ✓ `routes/` - Web, API routes
- ✓ `config/` - Configuration files
- ✓ `public/` - Assets, images

**Skipped (already on server):**
- ✗ `vendor/` - Composer packages
- ✗ `node_modules/` - NPM packages
- ✗ `.git`, `.env`, `storage/logs`

**Automatic cache clearing:**
- `php artisan config:clear`
- `php artisan cache:clear`
- `php artisan view:clear`
- `php artisan route:clear`

---

## ⚙️ Process

1. **Syncs files** - Only app, resources, routes, config, public
2. **Clears caches** - Ensures changes take effect immediately

**No composer install, no npm install, no migrations** - Fast deploy!

---

## 🔍 Verify Deployment

```bash
# Check Laravel logs
ssh root@161.35.46.18
tail -f /var/www/sigma/storage/logs/laravel.log
```

---

## 🆘 If Something Breaks

### Quick Cache Clear:
```bash
ssh root@161.35.46.18 "cd /var/www/sigma && php artisan cache:clear && php artisan config:clear"
```

### View Live Site:
Just refresh your browser after deploy completes.

---

## 📦 When You Need Full Deploy

If you modified `composer.json` or `package.json`:

```bash
ssh root@161.35.46.18
cd /var/www/sigma
composer install --no-dev --optimize-autoloader
npm install --production
php artisan cache:clear
```

If you added database migrations:

```bash
ssh root@161.35.46.18
cd /var/www/sigma
php artisan migrate --force
```

---

## ✅ Typical Use Cases

**Modified a controller:**
```bash
bash deploy.sh
```

**Updated a view (Blade file):**
```bash
bash deploy.sh
```

**Changed routes:**
```bash
bash deploy.sh
```

**Modified CSS/JS in resources:**
```bash
bash deploy.sh
```

**Changed config file:**
```bash
bash deploy.sh
```

---

## 📊 Deploy Time

Typical deploy: **30-60 seconds**
- File sync: ~20 seconds
- Cache clear: ~5 seconds

Much faster than full composer/npm install!

---

## 🎯 Summary

**One command:**
```bash
bash deploy.sh
```

**One password:** sigma$S1lab

**Two steps:**
1. Sync changed files
2. Clear caches

**Done!**
