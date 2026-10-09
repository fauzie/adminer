FROM php:7.4-cli-alpine

LABEL   maintainer="Rizal Fauzie Ridwan <rizal@fauzie.my.id>"

RUN     apk add --update --no-cache curl icu-libs gettext libintl libzip \
        && apk add --virtual .build-deps libzip-dev zlib-dev gettext-dev icu-dev make gcc g++ autoconf \
        && docker-php-source extract \
        && docker-php-ext-install -j$(nproc) opcache zip pdo_mysql mysqli \
        && docker-php-source delete \
        && mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
        && apk del .build-deps \
        && mkdir -p /var/www/html

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "/var/www/html"]