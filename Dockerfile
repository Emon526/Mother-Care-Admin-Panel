FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
        php8-pdo \
        php8-pdo_mysql \
        php8-sockets

RUN docker-php-ext-install pdo pdo_mysql sockets --with-php-config=/usr/local/bin/php-config

RUN curl -sS https://getcomposer.org/installer​ | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .
RUN composer install --no-scripts --no-autoloader --ignore-platform-reqs
