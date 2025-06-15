#!/bin/bash
#schedule this file for minutely execution by cron
# /home/weather/generateRRDs/mm-temperature.py
# /home/weather/generateRRDs/mr-temperature.py
# /home/weather/generateRRDs/ee-temperature.py
/home/weather/generateRRDs/sb-temperature.py
# /home/weather/generateRRDs/rm-temperature.py
/home/weather/generateRRDs/dc-temperature.py

# /home/weather/generateRRDs/mm-humidity.py
# /home/weather/generateRRDs/mr-humidity.py
# /home/weather/generateRRDs/ee-humidity.py
/home/weather/generateRRDs/sb-humidity.py
# /home/weather/generateRRDs/rm-humidity.py
/home/weather/generateRRDs/dc-humidity.py

# /home/weather/generateRRDs/mm-barometer.py
# /home/weather/generateRRDs/mr-barometer.py
# /home/weather/generateRRDs/ee-barometer.py
/home/weather/generateRRDs/sb-barometer.py
/home/weather/generateRRDs/dc-barometer.py

# /home/weather/generateRRDs/mm-windspeed.py # something wrong
# /home/weather/generateRRDs/mr-windspeed.py # something wrong
# /home/weather/generateRRDs/ee-windspeed.py # something wrong
/home/weather/generateRRDs/sb-windspeed.py

# /home/weather/generateRRDs/mm-winddir.py
# /home/weather/generateRRDs/mr-winddir.py
# /home/weather/generateRRDs/ee-winddir.py
/home/weather/generateRRDs/sb-winddir.py

# /home/weather/generateRRDs/mm-winddirscaled.py
# /home/weather/generateRRDs/mr-winddirscaled.py
# /home/weather/generateRRDs/ee-winddirscaled.py
/home/weather/generateRRDs/sb-winddirscaled.py

# /home/weather/generateRRDs/mm-solar.py
/home/weather/generateRRDs/sb-solar.py
/home/weather/generateRRDs/sb-uv.py


# rem=$(( $(date +%M) % 5 ))

# if [ $rem -eq 0 ]
# then
#rain updaters
# /home/weather/generateRRDs/rm-rain-year.py
# /home/weather/generateRRDs/dc-rain-year.py
# /home/weather/generateRRDs/mr-rain-year.py
# /home/weather/generateRRDs/rm-rain-month.py
# /home/weather/generateRRDs/dc-rain-month.py
# /home/weather/generateRRDs/mr-rain-month.py
# /home/weather/generateRRDs/rm-rain-rate.py
# /home/weather/generateRRDs/dc-rain-rate.py
# /home/weather/generateRRDs/mr-rain-rate.py
# fi
