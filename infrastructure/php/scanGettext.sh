#!/bin/sh

cd /app
php locale/cache-templates.php
xgettext --default-domain=broker -p ./locale --from-code=UTF-8 -n --omit-header -L PHP -o broker.pot ./tmp/cache/*/*.php
xgettext --default-domain=broker -p ./locale --from-code=UTF-8 -n --omit-header -L PHP -o broker.pot -j ./src/Model/*.php
xgettext --default-domain=broker -p ./locale --from-code=UTF-8 -n --omit-header -L PHP -o broker.pot -j ./src/Cron/*.php
xgettext --default-domain=broker -p ./locale --from-code=UTF-8 -n --omit-header -L PHP -o broker.pot -j ./src/Base/Repository/*.php
xgettext --default-domain=broker -p ./locale --from-code=UTF-8 -n --omit-header -L PHP -o broker.pot -j ./src/Base/Validator/*.php