# Base image
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        git \
        curl \
        libssl-dev \
        openssl && \
    rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip opcache && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb opcache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - && \
    apt-get install -y nodejs && \
    rm -rf /var/lib/apt/lists/*

# Copy source code
WORKDIR /var/www/html
COPY . .

# Install dependencies
RUN composer install --no-dev --no-scripts --no-autoloader --ignore-platform-reqs && \
    npm install --only=production && \
    npm run prod && \
    composer dump-autoload --no-dev --optimize

# Set permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache && \
    chmod 777 -R storage/logs

# Copy NGINX configuration file
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Expose port 80
EXPOSE 80

# Start NGINX and PHP-FPM
CMD service php8.2-fpm start && nginx -g "daemon off;"
