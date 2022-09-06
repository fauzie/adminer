#!/bin/sh

pidof nginx >/dev/null && echo "nginx is running" || /usr/sbin/nginx
pidof php-fpm >/dev/null && echo "php-fpm is running" || /usr/local/sbin/php-fpm -F

exec "$@"
