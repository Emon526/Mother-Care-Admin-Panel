FROM php:8.2-fpm

# Install necessary extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install zip pdo pdo_mysql

# Set the working directory
WORKDIR /var/www/html

# Copy the entire Laravel project to the working directory
COPY . .

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --no-scripts --prefer-dist --optimize-autoloader

# Copy the Nginx configuration file
COPY nginx.conf /etc/nginx/conf.d/default.conf
