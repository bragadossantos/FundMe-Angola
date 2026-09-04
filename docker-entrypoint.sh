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

# Ensure .env file exists in container for Artisan commands
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Set strict permissions for Apache www-data user
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/.env
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

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
