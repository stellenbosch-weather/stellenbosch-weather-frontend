<?php
include_once ("/home/weather/public_html/sources/maan.php");
# Laat moon.php sy werk doen. Dit moet heel bo aan die script gedoen word.
list($MoonPhase, $MoonAge, $MoonDist, $MoonAng, $SunDist, $SunAng, $mpfrac) = Moon::phase(date("Y"), date("m"), date("d"), date("G"), date("i"), date("s"));
ini_set(precision, "14"); # Maak weer floats reg sodat round() weer werk.

#include_once ("/home/weather/public_html/forecasting/bt-sager-wc.php");

## Definieer Funksies
# Timer
function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


#Bereken gemiddelde van drie nie-nul waardes
function get_average($getal1, $getal2, $getal3)
{
  $totaal=0;
  $aantal=0;
  
  
  if(isset($getal1) and !($getal1 === "") and !($getal1 == null))
  {
    $totaal=$totaal+$getal1;
    $aantal++;
  }
  if(isset($getal2) and !($getal2 === "") and !($getal2 == null))
  {
    $totaal=$totaal+$getal2;
    $aantal++;
  }
  if(isset($getal3) and !($getal3 === "") and !($getal3 == null))
  {
    $totaal=$totaal+$getal3;
    $aantal++;
  }
  
  if($aantal>0)
  {
    return $totaal/$aantal;
  }
  else
  {
    return "N/A";
  }
}

function baroTrendText($trend)
{
    /*
    Convert pressure trend to a string, as used by the UK met office.
    Stolen from pywws (GPLv2).
    */
    if ($trend > 6.0)
        return 'rising very rapidly';
    elseif ($trend > 3.5)
        return 'rising quickly';
    elseif ($trend > 1.5)
        return 'rising';
    elseif ($trend >= 0.1)
        return 'rising slowly';
    elseif ($trend < -6.0)
        return 'falling very rapidly';
    elseif ($trend < -3.5)
        return 'falling quickly';
    elseif ($trend < -1.5)
        return 'falling';
    elseif ($trend <= -0.1)
        return 'falling slowly';
    return 'steady';
}

# Start timer
$time_start = microtime_float();

## Koppel aan databasis
include("createDatabaseConnection.php");

$rainUnit = "mm";
$rainRateUnit = "mm/h";
$temperatureUnit = "&deg;C";
$windUnit = "km/h";
$humidUnit = "&#37;";
$baroUnit = "hPa";

$workingWeatherStationCount = 0;
$errorString = "";

# Latest entry from EE weather station
$query = "SELECT MAX(`time`) FROM `WeatherEE` WHERE `date` = DATE(NOW()) AND TIMESTAMPDIFF(MINUTE,ADDTIME(date,time),NOW()) < 10";
$result = mysql_query($query);
if(mysql_num_rows($result)==0 or mysql_result($result,0)==null)
{
  //echo "<small>E&E offline, </small>";
  #$errorString = $errorString."E&E offline, ";
  $timeEE=0;
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
$query = "SELECT MAX(`time`) FROM `WeatherMM` WHERE `date` = DATE(NOW()) AND TIMESTAMPDIFF(MINUTE,ADDTIME(date,time),NOW()) < 10";
$result = mysql_query($query);
if(mysql_num_rows($result)==0 or mysql_result($result,0)==null)
{
  //echo "<small>M&M offline, </small>";
  #$errorString = $errorString."M&M offline, ";
  $timeMM=0;
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
$query = "SELECT MAX(`Time`) FROM `SB_TMin` WHERE `Date` = DATE(NOW()) AND TIMESTAMPDIFF(MINUTE,`TimeStamp`,NOW()) < 10";
$result = mysql_query($query);
if(mysql_num_rows($result)==0 or mysql_result($result,0)==null)
{
  //echo "<small>Sonbesie offline, </small>";
  $errorString = $errorString."Sonbesie offline, ";
  $timeSB=0;
  $latestSB="";
}
else
{
  $workingWeatherStationCount++;
  
  $timeSB = mysql_result($result,0);

  $query = "SELECT * FROM `SB_TMin` WHERE `Date` = DATE(NOW()) AND `Time` = '$timeSB'";
  $result = mysql_query($query);
  $latestSB = (mysql_fetch_assoc($result));
  
  $query = <<<BLOCK
SELECT 
  ( 
    SELECT `BP_mB_Avg` FROM `SB_TMin` WHERE `TimeStamp`=(SELECT MAX(`TimeStamp`) FROM `SB_TMin`) 
  )
 - 
  (
    SELECT `BP_mB_Avg` FROM `SB_TMin` 
    WHERE `TimeStamp` > DATE_SUB((SELECT MAX(`TimeStamp`) FROM `SB_TMin`), INTERVAL 3 HOUR)
    ORDER BY `TimeStamp` ASC LIMIT 1
  )
AS "TREND3H"
BLOCK;
  
  $result = mysql_query($query);
  $baroTrend = mysql_fetch_array($result);
  $baroTrend = $baroTrend[0];
  $baroTrend = baroTrendText($baroTrend);
}

# Kies die weerstasie waarvan die data die nuutste is.
$weerstasie = "";
if ($latestMM["date"] == $latestEE["date"]){ #Selfde datum
	if ($latestEE["time"] > $latestMM["time"]){ #EE nuwer as MM
		#print "EE nuwer";
		$weerstasie = "E&amp;E";
	}
	elseif ($latestMM["time"] > $latestEE["time"]){ #MM nuwer as EE
		#print "MM nuwer";
		$weerstasie = "M&amp;M";
	}
	else {
		#print "Ewe oud";
		$weerstasie = "M&amp;M"; #Presies ewe oud. Kies dan MM.
	}
}
elseif ($latestMM["date"] >= $latestEE["date"]){ #anders nuutste volgens datum
	$weerstasie = "M&amp;M";
}
else {
	$weerstasie = "E&amp;E";
}

if ($weerstasie == "E&amp;E") $latest = $latestEE;
else $latest = $latestMM;

# Indien E&E vir my NULL's gee, gebruik M&M. (waarskynlik agv wireless van E&E)
if ($latest["tempout"] == Null) {
	#print $latest["tempout"];
	if($weerstasie == "E&amp;E"){
		$weerstasie = "M&amp;M";
		$latest = $latestMM;
	}
	else{
		$weerstasie = "E&amp;E";
		$latest = $latestEE;
	}
}


# Windspeed and direction
# Sonbesie > E&E > M&M

$wind = round($latestSB["WS_ms_S_WVT"]*3.6,2);
$windDir = $latestSB["WindDir_D1_WVT"];

if(is_null($wind) or !isset($wind))
{
  $wind = $latestEE["windspeed"];
  $windDir = $latestEE["direction"];
}
else if(is_null($wind) or !isset($wind))
{
  $wind = $latestMM["windspeed"];
  $windDir = $latestMM["direction"];
}

if(is_numeric($windDir))
{
  $dir = array("N","NNE","NE","ENE","E","ESE","SE","SSE","S","SSW","SW","WSW","W","WNW","NW","NNW");
  $windDir = $dir[round($windDir/22.5,0)];
}

if ($wind == "0")
{
  $windString = "calm";
}
else
{
  $windString = $wind." ".$windUnit.", ".$windDir;
  if($wind>30) $windString = $windString.", stormy";
  elseif($wind>20) $windString = $windString.", very windy";
  elseif($wind>10) $windString = $windString.", windy";
}

################################################################################
# Rain
################################################################################
$rain=0;
$rate=0;
$online=false;
/*if($timeEE==0)
{
    $rainString = "<span style=\"color: gray\">unavailable</span>";
}
else
{
  # net E&E het werkende reen, anders kies nuutste een eerder
  $query="SELECT SUM(`rain`) AS totalRain FROM WeatherEE WHERE `date` = DATE(NOW())";
  $result = mysql_query($query);
  $result_asc=mysql_fetch_assoc($result);
  
  $query="SELECT max(rainrate) AS rate FROM WeatherEE WHERE `date` = DATE(NOW()) AND TIMESTAMPDIFF(MINUTE,ADDTIME(date,time),NOW()) < 10";
  $result2 = mysql_query($query);
  $result2=mysql_fetch_assoc($result2);
  
  if(mysql_num_rows($result)==0 or mysql_result($result,0)==null)
  {
    $rainString = "<span style=\"color: gray\">unavailable</span>";
  }
  else
  {
    
    //var_dump($result);
    $rain = $result_asc["totalRain"];
    //var_dump($result_asc);
    //echo $rain;
    $rainRate = round($result2["rate"],1);
    //echo $rainRate;
    if ($rain == 0 or $rain == null) $rainString = "none";
    else $rainString = round($rain,1)." ".$rainUnit;
    
    if ($rainRate != 0 and $rainRate != null)
    {
      $rainString = $rainString." @ ".$rainRate." ".$rainRateUnit;
    }
  }
}*/

##
# Rainmeter
##

$query = "SELECT * FROM `Weather_rainmeter` WHERE Date(`Datetime`) = DATE(NOW()) AND TIMESTAMPDIFF(MINUTE,`Datetime`,NOW()) < 10";
$result = mysql_query($query);

if(mysql_num_rows($result)==0 or mysql_result($result,0)==null)
{
  //$rainString = "<span style=\"color: gray\">unavailable</span>";
  //$errorString = $errorString."rain meter offline, ";
  //rain meter is permanently offline
}
else
{
  $online = true;
  $workingWeatherStationCount++;

  $query = "SELECT SUM(`Rain_mm`) AS 'TotalRain', MAX(`Rate_mm_h`) AS 'MaxRate' FROM `Weather_rainmeter` WHERE Date(`Datetime`) = DATE(NOW())";
  $result = mysql_query($query);
  $rainAssoc = mysql_fetch_assoc($result);

  //if($rainAssoc["TotalRain"]!=0)
  //{
  if($rain<$rainAssoc["TotalRain"])
  {
    $rain = round($rainAssoc["TotalRain"],1);
  }
  if($rate<$rainAssoc["MaxRate"])
  {
    $rate = round($rainAssoc["MaxRate"],2);
  }
    //$rainString = round($rainAssoc["TotalRain"],1)."mm @ ".round($rainAssoc["MaxRate"],2)." mm/h<small><small> (max today)</small></small>";
  //}
  //else
  //{
    //$rainString = "none";
  //}
}

##
# DeCingel
##
$query = "SELECT SUM(  `Rain` ) AS Rain, MAX(  `Rain` ) AS Rate
FROM  `Weather_decingel` 
WHERE  `Date` = DATE( NOW( ) )";
$result = mysql_query($query);
$rainAssoc = mysql_fetch_assoc($result);

if(mysql_num_rows($result)==0 or mysql_result($result,0)==null)
{
}
else
{
  $online = true;
}

if($rain < ($rainAssoc["Rain"]))
{
  $rain = ($rainAssoc["Rain"]);
}
if($rate < ($rainAssoc["Rate"]*6))
{
  $rate = ($rainAssoc["Rate"]*6);
}


##
# Result
##
if($rain!=0 and $online==true)
{
  $rainString = round($rain,1)."mm";
  if($rate != 0)
  {
    $rainString = $rainString." @ ".round($rate,2)." mm/h<small><small> (max today)</small></small>";
  }
}
elseif($online)
{
  $rainString = "none";
}
else
{
  $rainString = "<span style=\"color: gray\">unavailable</span>";
}

################################################################################
# other
################################################################################
$humidity = round(get_average($latestMM["outhum"],$latestEE["outhum"],$latestSB["RH"]),1);
$barometer = round(get_average($latestMM["bar"],$latestEE["bar"],$latestSB["BP_mB_Avg"]),0);


$temperature = get_average($latestMM["tempout"],$latestEE["tempout"],$latestSB["AirTC_Avg"]);

# if the temperature is still blank, no failover, hardcode WS
if(!is_numeric($temperature))
{
  $query = "SELECT * FROM `Weather_rainmeter` WHERE Date(`Datetime`) = DATE(NOW()) AND TIMESTAMPDIFF(MINUTE,`Datetime`,NOW()) < 10";
  $result = mysql_query($query);
  $latestRM = mysql_fetch_assoc($result);
  $temperature = $latestRM['Temperature'];
}

# if the temperature is still blank, no failover, hardcode WS
if(!is_numeric($temperature))
{
  $query = "SELECT * FROM `Weather_decingel` WHERE `Date` = DATE(NOW()) AND TIMESTAMPDIFF(MINUTE,`DateTime`,NOW()) < 130"; //gmt offset
  $result = mysql_query($query);
  $latestDC = mysql_fetch_assoc($result);
  $temperature = $latestDC['Temperature'];
}

if(is_numeric($temperature))
{
  $temperature = round($temperature,1);
}


# EE max temp for today
$query="SELECT MAX(`tempout`) FROM `WeatherEE` WHERE `date` = DATE(NOW())";
$result=mysql_query($query);
if(mysql_num_rows($result)==0 or mysql_result($result,0)==null)
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
}

# MM max temp for today
$query="SELECT MAX(`tempout`) FROM `WeatherMM` WHERE `date` = DATE(NOW())";
$result=mysql_query($query);
if(mysql_num_rows($result)==0 or mysql_result($result,0)==null)
{
  $maxtempMM="";
  $maxtemptimeMM="";
}
else
{
  $maxtempMM = mysql_result($result,0);
  $query="SELECT `time` FROM `WeatherMM` WHERE `date`= DATE(NOW()) 
          AND ABS(`tempout`-$maxtempMM) < 0.002
          LIMIT 1"; // floating points are inacurate
  $result = mysql_fetch_assoc(mysql_query($query));
  $maxtemptimeMM = $result["time"];
}
	
# SB max temp for today
$query="SELECT MAX(`AirTC_Avg`) FROM `SB_TMin` WHERE `Date` = DATE(NOW())";
$result=mysql_query($query);
if(mysql_num_rows($result)==0 or mysql_result($result,0)==null)
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
}

if($maxtempSB>$maxtempEE and $maxtempSB>$maxtempMM)
{
  $maxTemp = round($maxtempSB,2);
  $maxTempTime = substr($maxtemptimeSB,0,5);
}
elseif($maxtempEE>$maxtempSB and $maxtempEE>$maxtempMM)
{
  $maxTemp = round($maxtempEE,1);
  $maxTempTime = substr($maxtemptimeEE,0,5);
}
elseif($maxtempMM>$maxtempEE and $maxtempMM>$maxtempSB)
{
  $maxTemp = round($maxtempMM,1);
  $maxTempTime = substr($maxtemptimeMM,0,5);
}
else
{
  $maxTemp = round($maxtempSB,2);
  $maxTempTime = substr($maxtemptimeSB,0,5);
}


# EE min temp for today
$query="SELECT MIN(`tempout`) FROM `WeatherEE` WHERE `date` = DATE(NOW())";
$result=mysql_query($query);
if(mysql_num_rows($result)==0 or mysql_result($result,0)==null)
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
}

# MM min temp for today
$query="SELECT MIN(`tempout`) FROM `WeatherMM` WHERE `date` = DATE(NOW())";
$result=mysql_query($query);
if(mysql_num_rows($result)==0 or mysql_result($result,0)==null)
{
  $mintempMM="";
  $mintemptimeMM="";
}
else
{
  $mintempMM = mysql_result($result,0);
  $query="SELECT `time` FROM `WeatherMM` WHERE `date`= DATE(NOW()) 
          AND ABS(`tempout`-$mintempMM) < 0.002
          LIMIT 1"; // floating points are inacurate
  $result = mysql_fetch_assoc(mysql_query($query));
  $mintemptimeMM = $result["time"];
}
	
# SB min temp for today
$query="SELECT MIN(`AirTC_Avg`) FROM `SB_TMin` WHERE `Date` = DATE(NOW())";
$result=mysql_query($query);
if(mysql_num_rows($result)==0 or mysql_result($result,0)==null)
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
}

if($mintempSB != "" and $mintempSB<$mintempEE and $mintempSB<$mintempMM)
{
  $minTemp = round($mintempSB,2);
  $minTempTime = substr($mintemptimeSB,0,5);
}
elseif($mintempEE != "" and $mintempEE<$mintempSB and $mintempEE<$mintempMM)
{
  $minTemp = round($mintempEE,1);
  $minTempTime = substr($mintemptimeEE,0,5);
}
elseif($mintempMM != "" and $mintempMM<$mintempEE and $mintempMM<$mintempSB)
{
  $minTemp = round($mintempMM,1);
  $minTempTime = substr($mintemptimeMM,0,5);
}
else
{
  $minTemp = round($mintempSB,2);
  $minTempTime = substr($mintemptimeSB,0,5);
}

# Maak databasis toe
mysql_close();

# Lees data in wat ek nie uit die databasis kan kry nie
if($timeEE!=0 or $timeMM != 0)
{
  if ($timeEE!=0) $html=file_get_contents('/home/weather/public_html/weatherlinkEE/datastrings.htm');
  else $html=file_get_contents('/home/weather/public_html/weatherlink/templates/datastrings.htm');
  $values=explode("\n", $html);
  $baroTrend = $values[1];
  $quickforecast = $values[0];

  if(strpos($quickforecast, "FORECAST REQUIRES 3 HOURS OF RECENT DATA") !== false)
  {
    $quickforecast = "<span style=\"color: gray\">---</span>";
    if(filemtime ( "/home/weather/public_html/forecasting/currentforecast.txt" ) > ( time() - (60*10) ) ) #older that 10 minutes
    {
      $quickforecast = "".file_get_contents('/home/weather/public_html/forecasting/currentforecast.txt')."";
    }
  }
}
else
{
  //use one of the two forecast algorithms
  $quickforecast = "<span style=\"color: gray\">---</span>";
  if(filemtime ( "/home/weather/public_html/forecasting/currentforecast.txt" ) > ( time() - (60*10) ) ) #older that 10 minutes
  {
    $quickforecast = "".file_get_contents('/home/weather/public_html/forecasting/currentforecast.txt')."";
  }
}

# Maak die datum en tyd mooi.
$today = date('Y')."-".date('m')."-".date('d');
$yesterday = date('Y')."-".date('m')."-".(date('d') - 1);
$updateDate = $latestSB["Date"];
$updateTime = substr($latestSB["Time"], 0, 5);
if ($updateDate == $today){ $updateDate = "Today";}
elseif ($updateDate == $yesterday){ $updateDate = "Yesterday";}

;

/* This is done in the html of the hoempage now
$windString = 
'<a href="http://weather.sun.ac.za/graphs/wind" target="mainFrame" '.
  'title="Click for graphs.">'.$windString."</a>";
*/

//$rainString
$humidString = $humidity.$humidUnit;
if(isset($baroTrend)) 
{
  $baroString = $barometer.$baroUnit.", ".$baroTrend;
}
else 
{
  $baroString = $barometer.$baroUnit;
}
if($temperature!="N/A")
{
  $temperString = $temperature.$temperatureUnit;
}
else
{
  $temperString=$temperature;
}
$minTemperString = $minTemp.$temperatureUnit." at ".$minTempTime;
$maxTemperString = $maxTemp.$temperatureUnit." at ".$maxTempTime;

include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/DatabaseConnector.php';
include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/StationDescription.php';
include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/StationFunctions.php';
$databaseConnector = new DatabaseConnector();
$stationDescriptions = new StationDescription($databaseConnector::getConnection());
$stationList = $stationDescriptions::getFullDescription();
$longActive = $stationDescriptions::getLongActiveStationsCount(); 
$nowActive = getNowActiveStationCount($databaseConnection, $stationList, $stationDescriptions);

$updateTimeString = $updateDate." at ".$updateTime.' from '.
  '<a href="http://weather.sun.ac.za/aboutWeatherStations.php" target="mainFrame" '.
  'title="Click for more information about the weather stations.">'.
  $nowActive.' of '.$longActive.' weather stations</a>.';

#$updateTimeString = $updateDate." at ".$updateTime.' from '.
#  '<a href="http://weather.sun.ac.za/aboutWeatherStations.php" target="mainFrame" '.
#  'title="Click for more information about the weather stations.">'.
#  $workingWeatherStationCount.' weather stations</a>.';

if ($errorString != "") $errorString = "<small>".$errorString."(".($longActive-$nowActive)." of ".$longActive." weather stations offline)</small>";
#include forecasts after error string hadling above
include("HomeAPIforecasts.php");

include("HomeAPIsunmoon.php");

include("HomeAPIzodiac.php");


$time_end = microtime_float();
$time = $time_end - $time_start;
$time = number_format($time,4)

?>
