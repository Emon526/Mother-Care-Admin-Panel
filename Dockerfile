# Set the base image
FROM php:8.0.2-apache

# Install required PHP extensions and tools
RUN apt-get update && \
    apt-get install -y git unzip libonig-dev libpq-dev && \
    docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb

# Install Node.js and Yarn
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g yarn

# Set the working directory
WORKDIR /var/www/html

# Copy the composer.json and composer.lock files to the container
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-dev --optimize-autoloader

# Copy the package.json and yarn.lock files to the container
COPY package.json yarn.lock ./

# Install Node.js dependencies
RUN yarn install --frozen-lockfile --no-cache --production

# Copy the Laravel application files to the container
COPY . .

# Build the application assets
RUN yarn build

# Copy the Vite.js configuration file to the container
COPY vite.config.js ./

# Copy the Apache virtual host configuration file to the container
COPY apache2.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache modules and restart Apache
RUN a2enmod rewrite && \
    service apache2 restart

# Expose port 80 for the web server
EXPOSE 80

# Start the web server
CMD ["apache2-foreground"]
