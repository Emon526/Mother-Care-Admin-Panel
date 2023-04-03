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
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs

# Copy source code
WORKDIR /var/www/html
COPY . .

# # Install dependencies
# # RUN composer install --no-dev --no-scripts --no-autoloader --ignore-platform-reqs
# RUN composer install --no-scripts --no-autoloader --ignore-platform-reqs

# # Set ownership of public/build directory to www-data
# RUN chown -R www-data:www-data /var/www/html/public/build

# # RUN npm install --only=production && npm run build
# RUN npm install && npm run build 

# RUN composer dump-autoload --no-dev --optimize

# # Set permissions
# RUN chmod -R 775 storage bootstrap/cache && \
#     chmod 777 -R storage/logs




#######


RUN chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
        /var/www/html/public

RUN composer install --no-scripts --no-autoloader --ignore-platform-reqs

RUN composer dump-autoload --optimize

RUN php artisan optimize

RUN php artisan config:cache

RUN npm install && npm run build

RUN mkdir -p public/build && chown -R www-data:www-data public
######


# Copy NGINX configuration file
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Expose port 80
EXPOSE 80

# Start NGINX and PHP-FPM
CMD service php8.2-fpm start && nginx -g "daemon off;"
