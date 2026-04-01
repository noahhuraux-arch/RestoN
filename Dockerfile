# NGINX
FROM nginx:1.27-alpine AS resto_nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf
WORKDIR /var/www/html/public

# BASE PHP
FROM php:8.2-fpm AS base
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

# DEV PHP
FROM base AS dev
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
COPY entrypoint-dev.sh /usr/local/bin/entrypoint-dev.sh
RUN chmod +x /usr/local/bin/entrypoint-dev.sh
ENTRYPOINT ["entrypoint-dev.sh"]

# PROD PHP
FROM base AS prod
ENV APP_ENV=prod
COPY . /var/www/html
RUN mkdir -p /var/www/html/var/cache /var/www/html/var/log \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/var
USER www-data
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && php bin/console cache:clear --env=prod \
    && php bin/console asset-map:compile --env=prod
USER root

