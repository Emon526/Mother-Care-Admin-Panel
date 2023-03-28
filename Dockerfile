# Set the base image
FROM php:8.2-apache

# Install dependencies
RUN apt-get update && \
    apt-get install -y git unzip libicu-dev && \
    docker-php-ext-install pdo_mysql intl && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb

# Enable Apache modules
RUN a2enmod rewrite

# Copy files and set working directory
COPY . /var/www/html
WORKDIR /var/www/html

# Install composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-dev --prefer-dist --no-scripts --no-progress --no-suggest

# Install node dependencies and build frontend assets
RUN apt-get install -y npm && \
    npm install && \
    npm run production

# Copy .env file
COPY .env /var/www/html/.env

# Set file permissions
RUN chown -R www-data:www-data /var/www/html/storage && \
    chown -R www-data:www-data /var/www/html/bootstrap/cache && \
    chmod -R 755 /var/www/html/storage && \
    chmod -R 755 /var/www/html/bootstrap/cache

# Start Apache
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
