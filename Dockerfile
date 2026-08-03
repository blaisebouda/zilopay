# Use a base image with Nginx and PHP
FROM richarvey/nginx-php-fpm:3.1.6

# Install Node.js in the final image to run Inertia SSR
RUN cd ~ \
    && curl -sL https://deb.nodesource.com/setup_18.x -o nodesource_setup.sh \
    && bash nodesource_setup.sh \
    && apt install -y nodejs \
    && cd /var/www/html

# Copy your application code into the container
COPY . .

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Make sure the start script is executable and run it
CMD ["/start.sh"]