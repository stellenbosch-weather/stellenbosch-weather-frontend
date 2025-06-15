<?php
include_once $_SERVER[ 'DOCUMENT_ROOT' ].'/include/FunctionsGeneral.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ].'/include/Units.php';
include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/DatabaseConnector.php';
include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/FunctionsDay.php';

$databaseConnector = new DatabaseConnector();
$dbConnection = $databaseConnector::getConnection();

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



/*
http://weather.sun.ac.za/upload/weatherStation.php?
ID=D-C&
PASSWORD=REDACTED
tempc=19.1&
dateutc=2017-07-26+13%3A28%3A37&
solarradiation=192.66&
UV=3&
barohp=0.0&
apptempc=18.0&
rainmm_day=5900.4&
heatindex=19.1&
windchillc=19.1&
windspeedkmph=0.00&
windgustkmph=0.00&
humidity=40&
cadhumindex=18.5&
rainmm=15504.90&
softwaretype=pywws&
action=updateraw&
rainmm_hr=5900.4&
dewptc=5.2
*/

$query = "SELECT ShortName,UploadPassword,TableName FROM WeatherStations WHERE 1";
$result = $dbConnection->query($query);

if($result==FALSE)
{
  print "ERROR: mysql error";
  $databaseConnector::closeConnection();
  die;
}

$table_name = "";

while ($row = $result->fetch())
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
$result = $dbConnection->query($existsQuery);

if($result==FALSE)
{
  print "ERROR: Can not determine existance of record";
  $databaseConnector::closeConnection();
  die;
}

$rowCount = $result->fetchColumn();

if($rowCount==0)
{
  $insertQuery = "INSERT INTO `".$table_name."` (`DateTime`,`Date`,`Time`) ".
  "VALUES (\"".date_format($entryDateTimeObject,"Y-m-d G:i:s")."\", ".
  "\"".date_format($entryDateTimeObject,"Y-m-d")."\", ".
  "\"".date_format($entryDateTimeObject,"G:i:s")."\")";
  
  $result = $dbConnection->query($insertQuery);
  if($result==FALSE)
  {
    print "ERROR: Can not insert new DateTime";
    $databaseConnector::closeConnection();
    die;
  }
}

$statement = $dbConnection->prepare("UPDATE `".$table_name."`
SET 
WindDir=:winddir,
WindAvg=:windspeedkmph,
WindGust=:windgustkmph,
WindChill=:windchillc,
Temperature=:tempc,
ApparentTemp=:apptempc,
USAHeatIndex=:heatindex,
DewPoint=:dewpt,
Rain=:rainmm,
RainHr=:rainmm_hr,
RainDay=:rainmm_day,
Barometer=:barohp,
BarometerTrend=:barotrend,
DewPoint=:dewptc,
Humidity=:humidity,
CanadianHumidityIndex=:cadhumindex,
SolarRad=:solarradiation,
UV=:UV
WHERE `DateTime` = :datetime
");

$keys = ['winddir', 'windspeedkmph', 'windgustkmph', 'windchillc', 'tempc', 'apptempc', 'heatindex', 'dewpt', 'rainmm', 'rainmm_hr', 'rainmm_day', 'barohp', 'barotrend', 'dewptc', 'humidity', 'cadhumindex', 'solarradiation', 'UV'];

foreach ($keys as $key)
{
  $statement->bindValue(':'.$key, isset($_REQUEST[$key]) ? $_REQUEST[$key] : null);
}
$statement->bindValue(':datetime', date_format($entryDateTimeObject,"Y-m-d G:i:s"), PDO::PARAM_STR);
$result = $statement->execute();

if($result==TRUE)
{
  print "success";
}
else
{
  print "ERROR: Can't add entry:";
  var_dump($result);
}

$databaseConnector::closeConnection();
?>
