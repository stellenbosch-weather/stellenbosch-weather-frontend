#!/bin/bash

# This script needs to be run for all combinations of stations and sensors to create all the RRD files.

station="mm"
sensor="winddir"
minimum="0"
maximum="200"

rrdtool create $station-$sensor.rrd --step 60 --start "-10 years", \
DS:$sensor:GAUGE:600:$minimum:$maximum \
RRA:AVERAGE:0.5:1:1440 \
RRA:AVERAGE:0.5:10:4320 \
RRA:AVERAGE:0.5:60:8760 \
RRA:AVERAGE:0.5:1440:3650 \
#1 minute avg for 1 day
#10 minute average for 30 days
#1 hour avg for 1 year
#1 day average for 10 years
