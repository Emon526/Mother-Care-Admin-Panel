# Base image
FROM php:8.0-apache

# Set the working directory
WORKDIR /var/www/html

# Copy project files to the container
COPY . .

# Install dependencies using Composer
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev

# Copy the .env file to the project
COPY .env.example .env

# Generate the application key
RUN php artisan key:generate

# Set the port
EXPOSE 80
