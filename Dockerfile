# syntax=docker/dockerfile:1
FROM php:8.3-apache

# Ekstensi PHP yang dibutuhkan Laravel + MySQL
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
        libonig-dev \
        libxml2-dev \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql mbstring zip opcache \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# Virtual host Laravel: DocumentRoot -> /public, listen di 8080
RUN rm -f /etc/apache2/sites-enabled/000-default.conf
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Dependency dulu (layer cache tetap kepakai walau source berubah)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

COPY . .

RUN composer dump-autoload --optimize --no-dev \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Platform (Railway/Render/Koyeb) meng-inject PORT saat runtime; default 8080
ENV PORT=8080
EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
