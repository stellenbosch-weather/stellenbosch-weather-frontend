#!/bin/bash
set -e
#schedule this file for minutely execution by cron

cd -P -- "$(dirname -- "$0")"

# Sonbesie
"${WEATHER_PYTHON:-python}" ./sonbesie/TMinHTML.py "$@"
