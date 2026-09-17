FROM php:8.3-cli-alpine

# Install system dependencies, ca-certificates & PHP extensions
RUN apk add --no-cache nodejs npm git unzip libpng-dev libzip-dev oniguruma-dev ca-certificates \
    && update-ca-certificates \
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
CMD sh -c "php artisan storage:link && (php artisan migrate --force || true) && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"