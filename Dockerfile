# Build stage
FROM php:8.2-cli-alpine AS build

# Install dependencies
RUN apk add --no-cache \
        libzip-dev \
        zip \
        unzip \
        git \
        curl \
        libssl1.1 \
        openssl-dev \
    && docker-php-ext-install pdo_mysql zip \
    && pecl install mongodb && docker-php-ext-enable mongodb

# Install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apk add --no-cache nodejs

# Set working directory
WORKDIR /app

# Copy project files
COPY composer.json composer.lock ./
COPY database database/
COPY routes routes/
COPY app app/

# Install PHP dependencies
RUN composer install --no-dev --no-scripts --no-autoloader

# Install JavaScript dependencies
COPY package.json package-lock.json webpack.mix.js ./
RUN npm ci --production

# Build assets
COPY resources resources/
RUN npm run production
RUN mkdir -p public/build && cp public/index.php public/build/index.php

# Autoload PHP classes
RUN composer dump-autoload --no-dev --optimize

# Runtime stage
FROM php:8.2-cli-alpine

# Install runtime dependencies
RUN apk add --no-cache \
        libzip \
        openssl \
    && docker-php-ext-install pdo_mysql zip

# Set working directory
WORKDIR /app

# Copy project files and assets
COPY --from=build /app .
COPY public public/

# Set ownership
RUN chown -R www-data:www-data storage bootstrap/cache public

# Expose port
EXPOSE 8000

# Run the application
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
