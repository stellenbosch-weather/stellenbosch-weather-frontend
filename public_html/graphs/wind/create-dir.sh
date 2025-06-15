#!/bin/bash
station="sb"
sensor="winddir"
minimum="-1"
maximum="1"

for station in dc eb ee mm mr rm sb
do

rrdtool create $station-$sensor.rrd --step 60 --start "-15 years", \
DS:dirx:GAUGE:3600:$minimum:$maximum \
DS:diry:GAUGE:3600:$minimum:$maximum \
RRA:AVERAGE:0.5:1:2000 \
RRA:AVERAGE:0.5:10:1440 \
RRA:AVERAGE:0.5:180:480 \
RRA:AVERAGE:0.5:1440:100 \
RRA:AVERAGE:0.5:10080:60 \
RRA:AVERAGE:0.5:43200:122 \
#1 minute avg for 1+ day
#10 minute avg for 10 day
#3 hour avg for 2 months
#1 day average for 100 days
#1 week avg for 60 weeks
#1 month average for 10 years

done