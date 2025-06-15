#!/bin/bash
station="eb"
sensor="rain"
minimum="0"
maximum="1000"

rrdtool create $station-$sensor-month.rrd --step 86400 --start "01/01/1995", \
DS:$sensor:GAUGE:172800:$minimum:$maximum \
RRA:MAX:0.5:1:7300 \
#RRA:MAX:0.5:2:100 \
#RRA:MAX:0.5:4:100 \
#RRA:MAX:0.5:8:100 \
#RRA:MAX:0.5:16:100 \
#RRA:MAX:0.5:32:100 \
#RRA:MAX:0.5:64:100
#1 day avg for one year
#1 week avg for 5 years
#monthly average for 50 years
