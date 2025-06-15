<?php 
include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/DatabaseConnector.php';
include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/StationDescription.php';
include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/StationFunctions.php';

$databaseConnector = new DatabaseConnector();
mysql_set_charset("utf8mb4");
$stationDescriptions = new StationDescription($databaseConnector::getConnection());
$stationList = $stationDescriptions::getFullDescription();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Refresh" content="60; url=http://weather.sun.ac.za/aboutWeatherStations.php" /> 
<title>About - Weather Stations</title>
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


</head>

<body>
<div width="200px" align="center">
  <table>
    
    <!--Big heading-->
    <tr><td colspan="10" style="padding-top: 0px; padding-bottom: 10px; font-weight: bold; font-size: 24pt; text-align: center;">
      Weather stations in use by this website
    </td></tr>
    
    <tr>
      <td colspan="10"></td>
    </tr>
    
    <!--table headings-->
    <tr>
      <th class="tableHeadings">Short Name</th>
      <th class="tableHeadings">Full Name</th>
      <th class="tableHeadings">Station type</th>
      <th class="tableHeadings">Accuracy</th>
      <th class="tableHeadings">Comment</th>
      <th class="tableHeadings">Location</th>
      <th class="tableHeadings" colspan="2">Data range</th>
      <th class="tableHeadings">Current status</th>
      <th class="tableHeadings">Long term status</th>
    </tr>
    
<?php
  foreach ($stationList as $station)
  {
    echo '<tr>';
    echo '<td class="tableContent" style="font-weight: bold;">'.$station["ShortName"].'</td>';
    echo '<td class="tableContent">'.$station["FullName"].'</td>';
    echo '<td class="tableContent">'.$station["StationType"].'</td>';
    
    if ($station["Accuracy"] == "Bad") $fontColour="red";
    elseif ($station["Accuracy"] == "Very Good") $fontColour="green";
    else $fontColour="black";
    
    echo '<td class="tableContent" style="color: '.$fontColour.'; font-weight: bold;">'.$station["Accuracy"].'</td>';
    echo '<td class="tableContent">'.$station["Comment"].'</td>';
    echo '<td class="tableContent">'.$station["Location"].'</td>';
    
    $tableName=$stationDescriptions::getTableName($station["ShortName"]);
    $dateTimeField=$stationDescriptions::getDateTimeField($station["ShortName"]);
    $usesUTC=$stationDescriptions::usesUTC($station["ShortName"]);
    $firstTimestamp = getFirstEntryDateTime($databaseConnection, $tableName, $dateTimeField, $usesUTC);
    $lastTimestamp = getLastEntryDateTime($databaseConnection, $tableName, $dateTimeField, $usesUTC);
    
    echo '<td class="tableContent">'.(($firstTimestamp==Null)?"Unavailable":date("Y-m-d H:i:s", $firstTimestamp)).'</td>';
    echo '<td class="tableContent">'.(($lastTimestamp==Null)?"Unavailable":date("Y-m-d H:i:s", $lastTimestamp)).'</td>';
    
    if($lastTimestamp<time()-600) //older than 10 minutes
      echo '<td class="tableContent" style="color: orange; font-weight: bold;">Offline</td>';
    else
      echo '<td class="tableContent" style="color: green; font-weight: bold;">Active</td>';
    
    if($station["Status"]==1) //as defined in database
      echo '<td class="tableContent" style="color: green; font-weight: bold;">Active</td>';
    else
      echo '<td class="tableContent" style="color: red; font-weight: bold;">Offline</td>';
    
    echo '</tr>';
  }
?>
    
    <!--small open line-->
    <tr>
      <td height="2px" colspan="10"></td>
    </tr>

    <tr>
      <td colspan="10">Links with more info:</td>
    </tr>

    <tr>
      <td colspan="10"><a href="http://weather.sun.ac.za/api/Measurement%20description%20-%20Sonbesie%20CR1000.txt">Sonbesie - Sensor and Measurement description</td>
    </tr>
    <tr>
      <td colspan="10"><a href="http://weather.sun.ac.za/api/Measurement%20description%20-%20Davis%20Vantage%20Pro.txt">M&M and E&E - Measurement description</td>
    </tr>
    <tr>
      <td colspan="10"><a href="http://weather.sun.ac.za/api/Sonbesie%20metadata.pdf">Stellenbsoch University - weather station setup</td>
    </tr>
    <tr>
      <td colspan="10"><a href="http://maps.google.com/maps/ms?ie=UTF8&t=h&hl=en&msa=0&msid=105004959243337608908.00046bb532f92631515af&ll=-33.928415,18.865607&spn=0.002884,0.004796&z=18">Location of weather stations and webcam on Google Maps</td>
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
