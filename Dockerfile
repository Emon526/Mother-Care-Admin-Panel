FROM php:8.0-apache

COPY . /var/www/html

RUN apt-get update && \
    apt-get install -y \
        libpq-dev \
        && \
    docker-php-ext-install pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --no-dev

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
