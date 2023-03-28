# Use the official PHP image as the base image
FROM php:8.2-apache

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Install required PHP extensions
RUN docker-php-ext-install pdo_mysql

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the source code to the working directory
COPY . .
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN set('composer_options', 'install --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader');
RUN set -eux
# Install project dependencies using Composer
RUN composer install --prefer-dist --no-interaction

# Copy the Apache configuration file
COPY docker/apache2.conf /etc/apache2/apache2.conf

# Enable the Apache rewrite module
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Start the Apache web server
CMD ["apache2-foreground"]
