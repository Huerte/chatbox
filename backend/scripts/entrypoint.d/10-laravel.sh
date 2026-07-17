#!/bin/bash
chown -R application:application /app/storage /app/bootstrap/cache
chmod -R 775 /app/storage /app/bootstrap/cache

php artisan config:cache
php artisan route:cache
php artisan storage:link
php artisan migrate --force