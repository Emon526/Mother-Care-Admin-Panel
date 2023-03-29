# Base image
FROM php:8.2-apache

# Update and install necessary packages
RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo_mysql zip

# Install MongoDB PHP extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Copy project files into container
COPY . /var/www/html

# Copy .env file into container
COPY .env /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && npm install -g vite

# Install project dependencies
WORKDIR /var/www/html
RUN composer install --optimize-autoloader --no-dev --no-interaction --no-scripts \
    && npm install \
    && npm run dev

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
