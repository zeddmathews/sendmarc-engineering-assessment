#!/bin/bash
set -e

cd /var/www/app

# Ensure .env exists
if [ ! -f ".env" ]; then
    echo "Copying .env.example to .env..."
    cp .env.example .env

fi
# Fix permissions
mkdir -p storage/framework/views storage/framework/cache
chown -R www-data:www-data storage bootstrap/cache

# Laravel cache clear & cache rebuild
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start php-fpm in foreground
php-fpm -F

#docker compose build app
#docker compose up -d app