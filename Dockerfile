FROM php:8.3-cli

# Set the working directory
WORKDIR /app

# Install system dependencies and PHP extensions
RUN apt-get update && \
    apt-get install -y \
    libpq-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the Composer files first to leverage Docker caching
COPY composer.json composer.lock ./

# Copy the rest of the application files (including src/ConsoleApp.php)
COPY . /app

# Run composer install
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

ENTRYPOINT ["/usr/local/bin/php", "/app/bin/console"]