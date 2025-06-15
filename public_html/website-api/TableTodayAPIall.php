<?php
include("createDatabaseConnection.php");

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();



$workingWeatherStationCount = 0;

# Latest entry from EE weather station
$query = "SELECT MAX(`time`) FROM `WeatherEE` WHERE `date` = DATE(NOW())";
$result = mysql_query($query);
if(mysql_num_rows($result)==0 or mysql_result($result,0)==NULL)
{
  $timesEE=0;
  $latestEE="";
}
else
{
  $workingWeatherStationCount++;

  $timeEE = mysql_result($result,0);

  $query = "SELECT * FROM `WeatherEE` WHERE `date` = DATE(NOW()) AND `time` = '$timeEE'";
  $result = mysql_query($query);
  $latestEE = (mysql_fetch_assoc($result));
}

# Latest entry from MM weather station
$query = "SELECT MAX(`time`) FROM `WeatherMM` WHERE `date` = DATE(NOW())";
$result = mysql_query($query);
if(mysql_num_rows($result)==0 or mysql_result($result,0)==NULL)
{
  $timesMM=0;
  $latestMM="";
}
else
{
  $workingWeatherStationCount++;

  $timeMM = mysql_result($result,0);

  $query = "SELECT * FROM `WeatherMM` WHERE `date` = DATE(NOW()) AND `time` = '$timeMM'";
  $result = mysql_query($query);
  $latestMM = (mysql_fetch_assoc($result));
}

#Latest entry from Sonbesie
$query = "SELECT MAX(`Time`) FROM `SB_TMin` WHERE `Date` = DATE(NOW())";
$result = mysql_query($query);
if(mysql_num_rows($result)==0 or mysql_result($result,0)==NULL)
{
  $timesSB=0;
  $latestSB="";
}
else
{
  $workingWeatherStationCount++;
  
  $timeSB = mysql_result($result,0);

  $query = "SELECT * FROM `SB_TMin` WHERE `Date` = DATE(NOW()) AND `Time` = '$timeSB'";
  $result = mysql_query($query);
  $latestSB = (mysql_fetch_assoc($result));
}



# EE max temp for today
$query="SELECT MAX(`tempout`) FROM `WeatherEE` WHERE `date` = DATE(NOW())";
$result=mysql_query($query);
if(mysql_num_rows($result)==0 or mysql_result($result,0)==NULL)
{
  $maxtempEE="";
  $maxtemptimeEE="";
}
else
{
  $maxtempEE = mysql_result($result,0);
  $query="SELECT `time` FROM `WeatherEE` WHERE `date`= DATE(NOW()) 
          AND ABS(`tempout`-$maxtempEE) < 0.001
          LIMIT 1"; // floating points are inacurate
  $result = mysql_fetch_assoc(mysql_query($query));
  $maxtemptimeEE = $result["time"];
  $maxtempEE = round($maxtempEE,2);
}

# MM max temp for today
$query="SELECT MAX(`tempout`) FROM `WeatherMM` WHERE `date` = DATE(NOW())";
$result=mysql_query($query);
if(mysql_num_rows($result)==0 or mysql_result($result,0)==NULL)
{
  $maxtempMM="";
  $maxtemptimeMM="";
}
else
{
  $maxtempMM = mysql_result($result,0);
  $query="SELECT `time` FROM `WeatherMM` WHERE `date`= DATE(NOW()) 
          AND ABS(`tempout`-$maxtempMM) < 0.001
          LIMIT 1"; // floating points are inacurate
  $result = mysql_fetch_assoc(mysql_query($query));
  $maxtemptimeMM = $result["time"];
  $maxtempMM = round($maxtempMM,2);
}
	
# SB max temp for today
$query="SELECT MAX(`AirTC_Avg`) FROM `SB_TMin` WHERE `Date` = DATE(NOW())";
$result=mysql_query($query);
if(mysql_num_rows($result)==0 or mysql_result($result,0)==NULL)
{
  $maxtempSB="";
  $maxtemptimeSB="";
}
else
{
  $maxtempSB = mysql_result($result,0);
  $query="SELECT `Time` FROM `SB_TMin` WHERE `Date`= DATE(NOW()) 
          AND ABS(`AirTC_Avg`-$maxtempSB) < 0.001
          LIMIT 1"; // floating points are inacurate
  $result = mysql_fetch_assoc(mysql_query($query));
  $maxtemptimeSB = $result["Time"];
  $maxtempSB = round($maxtempSB,2);
}



# EE min temp for today
$query="SELECT MIN(`tempout`) FROM `WeatherEE` WHERE `date` = DATE(NOW())";
$result=mysql_query($query);
if(mysql_num_rows($result)==0 or mysql_result($result,0)==NULL)
{
  $mintempEE="";
  $mintemptimeEE="";
}
else
{
  $mintempEE = mysql_result($result,0);
  $query="SELECT `time` FROM `WeatherEE` WHERE `date`= DATE(NOW()) 
          AND ABS(`tempout`-$mintempEE) < 0.001
          LIMIT 1"; // floating points are inacurate
  $result = mysql_fetch_assoc(mysql_query($query));
  $mintemptimeEE = $result["time"];
  $mintempEE = round($mintempEE,2);
}

# MM min temp for today
$query="SELECT MIN(`tempout`) FROM `WeatherMM` WHERE `date` = DATE(NOW())";
$result=mysql_query($query);
if(mysql_num_rows($result)==0 or mysql_result($result,0)==NULL)
{
  $mintempMM="";
  $mintemptimeMM="";
}
else
{
  $mintempMM = mysql_result($result,0);
  $query="SELECT `time` FROM `WeatherMM` WHERE `date`= DATE(NOW()) 
          AND ABS(`tempout`-$mintempMM) < 0.001
          LIMIT 1"; // floating points are inacurate
  $result = mysql_fetch_assoc(mysql_query($query));
  $mintemptimeMM = $result["time"];
  $mintempMM = round($mintempMM,2);
}
	
# SB min temp for today
$query="SELECT MIN(`AirTC_Avg`) FROM `SB_TMin` WHERE `Date` = DATE(NOW())";
$result=mysql_query($query);
if(mysql_num_rows($result)==0 or mysql_result($result,0)==NULL)
{
  $mintempSB="";
  $mintemptimeSB="";
}
else
{
  $mintempSB = mysql_result($result,0);
  $query="SELECT `Time` FROM `SB_TMin` WHERE `Date`= DATE(NOW()) 
          AND ABS(`AirTC_Avg`-$mintempSB) < 0.001
          LIMIT 1"; // floating points are inacurate
  $result = mysql_fetch_assoc(mysql_query($query));
  $mintemptimeSB = $result["Time"];
  $mintempSB = round($mintempSB,2);
}



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
	}
	if ($dirsamples != 0) {
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
	}
	if ($dirsamples != 0) {
		$avgDir10MM = array("N","NNE","NE","ENE","E","ESE","SE","SSE","S","SSW","SW","WSW","W","WNW","NW","NNW");
		$avgDir10MM = $avgDir10MM[round($dirTotal/$samples/22.5,0)]; # Get average direction in degrees, then devide by sector size to get sector index.
	}


# Average windspeed and direction for SB
	$query = "SELECT `WS_ms_S_WVT`,`WindDir_D1_WVT` FROM `SB_TMin` WHERE `Date` = DATE(NOW()) ORDER BY `Time` DESC LIMIT 10";
	$result = mysql_query($query);
	$windTotal = 0.0;
	$dirTotal = 0.0;
	$samples = 0;
	$dirsamples = 0;
	while ($row = mysql_fetch_assoc($result)) {
		if ($row["WS_ms_S_WVT"] != 0) { 
			$samples = $samples + 1;
			$windTotal = $windTotal + $row["WS_ms_S_WVT"]; 
		
			if ($row["WindDir_D1_WVT"] != 0) {
				$dirsamples = $dirsamples + 1;
				$dirTotal = $dirTotal + $row["WindDir_D1_WVT"];
			}
		}
	}
	if ($samples != 0) {
		//The sonbesie measures in m/s. We watn km/h to be uniform.
		$avgWind10SB = round($windTotal/$samples * 3.6, 2);
	}
	if ($dirsamples != 0) {
		$avgDir10SB = array("N","NNE","NE","ENE","E","ESE","SE","SSE","S","SSW","SW","WSW","W","WNW","NW","NNW");
		$avgDir10SB = $avgDir10SB[round($dirTotal/$dirsamples/22.5,0)]; 
		# Get average direction in degrees, then devide by sector size to get sector index.
	}




# EE max wind for today
	$query="SELECT * FROM WeatherEE WHERE `date`= DATE(NOW()) ORDER BY `hispeed` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxWindEE = $result["hispeed"];
	$maxWindTimeEE = $result["time"];
	$maxWindDirEE = $result["direction"];

# MM max wind for today
	$query="SELECT * FROM WeatherMM WHERE `date`= DATE(NOW()) ORDER BY `hispeed` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxWindMM = $result["hispeed"];
	$maxWindTimeMM = $result["time"];
	$maxWindDirMM = $result["direction"];

# SB max wind for today
	$query="SELECT * FROM SB_TMin WHERE `Date`= DATE(NOW()) ORDER BY `WS_ms_S_WVT` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxWindSB = $result["WS_ms_S_WVT"];
	$maxWindTimeSB = $result["Time"];
	$maxWindDirSB = $result["WindDir_D1_WVT"];
	$dirs = array("N","NNE","NE","ENE","E","ESE","SE","SSE",
                "S","SSW","SW","WSW","W","WNW","NW","NNW");
  $maxWindSB = round($maxWindSB*3.6,2);
  $maxWindDirSB = $dirs[round($maxWindDirSB/22.5)]; 
	
	

# EE min windchill for today
	$query="SELECT * FROM WeatherEE WHERE `date`= DATE(NOW()) ORDER BY `windchill` ASC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minWindchillEE = $result["windhill"];
	$minWindchillTimeEE = $result["time"];

# MM min windchill for today
	$query="SELECT * FROM WeatherMM WHERE `date`= DATE(NOW()) ORDER BY `windchill` ASC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minWindchillMM = $result["windchill"];
	$minWindchillTimeMM = $result["time"];



# EE min dewpoint for today
	$query="SELECT * FROM WeatherEE WHERE `date`= DATE(NOW()) ORDER BY `dew` ASC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$mindewEE = $result["dew"];
	$mindewtimeEE = $result["time"];

# MM min dewpoint for today
	$query="SELECT * FROM WeatherMM WHERE `date`= DATE(NOW()) ORDER BY `dew` ASC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$mindewMM = $result["dew"];
	$mindewtimeMM = $result["time"];

# EE max dewpoint for today
	$query="SELECT * FROM WeatherEE WHERE `date`= DATE(NOW()) ORDER BY `dew` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxdewEE = $result["dew"];
	$maxdewtimeEE = $result["time"];

# MM max dewpoint for today
	$query="SELECT * FROM WeatherMM WHERE `date`= DATE(NOW()) ORDER BY `dew` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxdewMM = $result["dew"];
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
	$query="SELECT * FROM WeatherEE WHERE `date`= DATE(NOW()) ORDER BY `rainrate` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxrainrateEE = $result["rainrate"];
	$maxrainratetimeEE = $result["time"];

# MM max rainrate for today
	$query="SELECT * FROM WeatherMM WHERE `date`= DATE(NOW()) ORDER BY `rainrate` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxrainrateMM = $result["max"];
	$maxrainratetimeMM = $result["time"];


# MM total ET day
	$query="SELECT sum(ET) AS totalET FROM WeatherMM WHERE `date` = date(NOW())";
	$result = mysql_fetch_assoc(mysql_query($query));
	$etMM = $result["totalET"];



# EE min humidity for today
	$query="SELECT * FROM WeatherEE WHERE `date`= DATE(NOW()) ORDER BY `outhum` ASC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minhumEE = $result["outhum"];
	$minhumtimeEE = $result["time"];

# MM min humidity for today
	$query="SELECT * FROM WeatherMM WHERE `date`= DATE(NOW()) ORDER BY `outhum` ASC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minhumMM = round($result["outhum"],1);
	$minhumtimeMM = $result["time"];
	
# SB min humidity for today
	$query="SELECT * FROM SB_TMin WHERE `Date`= DATE(NOW()) ORDER BY `RH` ASC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minhumSB = $result["RH"];
	$minhumtimeSB = $result["Time"];


# EE max humidity for today
	$query="SELECT * FROM WeatherEE WHERE `date`= DATE(NOW()) ORDER BY `outhum` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxhumEE = $result["outhum"];
	$maxhumtimeEE = $result["time"];

# MM max humidity for today
	$query="SELECT * FROM WeatherEE WHERE `date`= DATE(NOW()) ORDER BY `outhum` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxhumMM = $result["outhum"];
	$maxhumtimeMM = $result["time"];
	
# SB max humidity for today
	$query="SELECT * FROM SB_TMin WHERE `Date`= DATE(NOW()) ORDER BY `RH` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxhumSB = $result["RH"];
	$maxhumtimeSB = $result["Time"];



# EE min barometer for today
	$query="SELECT * FROM WeatherEE WHERE `date`= DATE(NOW()) ORDER BY `bar` ASC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minbarEE = $result["bar"];
	$minbartimeEE = $result["time"];

# MM min barometer for today
	$query="SELECT * FROM WeatherMM WHERE `date`= DATE(NOW()) ORDER BY `bar` ASC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minbarMM = $result["bar"];
	$minbartimeMM = $result["time"];

# SB min barometer for today
	$query="SELECT * FROM SB_TMin WHERE `Date`= DATE(NOW()) ORDER BY `BP_mB_Avg` ASC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minbarSB = $result["BP_mB_Avg"];
	$minbartimeSB = $result["Time"];


# EE max barometer for today
	$query="SELECT * FROM WeatherEE WHERE `date`= DATE(NOW()) ORDER BY `bar` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxbarEE = $result["bar"];
	$maxbartimeEE = $result["time"];

# MM max barometer for today
	$query="SELECT * FROM WeatherMM WHERE `date`= DATE(NOW()) ORDER BY `bar` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxbarMM = $result["bar"];
	$maxbartimeMM = $result["time"];

# SB max barometer for today
	$query="SELECT * FROM SB_TMin WHERE `Date`= DATE(NOW()) ORDER BY `BP_mB_Avg` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minbarSB = $result["BP_mB_Avg"];
	$minbartimeSB = $result["Time"];



# EE min heat index for today
	$query="SELECT * FROM WeatherEE WHERE `date`= DATE(NOW()) ORDER BY `heatindex` ASC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minHeatEE = $result["heatindex"];
	$minHeatTimeEE = $result["time"];

# MM min heat index for today
	$query="SELECT * FROM WeatherMM WHERE `date`= DATE(NOW()) ORDER BY `heatindex` ASC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minHeatMM = $result["heatindex"];
	$minHeatTimeMM = $result["time"];


# EE max heat index for today
	$query="SELECT * FROM WeatherEE WHERE `date`= DATE(NOW()) ORDER BY `heatindex` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxHeatEE = $result["heatindex"];
	$maxHeatTimeEE = $result["time"];

# MM max heat index for today
	$query="SELECT * FROM WeatherMM WHERE `date`= DATE(NOW()) ORDER BY `heatindex` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxHeatMM = $result["heatindex"];
	$maxHeatTimeMM = $result["time"];



# EE max thw for today
	$query="SELECT * FROM WeatherEE WHERE `date`= DATE(NOW()) ORDER BY `THWindex` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxthwEE = $result["THWindex"];
	$maxTHWtimeEE = $result["time"];

# MM max thsw for today
	$query="SELECT * FROM WeatherMM WHERE `date`= DATE(NOW()) ORDER BY `THSWindex` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxthswMM = $result["THSWindex"];
	$maxTHSWtimeMM = $result["time"];

# MM max thw for today
	$query="SELECT * FROM WeatherMM WHERE `date`= DATE(NOW()) ORDER BY `THW` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxthwMM = $result["THW"];
	$maxTHWtimeMM = $result["time"];


# EE min thw for today
	$query="SELECT * FROM WeatherEE WHERE `date`= DATE(NOW()) ORDER BY `THWindex` ASC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minthwEE = $result["THWindex"];
	$minTHWtimeEE = $result["time"];

# MM min thsw for today
	$query="SELECT * FROM WeatherMM WHERE `date`= DATE(NOW()) ORDER BY `THSWindex` ASC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minthswMM = $result["THSWindex"];
	$minTHSWtimeMM = $result["time"];

# MM min thw for today
	$query="SELECT * FROM WeatherMM WHERE `date`= DATE(NOW()) ORDER BY `THW` ASC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$minthwMM = $result["THW"];
	$minTHWtimeMM = $result["time"];




# MM max solarrad for today
	$query="SELECT * FROM WeatherMM WHERE `date` = date(NOW()) ORDER BY `solarrad` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxSolarradMM = $result["solarrad"];
	$maxSolarradTimeMM = $result["time"];

# SB max solarrad for today
	$query="SELECT * FROM SB_TMin WHERE `Date`= DATE(NOW()) ORDER BY `SunWM_Avg` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxSolarradSB = $result["SunWM_Avg"];
	$maxSolarradTimeSB = $result["Time"];



# MM max solarenergy for today
	$query="SELECT * FROM WeatherMM WHERE `date` = date(NOW()) ORDER BY `solarenergy` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxSolarEnergyMM = $result["solarenergy"];
	$maxSolarEnergyTimeMM = $result["time"];

# MM max UV index for today
	$query="SELECT * FROM WeatherMM WHERE `date` = date(NOW()) ORDER BY `UV` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxUVindexMM = $result["UV"];
	$maxUVindexTimeMM = $result["time"];

# MM max UV dose for today
	$query="SELECT * FROM WeatherMM WHERE `date` = date(NOW()) ORDER BY `UVdose` DESC LIMIT 1";
	$result = mysql_fetch_assoc(mysql_query($query));
	$maxUVdoseMM = round($result["UVdose"],2);
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

	#echo "<small>Page loaded in ".round($time,2)." seconds\n</small>";
	$loadTime = round($time,2);
	
?>

