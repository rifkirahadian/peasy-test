# Use the official PHP 7.4 image as base
FROM php:7.4

# Set working directory
WORKDIR /var/www/html

# Install PostgreSQL and Redis dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo_pgsql zip \
    && pecl install redis && docker-php-ext-enable redis

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy project files and directories to the working directory
COPY . .

# Install PHP dependencies
RUN composer install --no-scripts --no-autoloader

# Generate autoload files
RUN composer dump-autoload --optimize

# Expose port 8000
EXPOSE 8000

# Start PHP built-in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
