#!/bin/bash
# Clear all Laravel caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
php artisan clear-compiled
php artisan optimize:clear

# Clear session files
rm -rf storage/framework/sessions/*
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/views/*

echo "All caches cleared successfully"