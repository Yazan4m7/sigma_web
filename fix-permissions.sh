#!/bin/bash
# Run this on your cloud server to fix permissions

cd /var/www/staging

# Fix ownership
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache

# Fix permissions
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
rm -rf bootstrap/cache/*.php

echo "Permissions fixed!"
