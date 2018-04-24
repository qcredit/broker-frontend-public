#!bin/sh
# Last edit: 20180326 Dagor V: first sketches.
#
Address="localhost"
WaitTime="3"
Environment=$(echo ${ENV_TYPE} | tr '[:upper:]' '[:lower:]')
Exit=2
Curl_Flags="--connect-timeout 2 -sf"

if [ "X_${TESTING}_Y" = "X_TRUE_Y" ]; then
  DRY_RUN="&& false"
else
  DRY_RUN=""
fi

Time () {
  date +"%Y.%m.%d_%T"
}

Echo () {
  printf "# $(Time) ### $@ ###\n"
}

case "${Environment}" in
  testserver|production)
    curl ${Curl_Flags} $Address > /dev/null 2>&1 ${DRY_RUN}
    Exit=$?
    if [ $Exit -ne 0 ]; then
      Echo "No response from $Address." > /dev/stderr
    fi
  ;;
  developer)
    Exit=0
    Echo "Skipping healtcheck. Environment: $ENV_TYPE." >  /dev/stdout
  ;;
esac

sleep $WaitTime

exit $Exit