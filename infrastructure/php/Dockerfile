FROM php:7.2-fpm

COPY php.ini /usr/local/etc/php/

RUN apt-get update && apt-get install -y libssl-dev cron nano zlib1g-dev libicu-dev locales g++

RUN pecl install xdebug && docker-php-ext-install intl gettext pdo pdo_mysql && docker-php-ext-configure intl && docker-php-ext-enable pdo pdo_mysql xdebug && locale-gen pl_PL.UTF-8