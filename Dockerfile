FROM php:8.2-apache

USER root

RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        git \
        curl \
        libssl-dev \
        openssl

RUN openssl req -new -newkey rsa:4096 -days 365 -nodes -x509 \
    -subj "/C=US/ST=State/L=City/O=Organization/OU=Department/CN=example.com" \
    -keyout /etc/ssl/private/ssl-cert.key -out /etc/ssl/certs/ssl-cert.crt

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo_mysql zip

RUN pecl install mongodb && docker-php-ext-enable mongodb

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs

WORKDIR /var/www/html

COPY . .

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

CMD ["apache2-foreground"]
