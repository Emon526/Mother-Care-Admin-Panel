FROM php:8.2-fpm

RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        git \
        curl \
        libssl-dev \
        openssl

# set ServerName directive globally to suppress warning
RUN echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf && \
    a2enconf servername

RUN mkdir -p /etc/apache2/ssl

RUN openssl req -new -newkey rsa:4096 -days 365 -nodes -x509 \
    -subj "/C=US/ST=State/L=City/O=Organization/OU=Department/CN=example.com" \
    -keyout /etc/apache2/ssl/apache.key -out /etc/apache2/ssl/apache.crt

RUN chmod 400 /etc/apache2/ssl/apache.key

RUN sed -i 's/\/etc\/ssl\/certs\/ssl-cert-snakeoil.pem/\/etc\/apache2\/ssl\/apache.crt/g' /etc/apache2/sites-available/default-ssl.conf && \
    sed -i 's/\/etc\/ssl\/private\/ssl-cert-snakeoil.key/\/etc\/apache2\/ssl\/apache.key/g' /etc/apache2/sites-available/default-ssl.conf

# enable SSL module and set the SSL certificate and key
RUN a2enmod ssl && \
    a2ensite default-ssl && \
    sed -i '/<VirtualHost/a SSLEngine on\nSSLCertificateFile /etc/apache2/ssl/apache.crt\nSSLCertificateKeyFile /etc/apache2/ssl/apache.key' /etc/apache2/sites-available/default-ssl.conf

# expose both HTTP and HTTPS ports
EXPOSE 80 443

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo_mysql zip

RUN pecl install mongodb && docker-php-ext-enable mongodb

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs

WORKDIR /var/www/html

COPY . .

RUN mkdir -p /var/www/html/vendor && chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
        /var/www/html/public \
        /var/www/html/vendor

RUN composer install --no-scripts --no-autoloader --ignore-platform-reqs

RUN composer dump-autoload --optimize

RUN php artisan optimize

RUN php artisan config:cache

RUN npm install && npm run build

RUN mkdir -p public/build && chown -R www-data:www-data public

CMD ["php-fpm"]
