#!/bin/bash

echo "=== Starting FundMe Angola Container ==="

# Create all required storage, logs, and database directories
mkdir -p /var/www/html/storage/app/public \
         /var/www/html/storage/app/private \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache \
         /var/www/html/database

touch /var/www/html/storage/logs/laravel.log
touch /var/www/html/database/database.sqlite

# Only seed .env from the template if it doesn't already exist — never clobber
# a real .env that was placed in the container. In production the values that
# actually matter (DB_URL, APP_KEY, APP_DEBUG, ...) come from real environment
# variables injected by the hosting platform (see render.yaml), which always
# take precedence over the .env file regardless.
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Grant www-data ownership of writable app directories. Group-writable (775)
# is enough for Apache/PHP running as www-data — the previous 777 made these
# paths world-writable, which is unnecessary inside the container.
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database /var/www/html/.env /var/www/html/storage/logs/laravel.log || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database || true
chmod 640 /var/www/html/.env /var/www/html/storage/logs/laravel.log || true

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

# Re-create the public storage symlink (public/storage -> storage/app/public)
# on every boot, since the container filesystem is ephemeral and any symlink
# created in a previous run is gone. Without this, every uploaded/public image
# (e.g. campaign featured images) 404s.
echo "Linking public storage..."
php artisan storage:link --force || true

echo "=== FundMe Angola is ready! Starting Apache... ==="
exec apache2-foreground
