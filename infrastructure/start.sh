#!/bin/bash

php /var/www/html/vendor/bin/phinx migrate -c /var/www/html/phinx.php

if [ -z "$1" ]
then
    exec "/usr/sbin/apache2 -D -foreground"
else
    exec "$1"
fi