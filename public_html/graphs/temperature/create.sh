#!/bin/bash
# station="sb"
# sensor="temperature"
# minimum="-20"
# maximum="100"

# rrdtool create $station-$sensor.rrd --step 60 --start "-15 years", \
# DS:$sensor:GAUGE:3600:$minimum:$maximum \
# RRA:AVERAGE:0.5:1:1440 \
# RRA:MIN:0.5:1:1440 \
# RRA:MAX:0.5:1:1440 \
# RRA:AVERAGE:0.5:10:4320 \
# RRA:MIN:0.5:10:4320 \
# RRA:MAX:0.5:10:4320 \
# RRA:AVERAGE:0.5:60:8760 \
# RRA:MIN:0.5:60:8760 \
# RRA:MAX:0.5:60:8760 \
# RRA:AVERAGE:0.5:1440:3650 \
# RRA:MIN:0.5:1440:3650 \
# RRA:MAX:0.5:1440:3650 \
# #1 minute avg for 1 day
# #10 minute average for 30 days
# #1 hour avg for 1 year
# #1 day average for 10 years

# station="dc"
# sensor="temperature"
# minimum="-20"
# maximum="100"

# rrdtool create $station-$sensor.rrd --step 60 --start "-15 years", \
# DS:$sensor:GAUGE:3600:$minimum:$maximum \
# RRA:AVERAGE:0.5:1:1440 \
# RRA:MIN:0.5:1:1440 \
# RRA:MAX:0.5:1:1440 \
# RRA:AVERAGE:0.5:10:4320 \
# RRA:MIN:0.5:10:4320 \
# RRA:MAX:0.5:10:4320 \
# RRA:AVERAGE:0.5:60:8760 \
# RRA:MIN:0.5:60:8760 \
# RRA:MAX:0.5:60:8760 \
# RRA:AVERAGE:0.5:1440:3650 \
# RRA:MIN:0.5:1440:3650 \
# RRA:MAX:0.5:1440:3650

# station="ee"
# sensor="temperature"
# minimum="-20"
# maximum="100"

# rrdtool create $station-$sensor.rrd --step 60 --start "-15 years", \
# DS:$sensor:GAUGE:3600:$minimum:$maximum \
# RRA:AVERAGE:0.5:1:1440 \
# RRA:MIN:0.5:1:1440 \
# RRA:MAX:0.5:1:1440 \
# RRA:AVERAGE:0.5:10:4320 \
# RRA:MIN:0.5:10:4320 \
# RRA:MAX:0.5:10:4320 \
# RRA:AVERAGE:0.5:60:8760 \
# RRA:MIN:0.5:60:8760 \
# RRA:MAX:0.5:60:8760 \
# RRA:AVERAGE:0.5:1440:3650 \
# RRA:MIN:0.5:1440:3650 \
# RRA:MAX:0.5:1440:3650

# station="mm"
# sensor="temperature"
# minimum="-20"
# maximum="100"

# rrdtool create $station-$sensor.rrd --step 60 --start "-15 years", \
# DS:$sensor:GAUGE:3600:$minimum:$maximum \
# RRA:AVERAGE:0.5:1:1440 \
# RRA:MIN:0.5:1:1440 \
# RRA:MAX:0.5:1:1440 \
# RRA:AVERAGE:0.5:10:4320 \
# RRA:MIN:0.5:10:4320 \
# RRA:MAX:0.5:10:4320 \
# RRA:AVERAGE:0.5:60:8760 \
# RRA:MIN:0.5:60:8760 \
# RRA:MAX:0.5:60:8760 \
# RRA:AVERAGE:0.5:1440:3650 \
# RRA:MIN:0.5:1440:3650 \
# RRA:MAX:0.5:1440:3650

# station="mr"
# sensor="temperature"
# minimum="-20"
# maximum="100"

# rrdtool create $station-$sensor.rrd --step 60 --start "-15 years", \
# DS:$sensor:GAUGE:3600:$minimum:$maximum \
# RRA:AVERAGE:0.5:1:1440 \
# RRA:MIN:0.5:1:1440 \
# RRA:MAX:0.5:1:1440 \
# RRA:AVERAGE:0.5:10:4320 \
# RRA:MIN:0.5:10:4320 \
# RRA:MAX:0.5:10:4320 \
# RRA:AVERAGE:0.5:60:8760 \
# RRA:MIN:0.5:60:8760 \
# RRA:MAX:0.5:60:8760 \
# RRA:AVERAGE:0.5:1440:3650 \
# RRA:MIN:0.5:1440:3650 \
# RRA:MAX:0.5:1440:3650



station="rm"
sensor="temperature"
minimum="-20"
maximum="100"

rrdtool create $station-$sensor.rrd --step 60 --start "-15 years", \
DS:$sensor:GAUGE:3600:$minimum:$maximum \
RRA:AVERAGE:0.5:1:1440 \
RRA:MIN:0.5:1:1440 \
RRA:MAX:0.5:1:1440 \
RRA:AVERAGE:0.5:10:4320 \
RRA:MIN:0.5:10:4320 \
RRA:MAX:0.5:10:4320 \
RRA:AVERAGE:0.5:60:8760 \
RRA:MIN:0.5:60:8760 \
RRA:MAX:0.5:60:8760 \
RRA:AVERAGE:0.5:1440:3650 \
RRA:MIN:0.5:1440:3650 \
RRA:MAX:0.5:1440:3650
