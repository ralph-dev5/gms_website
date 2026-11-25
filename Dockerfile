
# Stage 1: Build frontend
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build

# Stage 2: Laravel application
FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libicu-dev libonig-dev libpq-dev \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring bcmath gd intl zip opcache \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf

RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader \
    --no-interaction --no-scripts

COPY . .

COPY --from=frontend /app/public/build ./public/build

RUN composer dump-autoload --optimize --no-dev --no-interaction \
    && php artisan package:discover --ansi \
    && mkdir -p storage/framework/cache \
       storage/framework/sessions \
       storage/framework/views \
       bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

ENV PORT=80

EXPOSE 80

# Bind Apache to Render's $PORT, write the DB SSL CA from $DB_SSL_CA_PEM (if set),
# cache config/routes/views with runtime env vars, optionally run migrations
# (RUN_MIGRATIONS=true) and create the admin (ADMIN_EMAIL + ADMIN_PASSWORD), then start Apache.
CMD ["sh", "-c", "sed -ri \"s/Listen [0-9]+/Listen ${PORT}/\" /etc/apache2/ports.conf \
    && sed -ri \"s/<VirtualHost \\*:[0-9]+>/<VirtualHost *:${PORT}>/\" /etc/apache2/sites-available/000-default.conf \
    && if [ -n \"$DB_SSL_CA_PEM\" ]; then mkdir -p storage/certs && printf '%s\\n' \"$DB_SSL_CA_PEM\" > storage/certs/db-ca.pem && export MYSQL_ATTR_SSL_CA=/var/www/html/storage/certs/db-ca.pem; fi \
    && (php artisan storage:link --force || true) \
    && php artisan config:cache && php artisan route:cache && php artisan view:cache \
    && if [ \"$RUN_MIGRATIONS\" = \"true\" ]; then php artisan migrate --force \
       && if [ -n \"$ADMIN_EMAIL\" ] && [ -n \"$ADMIN_PASSWORD\" ]; then php artisan db:seed --class=AdminUserSeeder --force; fi; fi \
    && exec apache2-foreground"]
