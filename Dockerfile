# --- Stage 1: Build Frontend Assets via pnpm ---
FROM node:20-alpine AS frontend-builder
WORKDIR /app

# Enable corepack to get pnpm automatically
RUN corepack enable && corepack prepare pnpm@latest --activate

# Copy only lockfile and package configuration for caching layers
COPY package.json pnpm-lock.yaml* ./
RUN pnpm install --frozen-lockfile

# Copy the rest of the application files and compile
COPY . .
RUN pnpm run build

# --- Stage 2: Serve the Application with PHP 8.3 ---
FROM php:8.3-fpm-alpine

# Install system dependencies and database drivers
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    postgresql-dev

# Install PHP extensions required for Laravel 13
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Grab the latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application backend files
COPY . .

# Copy compiled production assets from Stage 1 
COPY --from=frontend-builder /app/public/build ./public/build

# Install production PHP dependencies
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Fix write permissions for the container environment
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Nginx and Supervisor configs from your local project
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
