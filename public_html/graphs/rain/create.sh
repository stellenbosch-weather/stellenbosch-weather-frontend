#!/bin/bash

sensor="rain"
minimum="0"
maximum="100"

for station in dc eb ee mm mr rm sb
do

rrdtool create $station-$sensor.rrd --step 60 --start "-15 years", \
DS:$sensor:GAUGE:3600:$minimum:$maximum \
RRA:AVERAGE:0.5:2:61 \
RRA:AVERAGE:0.5:60:49 \
RRA:AVERAGE:0.5:1440:9 \
RRA:AVERAGE:0.5:10080:32 \
RRA:AVERAGE:0.5:43200:13 \
RRA:AVERAGE:0.5:525600:10 \
# 2 minute average for 2 hours (61 minutes) -> 2minute buckets on hour graph
# hourly average for 2 days (49 hours) -> hour buckets on day graph
# 1 day (1440 minutes) average for 8 days -> day buckets on week graph
# weekly (10080 minutes) average for a month (32 days)
# monthly/30day (43200 minutes) average for a year (13 months)
# Yearly average (525600) for 10 years


#RRA:AVERAGE:0.5:1:1440 \
#RRA:AVERAGE:0.5:10:4320 \
#RRA:AVERAGE:0.5:60:8760 \
#RRA:AVERAGE:0.5:1440:3650 \
#1 minute avg for 1 day
#10 minute average for 30 days
#1 hour avg for 1 year
#1 day average for 10 years
#

done