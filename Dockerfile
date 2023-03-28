# Set the base image
FROM php:8.0-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install composer
RUN apk add --no-cache composer

# Install dependencies
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && apk add --no-cache bash git openssh \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install pdo pdo_mysql \
    && composer config --global process-timeout 600 \
    && composer global require hirak/prestissimo \
    && composer global require "laravel/installer" --ignore-platform-reqs \
    && composer config --global github-protocols https ssh \
    && composer config --global discard-changes true \
    && composer config --global prefer-dist true \
    && composer config --global sort-packages true \
    && composer config --global optimize-autoloader true \
    && composer config --global --list \
    && composer clear-cache \
    && composer self-update --2 \
    && composer global update \
    && apk del .build-deps

# Add user for Laravel application
RUN addgroup -g 1000 -S www-data \
    && adduser -u 1000 -D -S -G www-data www-data \
    && chown -R www-data:www-data /var/www/html

# Copy project files
COPY . .

# Give the composer super user access
RUN chown -R www-data:www-data /root/.composer

# Set composer options
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_OPTIONS="--verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader"

# Install application dependencies
RUN composer install $COMPOSER_OPTIONS

# Expose port 9000 and start PHP-FPM server
EXPOSE 9000
CMD ["php-fpm"]
