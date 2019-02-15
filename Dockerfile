ARG GitCommitAppendix

FROM 666509747749.dkr.ecr.eu-west-1.amazonaws.com/aasa-public:php7.2-apache-test

MAINTAINER dagor.vint@aasaglobal.com

ENV TERM="xterm-256color"
ENV HOME="/root"
ENV TZ="Europe/Tallinn"
ENV DEBIAN_FRONTEND="noninteractive"
ENV DEBUG="TRUE"

ADD ./conf/start.sh       /usr/local/bin/broker-entrypoint
ADD ./conf/apache_php.ini /usr/local/etc/php/conf.d/broker_php.ini
ADD --chown=www-data:www-data . /var/www/html


RUN printf "<?php\n  echo \"<br>$(date)\";\n  echo \"<br>$(cat /root/.version)\"\n?>\n" > /var/www/html/version.php; \
    chmod +x /usr/local/bin/broker-entrypoint; \
    apt-get clean all; apt-get update -qq ; apt-get install -y git libicu-dev locales cron; \
    pecl install xdebug; \
    docker-php-ext-install intl gettext; \
    docker-php-ext-configure intl; \
    docker-php-ext-enable xdebug; \
    echo "[info] Changing Apache DocumentRoot."; \
    sed --in-place --expression '/DocumentRoot/s/\/.*/\/var\/www\/html\/public/g' /etc/apache2/sites-enabled/000-default.conf; \
    echo "[info] Removing Files and Folders."; \
    if cd /var/www/html/; then composer install; fi; \
    if cd /var/www/html/conf; then \
      for file in apache_000-default.conf apache_apache2.conf apache_php.ini apache_security.conf start.sh; do \
        test -f $file && /bin/rm $file; done; fi; \
    if cd /var/www/html/infrastructure; then \
      for file in start.sh; do \
        test -f $file && /bin/rm $file; done; \
      for folder in mysql nginx php; do \
        test -d $folder && /bin/rm -r $folder; done; fi; \
    echo 'pl_PL.UTF-8 UTF-8\n' >> /etc/locale.gen; \
    ln -sf /etc/locale.alias /usr/share/locale/locale.alias; \
    locale-gen

ENTRYPOINT ["broker-entrypoint"]

CMD ["apache2-foreground"]

# docker build -t broker-frontend-public .
# docker tag broker-frontend-public 666509747749.dkr.ecr.eu-west-1.amazonaws.com/broker-frontend-public:latest
# docker tag broker-frontend-public 666509747749.dkr.ecr.eu-west-1.amazonaws.com/broker-frontend-public:latest$GitCommitAppendix
# eval (aws ecr get-login --no-include-email --region eu-west-1)
# docker push 666509747749.dkr.ecr.eu-west-1.amazonaws.com/broker-frontend-public:latest$GitCommitAppendix
# docker push 666509747749.dkr.ecr.eu-west-1.amazonaws.com/broker-frontend-public:latest
