FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
        libzip-dev \
        libonig-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libssl-dev \
        zlib1g-dev \
        unzip \
        git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip pdo pdo_mysql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY --chown=www-data:www-data . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader \
    && rm -rf /var/lib/apt/lists/*

COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/ssl/server.crt /etc/ssl/certs/server.crt
COPY .docker/ssl/server.key /etc/ssl/private/server.key

RUN a2enmod rewrite \
    && a2enmod ssl \
    && service apache2 restart

EXPOSE 80 443

CMD ["apache2-foreground"]
