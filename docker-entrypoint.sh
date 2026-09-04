#!/bin/bash
set -e

echo "=== Starting FundMe Angola Container ==="

# Create all required storage and cache directories
mkdir -p /var/www/html/storage/app/public \
         /var/www/html/storage/app/private \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

# Set strict permissions for Apache www-data user
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Clear stale caches
echo "Clearing application cache..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true
php artisan route:clear || true

# Generate APP_KEY if missing or empty
if [ -z "$APP_KEY" ]; then
    echo "Generating Laravel APP_KEY..."
    php artisan key:generate --force
fi

# Run package discovery
echo "Discovering packages..."
php artisan package:discover --ansi || true

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force || echo "Migration step completed with warnings"

echo "=== FundMe Angola is ready! Starting Apache... ==="
exec apache2-foreground
