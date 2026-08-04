#!/usr/bin/env bash
set -e

# Configure Apache to use Render's dynamic PORT
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Update DocumentRoot to Laravel's public folder
sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

# Allow .htaccess overrides
sed -ri -e 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Create storage symlink
php artisan storage:link --quiet || true

# Run database migrations
php artisan migrate --force

# Seed the database (optional)
php artisan db:seed --force

# Cache Laravel config/routes/views for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache in foreground
exec apache2-foreground