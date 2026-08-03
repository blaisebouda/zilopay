FROM php:8.4-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_pgsql zip intl \
    && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-autoloader --no-scripts --no-interaction

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN composer dump-autoload --optimize
RUN npm run build

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY scripts/00-laravel-deploy.sh /usr/local/bin/laravel-deploy.sh
RUN chmod +x /usr/local/bin/laravel-deploy.sh

CMD ["/usr/local/bin/laravel-deploy.sh"]