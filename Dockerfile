FROM php:8.2-cli

RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        git \
        curl \
        libssl-dev \
        openssl

RUN docker-php-ext-install pdo_mysql zip

RUN pecl install mongodb && docker-php-ext-enable mongodb

RUN apt-get update && apt-get install -y openssl && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install openssl

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
        /var/www/html/public

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install

RUN npm install && npm run build

RUN mkdir -p public/build && chown -R www-data:www-data public

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
