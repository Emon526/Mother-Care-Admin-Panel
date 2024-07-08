FROM php:8.3.9-cli

# Set environment variable to avoid interactive prompts during package installation
ENV DEBIAN_FRONTEND=noninteractive

# Update package list and install dependencies
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        git \
        curl \
        libssl-dev \
        openssl

# Generate SSL certificate
RUN openssl req -new -newkey rsa:4096 -days 365 -nodes -x509 \
    -subj "/C=US/ST=State/L=City/O=Organization/OU=Department/CN=example.com" \
    -keyout /etc/ssl/private/ssl-cert.key -out /etc/ssl/certs/ssl-cert.crt

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip

# Install MongoDB extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Add NodeSource repository and install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Set correct permissions for storage and cache directories
RUN chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
        /var/www/html/public

# Install PHP dependencies
RUN composer install --no-dev --no-scripts --no-autoloader --ignore-platform-reqs
RUN composer dump-autoload --optimize
RUN php artisan optimize
RUN php artisan config:cache
RUN php artisan route:cache

# Install Node.js dependencies and build assets
RUN npm install && npm run build

# Create build directory and set correct permissions
RUN mkdir -p public/build && chown -R www-data:www-data public

# Expose port 443
EXPOSE 443

# Command to run the application
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=443"]
