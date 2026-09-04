#!/bin/bash

echo "=== Starting FundMe Angola Container ==="

# Force APP_DEBUG=true so any internal error trace is visible
export APP_DEBUG=true

# Create all required storage and cache directories
mkdir -p /var/www/html/storage/app/public \
         /var/www/html/storage/app/private \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

# Always ensure a fresh .env file exists in container from .env.example
cp -f /var/www/html/.env.example /var/www/html/.env

# Set permissions 777 for storage and bootstrap/cache to avoid permission errors
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/.env || true
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/.env || true

# Clear stale caches
echo "Clearing application cache..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true
php artisan route:clear || true

# Check and force generate valid base64 APP_KEY if missing or invalid
if [[ "$APP_KEY" != base64:* ]]; then
    echo "Generating valid Laravel APP_KEY..."
    php artisan key:generate --force || true
fi

# Run package discovery
echo "Discovering packages..."
php artisan package:discover --ansi || true

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force || echo "Migration step completed with warnings"

echo "=== FundMe Angola is ready! Starting Apache... ==="
exec apache2-foreground
