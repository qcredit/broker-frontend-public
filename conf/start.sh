#!/bin/sh
# Must be in Unix format
set -e
PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:${PATH}"

Time () { date "+%Y.%m.%d %T"; }
Echo () {
  first=${1%% *}
  if [ "${first}" = "[debug]" ]; then
    if [ "X_${Debug}_Y" = "X_TRUE_Y" ]; then
      printf "$(Time) ${SCRIPTNAME} # $@\n"
    fi
  elif [ "${first}" = "[error]" ]; then
    printf "$(Time) ${SCRIPTNAME} # $@\n" >&2
  else
    printf "$(Time) ${SCRIPTNAME} # $@\n"
  fi
}

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- apache2-foreground "$@"
fi

(
  Echo "[info] Starting phinx migrate." | tee -a /var/log/messages
  /var/www/html/vendor/bin/phinx migrate -c /var/www/html/phinx.php | tee -a /var/log/messages 2>&1
  Echo "[info] Finished phinx migrate: $?" | tee -a /var/log/messages
) &

sleep 8

Echo "[info] Generating application translations ..."
msgfmt --output-file=/var/www/html/locale/pl_PL/LC_MESSAGES/broker.mo /var/www/html/locale/pl_PL/LC_MESSAGES/broker.po

Echo "[info] Setting up cron jobs ..."
echo "* * * * * ENV_TYPE=\"${ENV_TYPE}\" /usr/local/bin/php /var/www/html/src/cron.php >> /var/log/apache2/broker-cron.log 2>&1" | crontab -

Echo "[info] Starting cron as daemon ..."
cron

Echo "[info] Starting $@ ..."
exec "$@"
