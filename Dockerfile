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

RUN    apt-get update \
    && usermod -u 1000 www-data \
    && a2enmod rewrite headers expires remoteip \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
    && echo 'session.save_handler=redis' >> /usr/local/etc/php/php.ini \
    && echo 'session.save_path="tcp://redis:6379"' >> /usr/local/etc/php/php.ini \
    && pecl install redis xdebug \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-enable redis pdo pdo_mysql xdebug \
    && test -d /tmp/pear && /bin/rm -rv /tmp/pear \
    && mkdir -p /var/www/html/cache \
    && chown www-data /var/www/html/cache \
    && if cd /var/www/html/conf; then \
         for file in apache_php.ini apache_000-default.conf apache_apache2.conf apache_security.conf; do \
           test -f $file && /bin/rm $file; done; fi

RUN    if cd /var/www/html; then\
         curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
         && composer update && composer install; \
         pwd && ls -lh . vendor/bin/phinx; \
         php /var/www/html/vendor/bin/phinx migrate || echo "Ignoring failure"; \
         php /var/www/html/vendor/bin/phinx seed:run -s PartnerSeed -s ApplicationSeed -s OfferSeed -s SampleAppOfferSeed || echo "Ignoring failure"; \
       else exit 1; fi

EXPOSE 80

# mysql
# mysql# create  database broker_frontend_test;
# mysql# grant all privileges on broker_frontend_test.* to bf_test identified by 'a9HJKvXz4uKRXvfVaBBg';
# mysql# grant execute on procedure setup.change_timezone to bf_test;
# mysql# create  database broker_frontend_production;
# mysql# grant all privileges on broker_frontend_production.* to bf_prod identified by 'PjkAbyTKTe2dr2qMRQF2';
# mysql# grant execute on procedure setup.change_timezone to bf_prod;

# docker build -t broker-frontend-public .
# docker tag broker-frontend-public 666509747749.dkr.ecr.eu-west-1.amazonaws.com/broker-frontend-public:latest
# docker tag broker-frontend-public 666509747749.dkr.ecr.eu-west-1.amazonaws.com/broker-frontend-public:latest$GitCommitAppendix
# eval (aws ecr get-login --no-include-email --region eu-west-1)
# docker push 666509747749.dkr.ecr.eu-west-1.amazonaws.com/broker-frontend-public:latest
# docker push 666509747749.dkr.ecr.eu-west-1.amazonaws.com/broker-frontend-public:latest$GitCommitAppendix
