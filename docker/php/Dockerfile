FROM php:8.4.1-fpm

# System deps
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev \
    libzip-dev \
    libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions (umum untuk Laravel)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
      pdo_mysql mbstring exif pcntl bcmath gd intl zip opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# php.ini (opsional)
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www

# Copy composer files dulu biar layer cache kepake
COPY composer.json composer.lock ./

# Install dependencies (tanpa scripts dulu biar aman)
RUN composer install --no-interaction --no-progress --prefer-dist --no-scripts

# Copy source code
COPY . .

# Jalankan scripts setelah code lengkap (opsional)
RUN composer dump-autoload --optimize

# Permission (opsional)
RUN chown -R www-data:www-data /var/www

CMD ["php-fpm"]
