#!/bin/bash

/var/www/html/vendor/bin/phinx migrate -c /var/www/html/phinx.php && apache2-foreground