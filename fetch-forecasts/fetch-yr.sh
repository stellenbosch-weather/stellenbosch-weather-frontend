#!/bin/bash

# Coordinates: -33.92867437486572, 18.865371933386907
LAT="-33.92867"
LON="18.86537"

API_URL="https://api.met.no/weatherapi/locationforecast/2.0/complete"

# Determine the directory this script resides in (works from cron too)
SCRIPT_DIR="$(cd -P -- "$(dirname -- "$0")" && pwd)"
OUTPUT_FILE="${SCRIPT_DIR}/locationforecast_complete.json"

# MET Norway requires an identifying User-Agent
USER_AGENT="stellenbosch-weather-client/1.0 your-email@example.com"

# Build the full URL with query parameters
FULL_URL="${API_URL}?lat=${LAT}&lon=${LON}"

echo "Fetching MET Norway locationforecast (complete) for lat=${LAT}, lon=${LON}"
echo "URL: ${FULL_URL}"

wget \
  --header="User-Agent: ${USER_AGENT}" \
  --header="Accept: application/json" \
  -O "${OUTPUT_FILE}" \
  "${FULL_URL}"

if [ $? -eq 0 ]; then
  echo "Download complete. Saved to ${OUTPUT_FILE}"
else
  echo "Download failed." >&2
  exit 1
fi
