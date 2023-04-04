# Base image
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        git \
        curl \
        libssl-dev \
        openssl && \
    rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip opcache && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb opcache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get update && apt-get install -y nodejs

# Copy source code
WORKDIR /var/www/html
COPY . .

# Set ownership of public/build directory to www-data
RUN mkdir -p public/build
RUN chown -R www-data:www-data /var/www/html/public/build

# Set permissions
RUN chmod -R 775 storage bootstrap/cache && \
    chmod 777 -R storage/logs

# Install dependencies
# RUN composer install --no-dev --no-scripts --no-autoloader --ignore-platform-reqs
RUN composer install --no-scripts --no-autoloader --ignore-platform-reqs

# RUN npm install --only=production && npm run build
RUN npm install && npm run build 

# RUN composer dump-autoload --no-dev --optimize
RUN composer dump-autoload --optimize

# Copy NGINX configuration file
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Expose port 80
EXPOSE 80

# # Expose port 443
# EXPOSE 443

# Start NGINX and PHP-FPM
# CMD service php8.2-fpm start && nginx -g "daemon off;" unreconized services
# CMD systemctl start php8.2-fpm.service && nginx -g "daemon off;" systemctl not found
# CMD php-fpm && nginx -g "daemon off;" fpm is running, pid 6 (but failed)
# CMD service php8.2-fpm restart && nginx -g "daemon off;" php8.2-fpm: unrecognized service
# CMD php-fpm restart && nginx -g "daemon off;" incorrect usage
CMD service php8.2-fpm restart && nginx -g "daemon off;"
