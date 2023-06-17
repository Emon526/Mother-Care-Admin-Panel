FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev

RUN php artisan optimize

RUN php artisan config:cache

EXPOSE 80

CMD ["php-fpm"]
