FROM php:8.4-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PHP extensions and system deps
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_pgsql zip intl \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js 22.x and enable pnpm via corepack
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && corepack enable \
    && corepack prepare pnpm@latest --activate

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy and install PHP dependencies WITHOUT autoloader (layer caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-autoloader --no-scripts --no-interaction

# Copy and install Node dependencies (layer caching)
COPY package.json pnpm-lock.yaml ./
RUN pnpm install --frozen-lockfile

# Copy ALL application code
COPY . .

# Now generate autoloader (app/helpers.php exists now)
RUN composer dump-autoload --optimize

# Build Vite assets (Inertia + React)
RUN pnpm run build

# Fix permissions for Laravel storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy startup script
COPY scripts/00-laravel-deploy.sh /usr/local/bin/laravel-deploy.sh
RUN chmod +x /usr/local/bin/laravel-deploy.sh

CMD ["/usr/local/bin/laravel-deploy.sh"]