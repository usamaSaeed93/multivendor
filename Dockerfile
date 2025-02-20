# Use PHP 8.1 FPM as base image
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mysqli zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Set git safe directory
RUN git config --global --add safe.directory /var/www

# Copy composer files first to leverage Docker cache
COPY composer.json composer.lock ./

# Install dependencies without running scripts or autoloader
RUN composer install --no-scripts --no-autoloader --ignore-platform-reqs

# Copy the rest of the application
COPY . .

# Generate autoloader and run scripts
RUN composer dump-autoload --optimize && \
    composer run-script post-install-cmd

# Set permissions
RUN chmod -R 777 storage bootstrap/cache

RUN php artisan storage:link

# Expose port for Laravel
EXPOSE 9000

# Run Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]