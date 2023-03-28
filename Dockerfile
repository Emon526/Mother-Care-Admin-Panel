FROM php:8.0.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql

# Enable Apache modules
RUN a2enmod rewrite

# Set up a working directory
WORKDIR /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy and install the project dependencies
COPY . /var/www/html/
RUN composer install --no-interaction --optimize-autoloader

# Set up the required environment variables
ENV APP_ENV=production
ENV APP_KEY=base64:RANDOMLY_GENERATED_KEY_HERE

# Expose the container port
EXPOSE 80

# Start the Apache server
CMD ["apache2-foreground"]
