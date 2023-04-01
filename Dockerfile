FROM php:8.2-cli

RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        git \
        curl \
        libssl-dev \
        openssl \
        ca-certificates

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

RUN composer install --no-dev --no-scripts --no-autoloader

RUN composer dump-autoload --no-dev --optimize

RUN php artisan optimize

RUN php artisan config:cache

RUN npm install && npm run build

RUN mkdir -p public/build && chown -R www-data:www-data public

RUN find /var/www/html -type f -name '*.php' -exec sed -i 's%http://%https://%g' {} +

RUN find /var/www/html/public -type f -name '*.html' -exec sed -i 's%http://%https://%g' {} +

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
