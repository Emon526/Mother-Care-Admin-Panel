FROM php:8.2-fpm

RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        git \
        curl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo_mysql zip

RUN pecl install mongodb && docker-php-ext-enable mongodb

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache

RUN composer install
RUN npm install

RUN php artisan config:cache

CMD ["php-fpm", "-F"]
