#!/bin/sh
# Must be in Unix format
set -e
PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:${PATH}"

Time () {
  date +"%Y.%m.%d_%T"
}

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- apache2-foreground "$@"
fi

( echo "### $(Time) Starting phinx migrate ###"     | tee -a /var/log/messages
  /var/www/html/vendor/bin/phinx migrate -c /var/www/html/phinx.php | tee -a /var/log/messages 2>&1
  echo "### $(Time) Finished phinx migrate: $? ###" | tee -a /var/log/messages
) &

sleep 8

echo "Setting up cron jobs..."
cat /app/infrastructure/php/crontab | crontab
cron

exec "$@"
