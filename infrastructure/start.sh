#!/bin/bash

/var/www/html/vendor/bin/phinx migrate -c /var/www/html/phinx.php && exec apache2 -D foreground

