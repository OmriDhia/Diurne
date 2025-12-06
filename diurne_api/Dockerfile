FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    && docker-php-ext-install \
    pdo_mysql \
    zip \
    intl \
    mbstring \
    opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/symfony

# Copy project files
COPY . .

# Install PHP dependencies
# We use --no-scripts to avoid running auto-scripts that might fail without database
RUN composer install --no-scripts --no-interaction --optimize-autoloader

CMD ["php-fpm"]
