#!/bin/bash
set -e

# Cache config/routes/views for production performance.
# (Safe to run every boot; Laravel rebuilds the cache files.)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run pending migrations automatically on deploy.
# Remove this line if you'd rather run migrations manually via Render's shell.
php artisan migrate --force

exec apache2-foreground