<?php
include_once $_SERVER[ 'DOCUMENT_ROOT' ].'/include/FunctionsGeneral.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ].'/include/Units.php';
include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/DatabaseConnector.php';
include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/FunctionsDay.php';

$databaseConnector = new DatabaseConnector($user="weather", $password="REDACTED");
$connection = $databaseConnector::getConnection();

/*
http://weatherstation.wunderground.com/weatherstation/updateweatherstation.php?ID=KCASANFR5&PASSWORD=REDACTED&dateutc=2000-01-01+10%3A32%3A35&winddir=230&windspeedmph=12&windgustmph=12&tempf=70&rainin=0&baromin=29.1&dewptf=68.2&humidity=90&weather=&clouds=&softwaretype=vws%20versionxx&action=updateraw

ID
PASSWORD
dateutc
winddir
windspeedkmph
windgustkmph
tempc
rainmm_hr
rainmm_day
barohp
dewptc
humidity
weather
clouds
softwaretype
action
*/

$query = "SELECT ShortName,UploadPassword,TableName FROM WeatherStations WHERE 1";
$result = mysql_query($query,$connection);

if($result==FALSE)
{
  print "ERROR: mysql error";
  $databaseConnector::closeConnection();
  die;
}

$table_name = "";

while ($row = mysql_fetch_assoc($result))
{
  if($row["ShortName"]==urldecode($_REQUEST["ID"]) and $row["UploadPassword"]==urldecode($_REQUEST["PASSWORD"]))
  {
    $table_name = $row["TableName"];
  }
}

if($table_name=="")
{
  print "ERROR: Invalid station ID or Password";
  $databaseConnector::closeConnection();
  die;
}

if(!isset($_REQUEST["dateutc"]))
{
  print "ERROR: DateTime not set";
  die;
}

$entryDateTimeObject = date_create_from_format( "Y-m-d G:i:s" , urldecode($_REQUEST["dateutc"]));
if($entryDateTimeObject==FALSE)
{
  print "ERROR: Invalid DateTime";
  $databaseConnector::closeConnection();
  die;
}

$existsQuery = "SELECT * FROM `".$table_name."` WHERE `DateTime` = \"".date_format($entryDateTimeObject,"Y-m-d G:i:s")."\"";
$result = mysql_query($existsQuery,$connection);

if($result==FALSE)
{
  print "ERROR: Can not determine existance of record";
  $databaseConnector::closeConnection();
  die;
}

if(mysql_num_rows($result)==0)
{
  $insertQuery = "INSERT INTO `".$table_name."` (`DateTime`,`Date`,`Time`) ".
  "VALUES (\"".date_format($entryDateTimeObject,"Y-m-d G:i:s")."\", ".
  "\"".date_format($entryDateTimeObject,"Y-m-d")."\", ".
  "\"".date_format($entryDateTimeObject,"G:i:s")."\")";
  
  $result = mysql_query($insertQuery,$connection);
  if($result==FALSE)
  {
    print "ERROR: Can not insert new DateTime";
    $databaseConnector::closeConnection();
    die;
  }
}

$updateQuery = "UPDATE `".$table_name."` SET ";

/*
winddir
windspeedkmph
windgustkmph
tempc
rainmm
barohp
dewptc
humidity
solarradiation
UV
*/
$valueCount = 0;
if( isset($_REQUEST["winddir"]) and is_numeric($_REQUEST["winddir"]) )
{
  $valueCount++;
  $value = mysql_real_escape_string($_REQUEST["winddir"]);
  $updateQuery = $updateQuery."`WindDir`=\"".$value."\", ";
}
if( isset($_REQUEST["windspeedkmph"]) and is_numeric($_REQUEST["windspeedkmph"]) )
{
  $valueCount++;
  $value = mysql_real_escape_string($_REQUEST["windspeedkmph"]);
  $updateQuery = $updateQuery."`WindAvg`=\"".$value."\", ";
}
if( isset($_REQUEST["windgustkmph"]) and is_numeric($_REQUEST["windgustkmph"]) )
{
  $valueCount++;
  $value = mysql_real_escape_string($_REQUEST["windgustkmph"]);
  $updateQuery = $updateQuery."`WindGust`=\"".$value."\", ";
}
if( isset($_REQUEST["windchillc"]) and is_numeric($_REQUEST["windchillc"]) )
{
  $valueCount++;
  $value = mysql_real_escape_string($_REQUEST["windchillc"]);
  $updateQuery = $updateQuery."`WindChill`=\"".$value."\", ";
}
if( isset($_REQUEST["tempc"]) and is_numeric($_REQUEST["tempc"]) )
{
  $valueCount++;
  $value = mysql_real_escape_string($_REQUEST["tempc"]);
  $updateQuery = $updateQuery."`Temperature`=\"".$value."\", ";
}
if( isset($_REQUEST["apptempc"]) and is_numeric($_REQUEST["apptempc"]) )
{
  $valueCount++;
  $value = mysql_real_escape_string($_REQUEST["apptempc"]);
  $updateQuery = $updateQuery."`ApparentTemp`=\"".$value."\", ";
}
if( isset($_REQUEST["heatindex"]) and is_numeric($_REQUEST["heatindex"]) )
{
  $valueCount++;
  $value = mysql_real_escape_string($_REQUEST["heatindex"]);
  $updateQuery = $updateQuery."`USAHeatIndex`=\"".$value."\", ";
}
if( isset($_REQUEST["dewpt"]) and is_numeric($_REQUEST["dewpt"]) )
{
  $valueCount++;
  $value = mysql_real_escape_string($_REQUEST["dewpt"]);
  $updateQuery = $updateQuery."`DewPoint`=\"".$value."\", ";
}
if( isset($_REQUEST["rainmm"]) and is_numeric($_REQUEST["rainmm"]) )
{
  $valueCount++;
  $value = mysql_real_escape_string($_REQUEST["rainmm"]);
  $updateQuery = $updateQuery."`Rain`=\"".$value."\", ";
}
if( isset($_REQUEST["rainmm_hr"]) and is_numeric($_REQUEST["rainmm_hr"]) )
{
  $valueCount++;
  $value = mysql_real_escape_string($_REQUEST["rainmm_hr"]);
  $updateQuery = $updateQuery."`RainHr`=\"".$value."\", ";
}
if( isset($_REQUEST["rainmm_day"]) and is_numeric($_REQUEST["rainmm_day"]) )
{
  $valueCount++;
  $value = mysql_real_escape_string($_REQUEST["rainmm_day"]);
  $updateQuery = $updateQuery."`RainDay`=\"".$value."\", ";
}
if( isset($_REQUEST["barohp"]) and is_numeric($_REQUEST["barohp"]) )
{
  $valueCount++;
  $value = mysql_real_escape_string($_REQUEST["barohp"]);
  $updateQuery = $updateQuery."`Barometer`=\"".$value."\", ";
}
if( isset($_REQUEST["barotrend"]) )
{
  $valueCount++;
  $value = mysql_real_escape_string($_REQUEST["barotrend"]);
  $updateQuery = $updateQuery."`BarometerTrend`=\"".$value."\", ";
}
if( isset($_REQUEST["dewptc"]) and is_numeric($_REQUEST["dewptc"]) )
{
  $valueCount++;
  $value = mysql_real_escape_string($_REQUEST["dewptc"]);
  $updateQuery = $updateQuery."`DewPoint`=\"".$value."\", ";
}
if( isset($_REQUEST["humidity"]) and is_numeric($_REQUEST["humidity"]) )
{
  $valueCount++;
  $value = mysql_real_escape_string($_REQUEST["humidity"]);
  $updateQuery = $updateQuery."`Humidity`=\"".$value."\", ";
}
if( isset($_REQUEST["cadhumindex"]) and is_numeric($_REQUEST["cadhumindex"]) )
{
  $valueCount++;
  $value = mysql_real_escape_string($_REQUEST["cadhumindex"]);
  $updateQuery = $updateQuery."`CanadianHumidityIndex`=\"".$value."\", ";
}
if( isset($_REQUEST["solarradiation"]) and is_numeric($_REQUEST["solarradiation"]) )
{
  $valueCount++;
  $value = mysql_real_escape_string($_REQUEST["solarradiation"]);
  $updateQuery = $updateQuery."`SolarRad`=\"".$value."\", ";
}
if( isset($_REQUEST["UV"]) and is_numeric($_REQUEST["UV"]) )
{
  $valueCount++;
  $value = mysql_real_escape_string($_REQUEST["UV"]);
  $updateQuery = $updateQuery."`UV`=\"".$value."\", ";
}

if($valueCount==0)
{
  print "ERROR: No data to insert";
  $databaseConnector::closeConnection();
  die;
}

$updateQuery = trim($updateQuery, ", ");

$updateQuery = $updateQuery." WHERE `DateTime` = \"".date_format($entryDateTimeObject,"Y-m-d G:i:s")."\"";
$result = mysql_query($updateQuery,$connection);

if($result==TRUE)
{
  print "success";
}
else
{
  print "ERROR: Can't add entry: ".$updateQuery;
}

$databaseConnector::closeConnection();
?>
