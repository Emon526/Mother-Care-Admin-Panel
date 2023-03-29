FROM php:8.2-apache

# Install Docker
RUN apt-get update && \
    apt-get install -y apt-transport-https ca-certificates curl gnupg lsb-release && \
    curl -fsSL https://download.docker.com/linux/debian/gpg | gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg && \
    echo "deb [arch=amd64 signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/debian \
    $(lsb_release -cs) stable" | tee /etc/apt/sources.list.d/docker.list > /dev/null && \
    apt-get update && \
    apt-get install -y docker-ce-cli

# Copy the application code
COPY . /var/www/html

# Install required PHP extensions
RUN apt-get update && \
    apt-get install -y \
        libpq-dev \
        && \
    docker-php-ext-install pdo_pgsql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/local/bin --filename=composer

# Install project dependencies
RUN composer install --no-dev

# Expose port 8000
EXPOSE 8000

# Start the application server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
