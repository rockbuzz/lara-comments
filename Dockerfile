FROM php:8.1-fpm-alpine

RUN apk add --no-cache $PHPIZE_DEPS bash \
    && docker-php-ext-install pdo pdo_mysql exif \
    && pecl install pcov \
    && docker-php-ext-enable pcov

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www