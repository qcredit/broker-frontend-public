FROM php:7.2-fpm

COPY php.ini /usr/local/etc/php/

RUN apt-get update && apt-get install -y libssl-dev cron nano zlib1g-dev libicu-dev locales gettext

RUN pecl install xdebug redis && docker-php-ext-install intl gettext pdo pdo_mysql && docker-php-ext-configure intl && docker-php-ext-enable redis pdo pdo_mysql xdebug \
        && echo 'pl_PL.UTF-8 UTF-8\n' >> /etc/locale.gen \
        && ln -s /etc/locale.alias /usr/share/locale/locale.alias \
        && locale-gen

ADD ./entrypoint.sh      /usr/local/bin/docker-php-entrypoint