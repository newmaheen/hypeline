FROM php:8.3-cli-alpine

# Install system dependencies & PHP extensions
RUN apk add --no-cache nodejs npm git unzip libpng-dev libzip-dev oniguruma-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy project files
COPY . .

# Install PHP and Node dependencies & build assets
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build

# Expose port and start Laravel server
EXPOSE 10000
CMD php artisan storage:link && php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=10000