# Use a lightweight base image
FROM php:8.2-fpm-alpine

# Install necessary extensions and packages
RUN apk add --no-cache \
        openssl \
        zip \
        unzip \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        openssl-dev \
        zlib-dev \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install zip pdo pdo_mysql \
    && apk del -f .build-deps

# Set the working directory
WORKDIR /var/www/html

# Copy the entire Laravel project to the working directory
COPY . .

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --no-scripts --prefer-dist --optimize-autoloader --no-interaction

# Copy the Nginx configuration file
COPY nginx.conf /etc/nginx/conf.d/default.conf
