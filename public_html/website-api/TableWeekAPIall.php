<?php
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
	$user="weatherclient";
	$password="REDACTED";
	$database="weather";
	mysql_connect('localhost',$user,$password);
	mysql_select_db($database);
$time_start = microtime_float();
# Latest entry from EE weather station
	#$query="SELECT * FROM `WeatherEE` WHERE `date` = date(NOW()) AND `time`=(SELECT max(`time`) FROM `WeatherEE` WHERE `date` = date(NOW())) LIMIT 1";
	$query="SELECT * FROM `WeatherEE` WHERE 1 ORDER BY `ID` DESC LIMIT 1";
	$result = mysql_query($query);
	$latestEE = (mysql_fetch_assoc($result));

# Latest entry from MM weather station
	#$query="SELECT * FROM `WeatherMM` WHERE `date` = date(NOW()) AND `time`=(select max(`time`) FROM `WeatherMM` WHERE `date` = date(NOW())) LIMIT 1";
	$query="SELECT * FROM `WeatherMM` WHERE 1 ORDER BY `ID` DESC LIMIT 1";
	$result = mysql_query($query);
	$latestMM = mysql_fetch_assoc($result);


# EE avg temp for week
	$query="SELECT avg(tempout) AS avg FROM WeatherEE WHERE `date` >= DATE_SUB( CURDATE( ) , INTERVAL 7 DAY )";
	$result = mysql_fetch_assoc(mysql_query($query));
	$avgtempEE = round($result["avg"],1);

# MM avg temp for week
	$query="SELECT avg(tempout) AS avg FROM WeatherMM WHERE `date` >= DATE_SUB( CURDATE( ) , INTERVAL 7 DAY )";
	$result = mysql_fetch_assoc(mysql_query($query));
	$avgtempMM = round($result["avg"],1);

# EE max temp for week
	$query="SELECT max(tempout) AS max FROM WeatherEE WHERE `date` >= DATE_SUB( CURDATE( ) , INTERVAL 7 DAY )";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxtempEE = round($result["max"],1);

# EE max temp time
	$query="SELECT * FROM WeatherEE WHERE `date` >= DATE_SUB( CURDATE( ) , INTERVAL 7 DAY ) AND `tempout` LIKE '".$maxtempEE."' LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxtemptimeEE = $result["time"];

# MM max temp for week
	$query="SELECT max(tempout) AS max FROM WeatherMM WHERE `date` >= DATE_SUB( CURDATE( ) , INTERVAL 7 DAY )";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxtempMM = round($result["max"],1);

# MM max temp time
	$query="SELECT * FROM WeatherMM WHERE `date` >= DATE_SUB( CURDATE( ) , INTERVAL 7 DAY ) AND `tempout` LIKE '".$maxtempMM."' LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxtemptimeMM = $result["time"];

# EE min temp for week
	$query="SELECT min(tempout) AS min FROM WeatherEE WHERE `date` >= DATE_SUB( CURDATE( ) , INTERVAL 7 DAY )";
	$result = mysql_fetch_assoc(mysql_query($query));
	$mintempEE = round($result["min"],1);

# EE min temp time
	$query="SELECT * FROM WeatherEE WHERE `date` >= DATE_SUB( CURDATE( ) , INTERVAL 7 DAY ) AND `tempout` LIKE '".$mintempEE."' LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$mintemptimeEE = $result["time"];

# MM min temp for today
	$query="SELECT min(tempout) AS min FROM WeatherMM WHERE `date` >= DATE_SUB( CURDATE( ) , INTERVAL 7 DAY )";
	$result = mysql_fetch_assoc(mysql_query($query));
	$mintempMM = round($result["min"],1);

# MM min temp time
	$query="SELECT * FROM WeatherMM WHERE `date` >= DATE_SUB( CURDATE( ) , INTERVAL 7 DAY ) AND `tempout` LIKE '".$mintempMM."' LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$mintemptimeMM = $result["time"];


# Average windspeed and direction for EE
	$query = "SELECT `windspeed`,`direction` FROM `WeatherEE` WHERE `date` = date(NOW()) ORDER BY `time` DESC LIMIT 10";
	$result = mysql_query($query);
	$windTotal = 0.0;
	$dirTotal = 0.0;
	$samples = 0;
	$dirsamples = 0;
	while ($row = mysql_fetch_assoc($result)) {
		if ($row["windspeed"] != null) { 
			$samples = $samples + 1;
			$windTotal = $windTotal + $row["windspeed"]; 
		
			if ($row["direction"] != null) {
				$dirsamples = $dirsamples + 1;
				$dirTotal = $dirTotal + ($row["direction"] == "N" ? 0 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "E" ? 90 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "S" ? 180 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "W" ? 270 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "NE" ? 45 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "SE" ? 135 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "SW" ? 225 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "NW" ? 315 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "NNE" ? 22.5 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "ENE" ? 67.5 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "ESE" ? 112.5 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "SSE" ? 157.5 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "SSW" ? 202.5 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "WSW" ? 247.5 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "WNW" ? 292.5 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "NNW" ? 337.5 : 0);
			}
		}
	}
	if ($samples != 0) {
		$avgWind10EE = round($windTotal / $samples, 2);
		$avgDir10EE = array("N","NNE","NE","ENE","E","ESE","SE","SSE","S","SSW","SW","WSW","W","WNW","NW","NNW");
		$avgDir10EE = $avgDir10EE[round($dirTotal/$samples/22.5,0)]; # Get average direction in degrees, then devide by sector size to get sector index.
	}

# Average windspeed and direction for MM
	$query = "SELECT `windspeed`,`direction` FROM `WeatherMM` WHERE `date` = date(NOW()) ORDER BY `time` DESC LIMIT 10";
	$result = mysql_query($query);
	$windTotal = 0.0;
	$dirTotal = 0.0;
	$samples = 0;
	$dirsamples = 0;
	while ($row = mysql_fetch_assoc($result)) {
		if ($row["windspeed"] != null) { 
			$samples = $samples + 1;
			$windTotal = $windTotal + $row["windspeed"]; 
		
			if ($row["direction"] != null) {
				$dirsamples = $dirsamples + 1;
				$dirTotal = $dirTotal + ($row["direction"] == "N" ? 0 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "E" ? 90 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "S" ? 180 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "W" ? 270 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "NE" ? 45 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "SE" ? 135 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "SW" ? 225 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "NW" ? 315 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "NNE" ? 22.5 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "ENE" ? 67.5 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "ESE" ? 112.5 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "SSE" ? 157.5 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "SSW" ? 202.5 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "WSW" ? 247.5 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "WNW" ? 292.5 : 0);
				$dirTotal = $dirTotal + ($row["direction"] == "NNW" ? 337.5 : 0);
			}
		}
	}
	if ($samples != 0) {
		$avgWind10MM = round($windTotal / $samples, 2);
		$avgDir10MM = array("N","NNE","NE","ENE","E","ESE","SE","SSE","S","SSW","SW","WSW","W","WNW","NW","NNW");
		$avgDir10MM = $avgDir10MM[round($dirTotal/$samples/22.5,0)]; # Get average direction in degrees, then devide by sector size to get sector index.
	}


# EE max wind for today
	$query="SELECT max(windspeed) AS max FROM WeatherEE WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxWindEE = round($result["max"],1);

# EE max wind time
	$query="SELECT * FROM WeatherEE WHERE `windspeed` LIKE '".$maxWindEE."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxWindTimeEE = $result["time"];
	$maxWindDirEE = $result["direction"];

# MM max wind for today
	$query="SELECT max(windspeed) AS max FROM WeatherMM WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxWindMM = round($result["max"],1);

# MM max wind time
	$query="SELECT * FROM WeatherMM WHERE `windspeed` LIKE '".$maxWindMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxWindTimeMM = $result["time"];
	$maxWindDirMM = $result["direction"];


# EE min windchill for today
	$query="SELECT min(windchill) AS min FROM WeatherEE WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minWindchillEE = round($result["min"],1);

# EE min windchill time
	$query="SELECT * FROM WeatherEE WHERE `windchill` LIKE '".$minWindchillEE."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minWindchillTimeEE = $result["time"];

# MM min windchill for today
	$query="SELECT min(windchill) AS min FROM WeatherMM WHERE `date` = date(now())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minWindchillMM = round($result["min"],1);

# MM min windchill time
	$query="SELECT * FROM WeatherMM WHERE `windchill` LIKE '".$minWindchillMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minWindchillTimeMM = $result["time"];


# EE min dewpoint for today
	$query="SELECT min(dew) AS min FROM WeatherEE WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$mindewEE = round($result["min"],1);

# EE min dewpoint time
	$query="SELECT * FROM WeatherEE WHERE `dew` LIKE '".$mindewEE."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$mindewtimeEE = $result["time"];

# MM min dewpoint for today
	$query="SELECT min(dew) AS min FROM WeatherMM WHERE `date` = date(now())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$mindewMM = round($result["min"],1);

# MM min dewpoint time
	$query="SELECT * FROM WeatherMM WHERE `dew` LIKE '".$mindewMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$mindewtimeMM = $result["time"];


# EE max dewpoint for today
	$query="SELECT max(dew) AS max FROM WeatherEE WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxdewEE = round($result["max"],1);

# EE max dewpoint time
	$query="SELECT * FROM WeatherEE WHERE `dew` LIKE '".$maxdewEE."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxdewtimeEE = $result["time"];

# MM max dewpoint for today
	$query="SELECT max(dew) AS max FROM WeatherMM WHERE `date` = date(now())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxdewMM = round($result["max"],1);

# MM max dewpoint time
	$query="SELECT * FROM WeatherMM WHERE `dew` LIKE '".$maxdewMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxdewtimeMM = $result["time"];


# EE total rain day
	$query="SELECT sum(rain) AS totalRain FROM WeatherEE WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$rainEE = $result["totalRain"];

# MM total rain day
	$query="SELECT sum(rain) AS totalRain FROM WeatherMM WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$rainMM = $result["totalRain"];


# EE max rainrate for today
	$query="SELECT max(rainrate) AS max FROM WeatherEE WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxrainrateEE = round($result["max"],1);

# EE max rainrate time
	$query="SELECT * FROM WeatherEE WHERE `rainrate` LIKE '".$maxrainrateEE."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxrainratetimeEE = $result["time"];

# MM max rainrate for today
	$query="SELECT max(rainrate) AS max FROM WeatherMM WHERE `date` = date(now())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxrainrateMM = round($result["max"],1);

# MM max rainrate time
	$query="SELECT * FROM WeatherMM WHERE `rainrate` LIKE '".$maxrainrateMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxrainratetimeMM = $result["time"];

# MM total ET day
	$query="SELECT sum(ET) AS totalET FROM WeatherMM WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$etMM = $result["totalET"];


# EE min humidity for today
	$query="SELECT min(outhum) AS min FROM WeatherEE WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minhumEE = round($result["min"],1);

# EE min humidity time
	$query="SELECT * FROM WeatherEE WHERE `outhum` LIKE '".$minhumEE."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minhumtimeEE = $result["time"];

# MM min humidity for today
	$query="SELECT min(outhum) AS min FROM WeatherMM WHERE `date` = date(now())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minhumMM = round($result["min"],1);

# MM min humidity time
	$query="SELECT * FROM WeatherMM WHERE `outhum` LIKE '".$minhumMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minhumtimeMM = $result["time"];


# EE max humidity for today
	$query="SELECT max(outhum) AS max FROM WeatherEE WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxhumEE = round($result["max"],1);

# EE max humidity time
	$query="SELECT * FROM WeatherEE WHERE `outhum` LIKE '".$maxhumEE."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxhumtimeEE = $result["time"];

# MM max humidity for today
	$query="SELECT max(outhum) AS max FROM WeatherMM WHERE `date` = date(now())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxhumMM = round($result["max"],1);

# MM max humidity time
	$query="SELECT * FROM WeatherMM WHERE `outhum` LIKE '".$maxhumMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxhumtimeMM = $result["time"];


# EE min barometer for today
	$query="SELECT min(bar) AS min FROM WeatherEE WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minbarEE = round($result["min"],1);

# EE min barometer time
	$query="SELECT * FROM WeatherEE WHERE `bar` LIKE '".$minbarEE."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minbartimeEE = $result["time"];

# MM min barometer for today
	$query="SELECT min(bar) AS min FROM WeatherMM WHERE `date` = date(now())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minbarMM = round($result["min"],1);

# MM min barometer time
	$query="SELECT * FROM WeatherMM WHERE `bar` LIKE '".$minbarMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minbartimeMM = $result["time"];


# EE max barometer for today
	$query="SELECT max(bar) AS max FROM WeatherEE WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxbarEE = round($result["max"],1);

# EE max barometer time
	$query="SELECT * FROM WeatherEE WHERE `bar` LIKE '".$maxbarEE."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxbartimeEE = $result["time"];

# MM max barometer for today
	$query="SELECT max(bar) AS max FROM WeatherMM WHERE `date` = date(now())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxbarMM = round($result["max"],1);

# MM max barometer time
	$query="SELECT * FROM WeatherMM WHERE `bar` LIKE '".$maxbarMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxbartimeMM = $result["time"];


# EE min heat index for today
	$query="SELECT min(heatindex) AS min FROM WeatherEE WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minHeatEE = round($result["min"],1);

# EE min heat index time
	$query="SELECT * FROM WeatherEE WHERE `heatindex` LIKE '".$minHeatEE."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minHeatTimeEE = $result["time"];

# MM min heat index for today
	$query="SELECT min(heatindex) AS min FROM WeatherMM WHERE `date` = date(now())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minHeatMM = round($result["min"],1);

# MM min heat index time
	$query="SELECT * FROM WeatherMM WHERE `heatindex` LIKE '".$minHeatMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minHeatTimeMM = $result["time"];


# EE max heat index for today
	$query="SELECT max(heatindex) AS max FROM WeatherEE WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxHeatEE = round($result["max"],1);

# EE max heat index time
	$query="SELECT * FROM WeatherEE WHERE `heatindex` LIKE '".$maxHeatEE."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxHeatTimeEE = $result["time"];

# MM max heat index for today
	$query="SELECT max(heatindex) AS max FROM WeatherMM WHERE `date` = date(now())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxHeatMM = round($result["max"],1);

# MM max heat index time
	$query="SELECT * FROM WeatherMM WHERE `heatindex` LIKE '".$maxHeatMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxHeatTimeMM = $result["time"];


# EE max thw for today
	$query="SELECT max(THWindex) AS max FROM WeatherEE WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxthwEE = round($result["max"],1);

# EE max thw time
	$query="SELECT * FROM WeatherEE WHERE `THWindex` LIKE '".$maxthwEE."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxTHWtimeEE = $result["time"];

# MM max thsw for today
	$query="SELECT max(THSWindex) AS max FROM WeatherMM WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxthswMM = round($result["max"],1);

# MM max thsw time
	$query="SELECT * FROM WeatherMM WHERE `THSWindex` LIKE '".$maxthswMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxTHSWtimeMM = $result["time"];

# MM max thw for today
	$query="SELECT max(THW) AS max FROM WeatherMM WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxthwMM = round($result["max"],1);

# MM max thw time
	$query="SELECT * FROM WeatherMM WHERE `THW` LIKE '".$maxthwMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxTHWtimeMM = $result["time"];


# EE min thw for today
	$query="SELECT min(THWindex) AS min FROM WeatherEE WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minthwEE = round($result["min"],1);

# EE min thw time
	$query="SELECT * FROM WeatherEE WHERE `THWindex` LIKE '".$minthwEE."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minTHWtimeEE = $result["time"];

# MM min thsw for today
	$query="SELECT min(THSWindex) AS min FROM WeatherMM WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minthswMM = round($result["min"],1);

# MM min thsw time
	$query="SELECT * FROM WeatherMM WHERE `THSWindex` LIKE '".$minthswMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minTHSWtimeMM = $result["time"];

# MM min thw for today
	$query="SELECT min(THW) AS min FROM WeatherMM WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minthwMM = round($result["min"],1);

# MM min thw time
	$query="SELECT * FROM WeatherMM WHERE `THW` LIKE '".$minthwMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minTHWtimeMM = $result["time"];


# MM max solarrad for today
	$query="SELECT max(solarrad) AS max FROM WeatherMM WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxSolarradMM = round($result["max"],1);

# MM max solarrad time
	$query="SELECT * FROM WeatherMM WHERE `solarrad` LIKE '".$maxSolarradMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxSolarradTimeMM = $result["time"];

# MM max solarenergy for today
	$query="SELECT max(solarenergy) AS max FROM WeatherMM WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxSolarEnergyMM = round($result["max"],2);

# MM max solarenergy time
	$query="SELECT * FROM WeatherMM WHERE `solarenergy` LIKE '".$maxSolarEnergyMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxSolarEnergyTimeMM = $result["time"];


# MM max UV index for today
	$query="SELECT max(UV) AS max FROM WeatherMM WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxUVindexMM = round($result["max"],1);

# MM max UV index time
	$query="SELECT * FROM WeatherMM WHERE `UV` LIKE '".$maxUVindexMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxUVindexTimeMM = $result["time"];

# MM max UV dose for today
	$query="SELECT max(UVdose) AS max FROM WeatherMM WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxUVdoseMM = round($result["max"],2);

# MM max UV dose time
	$query="SELECT * FROM WeatherMM WHERE `UVdose` LIKE '".$maxUVdoseMM."' AND `date` = date(NOW()) LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxUVdoseTimeMM = $result["time"];



	mysql_close();



# Nie in die Databasis nie
	$htmlEE=file_get_contents('/home/weather/public_html/weatherlinkEE/datastrings.htm');
	$valuesEE=explode("\n", $htmlEE);
	$baroTrendEE = $valuesEE[1];
	$quickforecastEE = $valuesEE[0];
	$htmlMM=file_get_contents('/home/weather/public_html/weatherlink/templates/datastrings.htm');
	$valuesMM=explode("\n", $htmlMM);
	$baroTrendMM = $valuesMM[1];
	$quickforecastMM = $valuesMM[0];

	$time_end = microtime_float();
	$time = $time_end - $time_start;

	echo "<small>Page loaded in ".round($time,2)." seconds\n</small>";
?>

