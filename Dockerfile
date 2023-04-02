FROM php:8.2-apache

# Install required packages and generate SSL certificate
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
RUN echo "ServerName dockertest-zloh.onrender.com" >> /etc/apache2/apache2.conf
# Install SSL certificates
RUN openssl req -new -newkey rsa:4096 -days 365 -nodes -x509 \
    -subj "/C=US/ST=State/L=City/O=Organization/OU=Department/CN=dockertest-zloh.onrender.com" \
    -keyout /etc/ssl/private/ssl-cert.key -out /etc/ssl/certs/ssl-cert.crt \
    -addext "subjectAltName=DNS:dockertest-zloh.onrender.com"

# Enable SSL module and configure virtual host
RUN a2enmod ssl
COPY apache/000-default.conf /etc/apache2/sites-available/
RUN a2ensite 000-default.conf
RUN a2dissite default-ssl.conf
# Redirect all HTTP requests to HTTPS
RUN echo "Redirect permanent / https://dockertest-zloh.onrender.com/" >> /etc/apache2/sites-available/000-default.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs

# Copy application files
WORKDIR /var/www/html
COPY . .

# Set correct file permissions
RUN chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
        /var/www/html/public

# Install dependencies and optimize application
RUN composer install --no-scripts --no-autoloader --ignore-platform-reqs
RUN composer dump-autoload --optimize
RUN php artisan optimize
RUN php artisan config:cache
RUN npm install && npm run build

# Create directory for compiled assets and set file permissions
RUN mkdir -p public/build && chown -R www-data:www-data public
EXPOSE 80
EXPOSE 443
CMD ["apache2-foreground"]
