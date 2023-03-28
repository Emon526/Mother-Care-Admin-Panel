FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set composer options and give superuser access to composer
ENV COMPOSER_OPTIONS="--verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader"
ENV COMPOSER_ALLOW_SUPERUSER=1

# Increase composer process timeout
RUN composer config --global process-timeout 600

# Install prestissimo and Laravel installer globally
RUN composer global require hirak/prestissimo && \
    composer global require "laravel/installer"

# Set global composer configuration options
RUN composer config --global github-protocols https ssh && \
    composer config --global discard-changes true && \
    composer config --global prefer-dist true && \
    composer config --global sort-packages true && \
    composer config --global optimize-autoloader true && \
    composer config --global --list && \
    composer clear-cache && \
    composer self-update --2 && \
    composer global update

# Copy application files
COPY . .

# Install application dependencies
RUN composer install $COMPOSER_OPTIONS

# Copy environment file
COPY .env.example .env

# Generate application key
RUN php artisan key:generate

# Expose port 9000 and start PHP-FPM server
EXPOSE 9000
CMD ["php-fpm"]
