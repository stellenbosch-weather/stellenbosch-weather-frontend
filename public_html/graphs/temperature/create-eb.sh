#!/bin/bash
station="eb"
sensor="temperature"
minimum="-75"
maximum="100"

rrdtool create $station-$sensor.rrd --step 86400 --start "01/01/1995", \
DS:$sensor-max:GAUGE:172800:$minimum:$maximum \
DS:$sensor-min:GAUGE:172800:$minimum:$maximum \
DS:$sensor-avg:GAUGE:172800:$minimum:$maximum \
RRA:MAX:0.5:1:7300 \
RRA:MIN:0.5:1:7300 \
RRA:AVERAGE:0.5:1:7300 \
#RRA:AVERAGE:0.5:2:100 \
#RRA:AVERAGE:0.5:4:100 \
#RRA:AVERAGE:0.5:8:100 \
#RRA:AVERAGE:0.5:16:100 \
#RRA:AVERAGE:0.5:32:100 \
#RRA:AVERAGE:0.5:64:100
#1 day avg for one year
#1 week avg for 5 years
#monthly average for 50 years
