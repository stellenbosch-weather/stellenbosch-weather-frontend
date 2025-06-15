#!/bin/bash

sensor="rain"
minimum="0"
maximum="1000"

for station in dc eb ee mm mr rm sb
do

rrdtool create $station-$sensor-month.rrd --step 60 --start "-15 years", \
DS:$sensor:GAUGE:3600:$minimum:$maximum \
RRA:MAX:0.5:1:1440 \
RRA:MAX:0.5:10:4320 \
RRA:MAX:0.5:60:8760 \
RRA:MAX:0.5:1440:3650 \
#1 minute avg for 1 day
#10 minute average for 30 days
#1 hour avg for 1 year
#1 day average for 10 years

done