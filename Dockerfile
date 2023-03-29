# Use the official PHP image as the base image
FROM php:8.0-fpm

# Set the working directory to /app
WORKDIR /app

# Install necessary extensions
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install opcache \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy composer.json and composer.lock
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-scripts --no-autoloader

# Copy package.json and package-lock.json
COPY package.json package-lock.json ./

# Install Node.js and npm
RUN apt-get install -y curl gnupg \
    && curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs

# Install dependencies
RUN npm install

# Copy the project files
COPY . .

# Copy environment variables from .env file
COPY .env ./

# Generate application key
RUN php artisan key:generate

# Run composer and npm scripts
RUN composer dump-autoload --no-scripts --no-dev --optimize \
    && npm run prod

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
