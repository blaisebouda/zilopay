# Multi-stage build for better optimization
FROM richarvey/nginx-php-fpm:3.1.6

# Install Node.js (Alpine version)
RUN apk add --no-cache nodejs npm

# Copy application code
COPY . .

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Build frontend assets
RUN npm install && npm run build

# Set environment variables
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

# Create custom start script
RUN echo '#!/bin/sh\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
php artisan migrate --force\n\
php artisan inertia:start-ssr &\n\
/start.sh' > /custom-start.sh && chmod +x /custom-start.sh

CMD ["/custom-start.sh"]