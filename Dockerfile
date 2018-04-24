ARG GitCommitAppendix
FROM php:7.2-apache

MAINTAINER dagor.vint@aasaglobal.com

ENV TERM=xterm-256color
ENV HOME=/root
ENV TZ=Europe/Tallinn
ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get clean all; apt-get update && apt-get upgrade -y; \
    apt-get install -y git zip unzip

ADD ./conf/apache_php.ini          /usr/local/etc/php/php.ini
ADD ./conf/apache_000-default.conf /etc/apache2/sites-enabled/000-default.conf
ADD ./conf/apache_apache2.conf     /etc/apache2/apache2.conf
ADD ./conf/apache_security.conf    /etc/apache2/conf-available/security.conf
ADD .                              /var/www/html
ADD ./infrastructure/start.sh      /usr/local/bin/docker-php-entrypoint

RUN    apt-get update \
    && usermod -u 1000 www-data \
    && a2enmod rewrite headers expires remoteip \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
    && echo 'session.save_handler=redis' >> /usr/local/etc/php/php.ini \
    && echo 'session.save_path="tcp://redis:6379"' >> /usr/local/etc/php/php.ini \
    && pecl install redis xdebug \
    && docker-php-ext-install pdo pdo_mysql >> /dev/null \
    && docker-php-ext-enable redis pdo pdo_mysql xdebug >> /dev/null \
    && if [ -d /tmp/pear ]; then /bin/rm -rv /tmp/pear; fi \
    && mkdir -p /var/www/html/cache \
    && chown www-data /var/www/html/cache \
    && if cd /var/www/html/conf; then \
         for file in apache_php.ini apache_000-default.conf apache_apache2.conf apache_security.conf; do \
           test -f $file && /bin/rm $file; done; fi \
    && if cd /var/www/html/infrastructure; then \
         for file in start.sh; do \
           test -f $file && /bin/rm $file; done; fi; \
         for folder in mysql nginx php; do \
           test -d $folder && /bin/rm -r $folder; done; fi

RUN    if cd /var/www/html; then\
         curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
         && composer update && composer install; \
         pwd && ls -lh . vendor/bin/phinx; \
       else exit 1; fi

EXPOSE 80

# docker build -t broker-frontend-public .
# docker tag broker-frontend-public 666509747749.dkr.ecr.eu-west-1.amazonaws.com/broker-frontend-public:latest
# docker tag broker-frontend-public 666509747749.dkr.ecr.eu-west-1.amazonaws.com/broker-frontend-public:latest$GitCommitAppendix
# eval (aws ecr get-login --no-include-email --region eu-west-1)
# docker push 666509747749.dkr.ecr.eu-west-1.amazonaws.com/broker-frontend-public:latest
# docker push 666509747749.dkr.ecr.eu-west-1.amazonaws.com/broker-frontend-public:latest$GitCommitAppendix
