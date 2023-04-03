# Build stage
FROM php:8.2-fpm AS build

# Install necessary extensions for building and testing
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install zip pdo pdo_mysql

# Copy the entire Laravel project to the working directory
WORKDIR /app
COPY . .

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --no-scripts --prefer-dist --optimize-autoloader

# Test the application
RUN php artisan test

# Remove unnecessary files
RUN rm -rf tests vendor

# Production stage
FROM php:8.2-fpm-alpine

# Install necessary packages for production
RUN apk add --no-cache \
    nginx \
    supervisor \
    && adduser -D -u 1000 app

# Copy the application files from the build stage to the production stage
COPY --from=build --chown=app:app /app /app

# Copy the Nginx configuration file
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Copy the Supervisor configuration file
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set the working directory and user
WORKDIR /app
USER app

# Expose the HTTP and HTTPS ports
EXPOSE 80 443

# Start Supervisor to run Nginx and PHP-FPM
CMD ["supervisord", "-n"]
