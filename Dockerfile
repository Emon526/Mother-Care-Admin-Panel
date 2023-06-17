FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
        libzip-dev \
        zip \
        unzip \
        git \
        curl \
        libssl-dev \
        openssl \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions and Composer
RUN docker-php-ext-install pdo_mysql zip \
    && pecl install mongodb && docker-php-ext-enable mongodb \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /var/www/html

# Copy only the necessary files for installation
COPY composer.json composer.lock ./

# Install PHP dependencies without dev dependencies
RUN composer install --no-scripts --no-autoloader --no-dev --ignore-platform-reqs \
    && composer dump-autoload --optimize --classmap-authoritative

# Copy the rest of the application code
COPY . .

# Set permissions for specific directories
RUN chown -R www-data:www-data \
        storage \
        bootstrap/cache \
        public

# Optimize the Laravel application
RUN php artisan optimize && \
    php artisan config:cache && \
    php artisan route:cache

# Cleanup unnecessary files
RUN rm -rf \
    tests \
    .git \
    .env.example \
    docker-compose.yml

# Expose the appropriate port
EXPOSE 443

# Set the command to run the application
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=443"]
