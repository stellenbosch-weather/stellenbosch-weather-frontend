<?php
/**
 * Simple script to read MET Norway Locationforecast 2.0 (complete) JSON
 * from local disk and render a basic HTML table.
 *
 * Requirements:
 * - PHP 7+
 * - A JSON file downloaded from:
 *   https://api.met.no/weatherapi/locationforecast/2.0/complete
 */

// ---------------------------------------------------------------------
// Configuration
// ---------------------------------------------------------------------

// Path to the JSON file (adjust this to your actual location)
$jsonFile = __DIR__ . '/../fetch-forecasts/locationforecast_complete.json';

// Directory where MET Norway weather icons are stored
$iconDir     = __DIR__ . '/sources/metno-weather-icons/weather/svg/';
$iconUrlBase = 'sources/metno-weather-icons/weather/svg/';

// Timezone for displaying timestamps
date_default_timezone_set('UTC');

// ---------------------------------------------------------------------
// Helper: resolve icon URL for a MET symbol code
// ---------------------------------------------------------------------

/**
 * @param string $symbolCode
 * @param string $iconDir
 * @param string $iconUrlBase
 * @return string|null  Relative URL to icon, or null if not found
 */
function getSymbolIconUrl($symbolCode, $iconDir, $iconUrlBase)
{
    if ($symbolCode === '') {
        return null;
    }

    // MET symbol codes are typically lowercase already, but normalize anyway
    $base = strtolower($symbolCode);

    // Try common extensions in order of preference
    $extensions = array('.svg', '.png', '.gif');

    foreach ($extensions as $ext) {
        $fsPath = $iconDir . $base . $ext;
        if (file_exists($fsPath)) {
            return $iconUrlBase . $base . $ext;
        }
    }

    return null;
}

// ---------------------------------------------------------------------
// Load and decode JSON
// ---------------------------------------------------------------------

if (!file_exists($jsonFile)) {
    die('JSON file not found: ' . htmlspecialchars($jsonFile));
}

$jsonContent = file_get_contents($jsonFile);
if ($jsonContent === false) {
    die('Failed to read JSON file: ' . htmlspecialchars($jsonFile));
}

$data = json_decode($jsonContent, true);
if ($data === null) {
    die('Failed to decode JSON (invalid JSON).');
}

// ---------------------------------------------------------------------
// Validate structure (GeoJSON-like from MET)
// ---------------------------------------------------------------------

if (
    !isset($data['properties']) ||
    !isset($data['properties']['timeseries']) ||
    !is_array($data['properties']['timeseries'])
) {
    die('Unexpected JSON structure: properties.timeseries not found.');
}

$timeseries = $data['properties']['timeseries'];

// ---------------------------------------------------------------------
// Prepare rows
// ---------------------------------------------------------------------
//
// We’ll extract for each time step:
// - Time
// - Air temperature (instant.details.air_temperature)
// - Wind speed (instant.details.wind_speed)
// - Precipitation (next_1_hours.details.precipitation_amount if available)
// - Weather symbol (next_1_hours.summary.symbol_code if available)
//

$rows = [];

foreach ($timeseries as $entry) {
    if (!isset($entry['time']) || !isset($entry['data']) || !is_array($entry['data'])) {
        continue;
    }

    $timeIso = $entry['time'];

    $instantDetails   = isset($entry['data']['instant']['details'])
        ? $entry['data']['instant']['details']
        : [];

    $next1h           = isset($entry['data']['next_1_hours'])
        ? $entry['data']['next_1_hours']
        : [];

    $next1hDetails    = isset($next1h['details']) ? $next1h['details'] : [];
    $next1hSummary    = isset($next1h['summary']) ? $next1h['summary'] : [];

    // Extract values with fallbacks
    $airTemp          = isset($instantDetails['air_temperature']) ? $instantDetails['air_temperature'] : null;
    $windSpeed        = isset($instantDetails['wind_speed']) ? $instantDetails['wind_speed'] : null;
    $precip1h         = isset($next1hDetails['precipitation_amount']) ? $next1hDetails['precipitation_amount'] : null;
    $symbolCode       = isset($next1hSummary['symbol_code']) ? $next1hSummary['symbol_code'] : '';

    $rows[] = [
        'time_iso'   => $timeIso,
        'air_temp'   => $airTemp,
        'wind_speed' => $windSpeed,
        'precip_1h'  => $precip1h,
        'symbol'     => $symbolCode,
    ];
}

// Optional: limit the number of rows shown
$maxRowsToDisplay = 100;
if (count($rows) > $maxRowsToDisplay) {
    $rows = array_slice($rows, 0, $maxRowsToDisplay);
}

// ---------------------------------------------------------------------
// Render HTML
// ---------------------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weather Forecast Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            margin: 1rem 0;
            width: 100%;
            max-width: 1200px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 0.4rem 0.6rem;
            text-align: center;
            font-size: 0.9rem;
        }
        th {
            background-color: #f5f5f5;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        caption {
            caption-side: top;
            text-align: left;
            font-weight: bold;
            margin-bottom: 0.4rem;
        }
        .weather-icon {
            width: 40px;
            height: 40px;
        }
    </style>
</head>
<body>

<h1>Weather Forecast Summary</h1>

<table>
    <caption>Based on MET Norway Locationforecast (complete)</caption>
    <thead>
    <tr>
        <th>Time (UTC)</th>
        <th>Air Temp (°C)</th>
        <th>Wind Speed (m/s)</th>
        <th>Precipitation next 1h (mm)</th>
        <th>Weather</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($rows)): ?>
        <tr>
            <td colspan="5">No forecast data available.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td>
                    <?php
                    // Format ISO time to something readable
                    $time = strtotime($row['time_iso']);
                    echo $time !== false ? date('Y-m-d H:i', $time) : htmlspecialchars($row['time_iso']);
                    ?>
                </td>
                <td>
                    <?php
                    echo $row['air_temp'] !== null
                        ? htmlspecialchars(number_format($row['air_temp'], 1))
                        : '&ndash;';
                    ?>
                </td>
                <td>
                    <?php
                    echo $row['wind_speed'] !== null
                        ? htmlspecialchars(number_format($row['wind_speed'], 1))
                        : '&ndash;';
                    ?>
                </td>
                <td>
                    <?php
                    echo $row['precip_1h'] !== null
                        ? htmlspecialchars(number_format($row['precip_1h'], 1))
                        : '&ndash;';
                    ?>
                </td>
                <td>
                    <?php
                    $symbolCode = $row['symbol'];
                    $iconUrl    = getSymbolIconUrl($symbolCode, $iconDir, $iconUrlBase);

                    if ($iconUrl !== null): ?>
                        <img
                            src="<?php echo htmlspecialchars($iconUrl); ?>"
                            alt="<?php echo htmlspecialchars($symbolCode); ?>"
                            title="<?php echo htmlspecialchars($symbolCode); ?>"
                            class="weather-icon"
                        />
                    <?php else: ?>
                        <?php echo htmlspecialchars($symbolCode !== '' ? $symbolCode : ''); ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>