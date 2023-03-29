FROM php:8.0

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip

COPY . /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN cd /var/www/html && composer install --no-interaction --no-scripts

RUN cd /var/www/html && npm install && npm run dev

COPY .env /var/www/html

ENV APP_NAME=Laravel
ENV APP_ENV=local
ENV APP_KEY=base64:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
ENV APP_DEBUG=true
ENV APP_URL=http://localhost
EXPOSE 80
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]

