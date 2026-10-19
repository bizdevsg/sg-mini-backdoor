FROM node:22-bookworm-slim AS frontend

WORKDIR /var/www/html

COPY package.json package-lock.json ./
RUN npm ci

COPY public ./public
COPY resources ./resources
COPY vite.config.js ./

RUN npm run build

FROM php:8.4-fpm-bookworm AS runtime

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update \
    && apt-get install --no-install-recommends -y \
        curl \
        git \
        libavif-dev \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-configure gd --with-avif --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        exif \
        gd \
        intl \
        opcache \
        pcntl \
        pdo_mysql \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --prefer-dist

COPY . .
COPY --from=frontend /var/www/html/public/build ./public/build
COPY docker/php/app.ini /usr/local/etc/php/conf.d/99-app.ini

RUN composer dump-autoload --no-dev --classmap-authoritative \
    && mkdir -p \
        bootstrap/cache \
        storage/app/public \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
    && ln -sfn ../storage/app/public public/storage \
    && chown -R www-data:www-data bootstrap/cache storage

USER www-data

EXPOSE 9000

CMD ["php-fpm"]

FROM nginx:1.27-alpine AS web

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY --from=runtime /var/www/html/public /var/www/html/public
