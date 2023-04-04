FROM php:8.2-fpm-alpine

# Install necessary packages
RUN apk update && \
    apk add --no-cache \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    npm \
    curl \
    git \
    unzip \
    libxml2-dev \
    libmcrypt-dev \
    libressl-dev \
    autoconf \
    g++ \
    make \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install gd \
    && docker-php-ext-install zip \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install opcache \
    && docker-php-ext-install bcmath \
    && pecl install mcrypt-1.0.4 && docker-php-ext-enable mcrypt \
    && pecl install redis && docker-php-ext-enable redis \
    && rm -rf /var/cache/apk/*

# Set working directory
WORKDIR /app

# Copy Laravel files
COPY . /app

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --no-scripts --no-progress --prefer-dist --optimize-autoloader

# Install npm dependencies and build the assets
RUN npm install && npm run build

# Set permission
RUN chown -R www-data:www-data /app/storage

# Expose port 9000 and start php-fpm
EXPOSE 9000
CMD ["php-fpm"]
