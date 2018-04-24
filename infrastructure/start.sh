#!/bin/sh
set -e

Time () {
  date +"%Y.%m.%d_%T"
}

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- apache2-foreground "$@"
fi

( echo "### $(Time) Starting phinx migrate ###"     | tee -a /var/log/messages
  /var/www/html/vendor/bin/phinx migrate -c /var/www/html/phinx.php
  echo "### $(Time) Finished phinx migrate: $? ###" | tee -a /var/log/messages
) &

sleep 8

exec "$@"
