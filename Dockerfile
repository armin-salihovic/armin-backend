FROM node:18-alpine AS assets

WORKDIR /app

COPY package*.json ./
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi

COPY resources ./resources
COPY vite.config.js ./
RUN npm run build

FROM php:8.1-fpm-alpine AS app

WORKDIR /var/www/html

RUN apk add --no-cache \
        bash \
        curl \
        freetype \
        icu-libs \
        libjpeg-turbo \
        libpng \
        libwebp \
        libzip \
        oniguruma \
        sqlite \
    && apk add --no-cache --virtual .build-deps \
        freetype-dev \
        icu-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libzip-dev \
        oniguruma-dev \
        sqlite-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        exif \
        gd \
        intl \
        mbstring \
        opcache \
        pcntl \
        pdo_sqlite \
        zip \
    && apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-interaction \
        --no-progress \
        --prefer-dist \
        --optimize-autoloader \
        --no-scripts

COPY . .
COPY --from=assets /app/public/build ./public/build
COPY docker/app/entrypoint.sh /usr/local/bin/armin-entrypoint

RUN composer dump-autoload --optimize \
    && chmod +x /usr/local/bin/armin-entrypoint \
    && mkdir -p database/sqlite storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache database/sqlite

ENTRYPOINT ["armin-entrypoint"]
CMD ["php-fpm"]

FROM nginx:1.27-alpine AS nginx

WORKDIR /var/www/html

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY --from=app /var/www/html/public ./public
