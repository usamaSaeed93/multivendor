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
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mysqli zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy the entire project first to ensure package.json is available
COPY . .

# Set git safe directory
RUN git config --global --add safe.directory /var/www

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node.js dependencies & build frontend
RUN npm install -g yarn && yarn install && yarn production

# Set permissions
RUN chmod -R 777 storage bootstrap/cache

# Create storage symlink
RUN php artisan storage:link

# Expose port for Laravel
EXPOSE 9000

# Run Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]
