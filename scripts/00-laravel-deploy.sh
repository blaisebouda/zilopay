#!/usr/bin/env bash
echo "Running composer"
composer global require hirak/prestissimo
composer install --no-dev --working-dir=/var/www/html

echo "generating application key..."
php artisan key:generate --show

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

# Build your React assets for production
echo "Building frontend assets..."
npm install
npm run build

# Start the Inertia SSR server in the background
echo "Starting Inertia SSR..."
php artisan inertia:start-ssr &

echo "All done!"