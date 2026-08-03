# ---- Stage 1: build frontend assets (Vite + React + Inertia) ----
FROM node:22-alpine AS frontend

WORKDIR /app
RUN corepack enable && corepack prepare pnpm@9 --activate
COPY package.json pnpm-lock.yaml ./
RUN pnpm install --frozen-lockfile
COPY . .
RUN pnpm run build

# ---- Stage 2: PHP application ----
FROM php:8.3-apache AS app

# System deps + PHP extensions Laravel typically needs
RUN apt-get update && apt-get install -y \
        git curl unzip zip \
        libpng-dev libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# App source
COPY . .

# Built frontend assets from stage 1
COPY --from=frontend /app/public/build ./public/build

# PHP deps (no dev deps, optimized autoloader)
RUN composer install --optimize-autoloader --no-dev --no-interaction --no-progress

# Permissions Laravel needs to write to
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Point Apache's document root at Laravel's /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/sites-available/*.conf \
        /etc/apache2/apache2.conf \
        /etc/apache2/conf-available/*.conf

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80
ENTRYPOINT ["/entrypoint.sh"]