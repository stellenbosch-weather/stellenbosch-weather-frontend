<?php 
include_once $_SERVER[ 'DOCUMENT_ROOT' ].'/include/FunctionsGeneral.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ].'/include/Units.php';
include $_SERVER[ 'DOCUMENT_ROOT' ].'/sources/MeasurementDescriptions.php';
include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/DatabaseConnector.php';
include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/FunctionsDay.php';
//default is yesterday
$date = new DateTime();
$date->sub(new DateInterval('P1D'));

//the GET or POST must be in the format year=2013&month=06&day=26
if( isset($_REQUEST["year"]) and isset($_REQUEST["month"]) and isset($_REQUEST["day"]) )
{
  if($tempDate = date_create_from_format('Y-m-d', $_REQUEST["year"]."-".$_REQUEST["month"]."-".$_REQUEST["day"]))
  {
    $date = $tempDate;
  }
}
else if( isset($_REQUEST["date"]) )
{
  if($tempDate = date_create_from_format('Y-m-d', $_REQUEST["date"]))
  {
    $date = $tempDate;
  }
}

$databaseConnector = new DatabaseConnector();
$functionsDay = new FunctionsDay($date, $databaseConnector::getConnection());

$stationList = getListOfStations();

$yesterday = clone $date;
$yesterday->sub(new DateInterval("P1D"));
$tomorrow = clone $date;
$tomorrow->add(new DateInterval("P1D"));



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Measurements for <?php echo date_format($date, "Y-m-d"); ?></title>
<script src="sources/jquery.js" type="text/javascript"></script>
<link href="sources/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<style type="text/css">
<!--
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	color: #000000;
}
body {
	background-color: #A8CBFF;
}

td.cellLink
{
  text-align: center; 
  background: #426ecf;
}
a.cellLink
{
  display: inline-block; 
  height:100%; 
  width:100%;
}
td.cellLink:hover
{
  background: #738fcf;
}

.tableHeadings 
{
  color: #FFFFFF;
  background-color: #426ecf;
  text-align: center;
  font-weight:bold;
  padding:5;
}

.tableContent
{
  background-color: #deefff;
  color: #000000;
}

-->
</style>

<!-- facebox -->
<script src="sources/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript"> 
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
        loading_image : 'loading.gif',
        close_image   : 'closelabel.gif'
      }) 
    })
</script>

<!-- jquery style -->
<link rel="stylesheet" href="sources/jquery-ui/css/smoothness/jquery-ui-1.8.16.custom.css">

<!-- java scripts -->
<script src="sources/jquery.js" type="text/javascript"></script>
<script src="sources/jquery-ui/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<script>
    $(function() {
        var myEndDate=new Date();
        $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd', defaultDate: '-1d' });
    });
</script>

</head>

<body>
<div width="200px" align="center">
  <table>
    <!--Date chooser -->
    <tr><td colspan="6" align="center">
      <span style="float:left;">
        <form method="post">
          Select a date: <input size="10" type="text" id="datepicker" name="date" value="<?php echo date_format($date, "Y-m-d"); ?>">
          <input type="submit" value="Update">
        </form>
      </span>
      <span style="float:left;">
        <form method="post">
      &nbsp;or&nbsp;
          <input type="hidden" name="date" value="<?php echo date_format($yesterday, "Y-m-d"); ?>">
          <input type="submit" value="< Previous day">
        </form>
      </span>
      <span style="float:left;">
        <form method="post">
          <input type="hidden" name="date" value="<?php echo date_format($tomorrow, "Y-m-d"); ?>">
          <input type="submit" value="Next day >">
        </form>
      </span>
    </td></tr>
    
    <tr>
      <td colspan="5" height="15px"></td>
    </tr>
    
    <!--Big heading-->
    <tr><td colspan="6" style="padding-top: 0px; padding-bottom: 10px; font-weight: bold; font-size: 24pt; text-align: center;">
      Measurements for <?php echo date_format($date, "Y-m-d"); ?>
    </td></tr>
    
    <tr>
      <td colspan="5"></td>
    </tr>
    
    <!--table headings-->
    <tr>
      <td width="110"></td>
      <td width="80"></td>
<?php
  foreach ($stationList as $station)
  {
    echo '<th width="220" scope="col" class="tableHeadings">'.getStationName($station).'</th>';
  }
?>
      <th width="55" scope="col" class="tableHeadings">Graphs</th>
    </tr>
    
    <!--small open line-->
    <tr>
      <td height="2px" colspan="5"></td>
    </tr>
    
    <!--measurements and period-->
    <tr>
      <th colspan="2">Dataset for this day</th>
<?php
foreach ($stationList as $station)
{
  $value = $functionsDay::getSampleCount($station);
  if($value == False) {
    $fontColour = "gray";
    $value = "no data available";
    echo '<td class="tableContent" style="color: '.$fontColour.'">';
    echo $value.'</td>';
    continue;
  }
  else {
    $fontColour = "black";
    $sampleCount = $value;
    $value = $value." samples";
  }
  
  echo '<td class="tableContent" style="color: '.$fontColour.'">';
  echo $value."<br />";
  
  $value = $functionsDay::getStartTime($station);
  echo "between ".date_format ($value, "H:i");
  
  $value = $functionsDay::getEndTime($station);
  echo " and ".date_format ($value, "H:i")."<br />";
  
  $percentage = round($sampleCount/(24*60)*100, 1);
  
  //before 2010-03-26 we only had 10 minutely data
  if (date_format($date, "U") < mktime(0,0,0,3,26,2010))
  {
    $percentage = round($sampleCount/(24*6)*100, 1);
  }
  
  echo $percentage."% complete";
  
  echo '</td>';
}
?>
      <td></td>
    </tr>
    
    <!--small open line-->
    <tr>
      <td height="8px" colspan="5"></td>
    </tr>
    
    
    <!--temperature-->
    <tr>
      <th rowspan="3" class="tableHeadings" scope="rowgroup" title="Temperature measured in degrees Celsius.">Temperature</th>
      
      <th class="tableHeadings" scope="row">Max</th>
<?php
foreach ($stationList as $station)
{
  $value = $functionsDay::getMaxTemp($station);
  if($value == False) {
    $fontColour = "gray";
    $value = "unavailable";
  }
  else {
    $fontColour = "black";
    $value = $value[0].temperatureUnit()." @ ".date_format ($value[1], "H:i");
  }
  
  echo '<td class="tableContent" style="color: '.$fontColour.'">';
  echo $value.'</td>';
}
?>
      <td rowspan="3" class="tableContent">graph2b</td>
    </tr>
    
    <tr>
      <th class="tableHeadings" scope="row">Avg</th>
<?php
foreach ($stationList as $station)
{
  $value = $functionsDay::getAvgTemp($station);
  if($value == False) {
    $fontColour = "gray";
    $value = "unavailable";
  }
  else {
    $fontColour = "black";
    $value = $value.temperatureUnit();
  }
  
  echo '<td class="tableContent" style="color: '.$fontColour.'">';
  echo $value.'</td>';
}
?>
    </tr>
    <tr>
      <th class="tableHeadings" scope="row">Min</th>
<?php
foreach ($stationList as $station)
{
  $value = $functionsDay::getMinTemp($station);
  if($value == False) {
    $fontColour = "gray";
    $value = "unavailable";
  }
  else {
    $fontColour = "black";
    $value = $value[0].temperatureUnit()." @ ".date_format ($value[1], "H:i");
  }
  
  echo '<td class="tableContent" style="color: '.$fontColour.'">';
  echo $value.'</td>';
}
?>
    </tr>
    
    
    <!--small open line-->
    <tr>
      <td height="2px" colspan="5"></td>
    </tr>
    
    
    <!--Humidity-->
    <tr>
      <th rowspan="3" class="tableHeadings" scope="rowgroup" title="Temperature measured in degrees Celsius.">Humidity</th>
      
      <th class="tableHeadings" scope="row">Max</th>
<?php
foreach ($stationList as $station)
{
  $value = $functionsDay::getMaxHumid($station);
  if($value == False) {
    $fontColour = "gray";
    $value = "unavailable";
  }
  else {
    $fontColour = "black";
    $value = $value[0].humidityUnit()." @ ".date_format ($value[1], "H:i");
  }
  
  echo '<td class="tableContent" style="color: '.$fontColour.'">';
  echo $value.'</td>';
}
?>
      <td rowspan="3" class="tableContent">graph2b</td>
    </tr>
    
    <tr>
      <th class="tableHeadings" scope="row">Avg</th>
<?php
foreach ($stationList as $station)
{
  $value = $functionsDay::getAvgHumid($station);
  if($value == False) {
    $fontColour = "gray";
    $value = "unavailable";
  }
  else {
    $fontColour = "black";
    $value = $value.humidityUnit();
  }
  
  echo '<td class="tableContent" style="color: '.$fontColour.'">';
  echo $value.'</td>';
}
?>
    </tr>
    <tr>
      <th class="tableHeadings" scope="row">Min</th>
<?php
foreach ($stationList as $station)
{
  $value = $functionsDay::getMinHumid($station);
  if($value == False) {
    $fontColour = "gray";
    $value = "unavailable";
  }
  else {
    $fontColour = "black";
    $value = $value[0].humidityUnit()." @ ".date_format ($value[1], "H:i");
  }
  
  echo '<td class="tableContent" style="color: '.$fontColour.'">';
  echo $value.'</td>';
}
?>
    </tr>
    
    
    <!--small open line-->
    <tr>
      <td height="2px" colspan="5"></td>
    </tr>
    
    
    <!--Barometer-->
    <tr>
      <th rowspan="3" class="tableHeadings" scope="rowgroup" title="Temperature measured in degrees Celsius.">Barometer</th>
      
      <th class="tableHeadings" scope="row">Max</th>
<?php
foreach ($stationList as $station)
{
  $value = $functionsDay::getMaxBaro($station);
  if($value == False) {
    $fontColour = "gray";
    $value = "unavailable";
  }
  else {
    $fontColour = "black";
    $value = $value[0].barometerUnit()." @ ".date_format ($value[1], "H:i");
  }
  
  echo '<td class="tableContent" style="color: '.$fontColour.'">';
  echo $value.'</td>';
}
?>
      <td rowspan="3" class="tableContent">graph2b</td>
    </tr>
    
    <tr>
      <th class="tableHeadings" scope="row">Avg</th>
<?php
foreach ($stationList as $station)
{
  $value = $functionsDay::getAvgBaro($station);
  if($value == False) {
    $fontColour = "gray";
    $value = "unavailable";
  }
  else {
    $fontColour = "black";
    $value = $value.barometerUnit();
  }
  
  echo '<td class="tableContent" style="color: '.$fontColour.'">';
  echo $value.'</td>';
}
?>
    </tr>
    <tr>
      <th class="tableHeadings" scope="row">Min</th>
<?php
foreach ($stationList as $station)
{
  $value = $functionsDay::getMinBaro($station);
  if($value == False) {
    $fontColour = "gray";
    $value = "unavailable";
  }
  else {
    $fontColour = "black";
    $value = $value[0].barometerUnit()." @ ".date_format ($value[1], "H:i");
  }
  
  echo '<td class="tableContent" style="color: '.$fontColour.'">';
  echo $value.'</td>';
}
?>
    </tr>
    
  </table>
<br />
<?
include ("adsense.php");
show_ip(false);
?>
</div>
</body>
</html>
<?php
$databaseConnector::closeConnection();
?>
