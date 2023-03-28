FROM php:8.2-fpm-alpine

# Install required packages
RUN apk --update --no-cache add \
    bash \
    curl \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev

# Configure PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer \
    && chmod +x /usr/local/bin/composer

# Set Composer options
RUN composer config --global process-timeout 600 \
    && composer global require hirak/prestissimo \
    && composer global require "laravel/installer" \
    && composer config --global github-protocols https ssh \
    && composer config --global discard-changes true \
    && composer config --global prefer-dist true \
    && composer config --global sort-packages true \
    && composer config --global optimize-autoloader true \
    && composer config --global --list \
    && composer clear-cache \
    && composer self-update --2 \
    && composer global update

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Give Composer super user access
ENV COMPOSER_ALLOW_SUPERUSER 1

# Install project dependencies
RUN composer set 'composer_options' 'install --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader' \
    && composer install

# Set directory permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
