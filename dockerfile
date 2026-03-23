FROM nginx:1.27-alpine AS contacts_nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf
WORKDIR /symfony-contacts/public

FROM php:8.2-fpm AS base
RUN apk add --no-cache \
    git unzip icu-dev libzip-dev libpng-dev oniguruma-dev libxml2-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

FROM base AS dev
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

FROM base AS prod
ENV APP_ENV=prod
COPY . /var/www/html
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && php bin/console cache:clear
