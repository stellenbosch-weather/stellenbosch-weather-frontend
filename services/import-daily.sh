#!/bin/bash
set -e
#schedule this file for daily execution by cron

cd -P -- "$(dirname -- "$0")"

# Sonbesie
"${WEATHER_PYTHON:-python}" ./sonbesie/TDailyHTML.py

