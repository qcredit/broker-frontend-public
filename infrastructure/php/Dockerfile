FROM php:7.2-fpm

COPY php.ini /usr/local/etc/php/

RUN apt-get update && apt-get install -y libssl-dev

RUN pecl install xdebug && docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo pdo_mysql xdebug