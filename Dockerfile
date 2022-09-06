FROM php:8-fpm-alpine

LABEL maintainer="Rizal Fauzie Ridwan <rizal@fauzie.id>"

RUN apk add --no-cache --update libpng libjpeg-turbo icu-libs \
    gettext freetype libintl libzip nginx
    
RUN apk add --virtual .build-deps libpng-dev libzip-dev icu-dev \
    libjpeg-turbo-dev libwebp-dev zlib-dev gettext-dev freetype-dev make gcc g++ autoconf

RUN export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS"; \
    docker-php-source extract; \
    docker-php-ext-configure gd --with-jpeg --with-freetype; \
    docker-php-ext-configure intl --enable-intl; \
    docker-php-ext-configure opcache --enable-opcache; \
    docker-php-ext-install -j$(nproc) \
    gd gettext intl mysqli opcache pdo_mysql zip

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN docker-php-source delete; \
    apk del .build-deps; \
    rm -rf /var/cache/apk/*; \
    rm -rf /tmp/*

COPY nginx.conf /etc/nginx/nginx.conf
COPY start.sh /start.sh

WORKDIR /var/www/html

EXPOSE 80

STOPSIGNAL SIGQUIT

ENTRYPOINT /start.sh
