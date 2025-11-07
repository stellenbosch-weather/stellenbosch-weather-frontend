#!/bin/bash
#schedule this file for minutely execution by cron

setenv SCRIPTDIR `dirname $0`

# Sonbesie
$SCRIPTDIR/sonbesie/TMinHTML.py
