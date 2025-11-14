#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import urllib.request, urllib.parse, urllib.error
import sys

API_URL = "https://api.met.no/weatherapi/locationforecast/2.0/complete"

# Hardcoded coordinates for 34° S, 19° E
LATITUDE = -34.0
LONGITUDE = 19.0

# Per MET Norway’s requirements, identify your application with a User-Agent
USER_AGENT = "weather.sun.ac.za/1.0 stellenboschweather@jpmeijers.com"

# Output file for raw JSON
OUTPUT_FILE = "locationforecast_complete.json"


def fetch_forecast(lat, lon):
    """
    Fetch the complete location forecast for the given coordinates.
    Returns the raw response body (bytes / str in Py2).
    """
    query_params = {
        "lat": str(lat),
        "lon": str(lon),
    }
    url = API_URL + "?" + urllib.parse.urlencode(query_params)

    headers = {
        "User-Agent": USER_AGENT,
        "Accept": "application/json",
    }

    request = urllib.request.Request(url, headers=headers)

    try:
        response = urllib.request.urlopen(request, timeout=15)
        body = response.read()
        response.close()
        return body
    except urllib.error.HTTPError as e:
        sys.stderr.write("HTTP error: %s (status code: %s)\n" % (e.reason, e.code))
        sys.exit(1)
    except urllib.error.URLError as e:
        sys.stderr.write("URL error: %s\n" % e.reason)
        sys.exit(1)


def write_to_file(path, data):
    """
    Write raw data to a file.
    """
    try:
        # Open in binary mode to avoid any encoding issues
        with open(path, "wb") as f:
            f.write(data)
    except OSError as e:
        sys.stderr.write("Failed to write file %s: %s\n" % (path, e))
        sys.exit(1)


def main():
    sys.stdout.write(
        "Fetching MET Norway locationforecast (complete) for lat=%s, lon=%s\n"
        % (LATITUDE, LONGITUDE)
    )

    raw_data = fetch_forecast(LATITUDE, LONGITUDE)

    write_to_file(OUTPUT_FILE, raw_data)

    sys.stdout.write("Raw JSON response written to %s\n" % OUTPUT_FILE)


if __name__ == "__main__":
    main()