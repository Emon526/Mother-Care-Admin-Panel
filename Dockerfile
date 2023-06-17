# Use a lightweight base image for production
FROM php:8.2-cli-alpine

# Set the working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apk update && apk add --no-cache \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    openssl

# Install PHP extensions and composer
RUN docker-php-ext-install pdo_mysql zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy only the necessary files for installation
COPY composer.json composer.lock ./

# Install PHP dependencies without dev dependencies
RUN composer install --no-scripts --no-autoloader --no-dev --ignore-platform-reqs

# Copy the rest of the application code
COPY . .

# Set permissions for specific directories
RUN chown -R www-data:www-data \
        storage \
        bootstrap/cache \
        public

# Optimize the autoloader and clear cached files
RUN composer dump-autoload --optimize && \
    php artisan optimize && \
    php artisan config:cache

# Remove development files and directories
RUN rm -rf \
    tests \
    .git \
    .env.example \
    docker-compose.yml

# Expose the appropriate port
EXPOSE 443

# Set the command to run the application
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=443"]
