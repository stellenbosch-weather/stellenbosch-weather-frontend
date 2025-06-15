<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_STRICT);

header("Cache-Control: max-age=300"); //5minutes

include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/DatabaseConnector.php';
include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/StationDescription.php';

$rrd_path = "/home/weather/public_html/graphs/solar/";
$sensor_type = "solar";
$sensor_unit = "w/m²";
$y_label = "Radiation in Watt/metre²";
$description = "Solar Radiation";

//create_graph("hour.png", "-1 hour", "Hourly $description", "averaged over a minute.");
create_graph("day.png", "-1 day", "Daily $description", "averaged over a minute.");
create_graph("week.png", "-1 week", "Weekly $description", "averaged over a minute.");
create_graph("month.png", "-1 month", "Monthly $description", "averaged over ten minutes.");
create_graph("3month.png", "-3 month", "3 Monthly $description", "averaged over a minute.");
create_graph("year.png", "-1 year", "Annual $description", "averaged over an hour.");
create_graph("7year.png","-7 year", "$description past 7 years", "averaged over a day.");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
    <title>
      Graphs
    </title>
    <meta http-equiv="refresh" content="300">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
  </head>
  <body>
    <center>
<?php
echo "<table>";
echo "<tr><th colspan=\"2\">$description Measurements</th></tr>";
echo "<tr><td>";
//echo "<img src='hour.png' alt='Generated RRD image'>";
echo "<img src='day.png?" . filemtime('day.png') . "' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='week.png?" . filemtime('week.png') . "' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='month.png?" . filemtime('month.png') . "' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='3month.png?" . filemtime('3month.png') . "' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='year.png?" . filemtime('year.png') . "' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='7year.png?" . filemtime('7year.png') . "' alt='Generated RRD image'>";
echo "</td></tr>";
//echo "</table>";


function create_graph($output, $start, $title, $comment) {
 global $rrd_path, $sensor_type, $sensor_unit, $y_label;
 $options = array(
    "--slope-mode",
    "--full-size-mode",
    "-w 600",
    "-h 300",
    "--start", $start,
    "--title=$title",
    //"--vertical-label=$comment",
    "--vertical-label=$y_label",
    //"--lower=0",
    "--watermark=weather.sun.ac.za",
    //"--lazy", //only update the graph if necessary
    "COMMENT: \l",
    );
    
 $options_sb1 = array(
    "DEF:sbtrackedmax=".$rrd_path."sb-solartracked.rrd:solartracked:MAX",
    "DEF:sbtrackedavg=".$rrd_path."sb-solartracked.rrd:solartracked:AVERAGE",
    "AREA:sbtrackedmax#ECD74833:",
    "LINE1.5:sbtrackedavg#C9B215AA:Direct Normal Irradiance - Solys 2     \\t",
    "GPRINT:sbtrackedavg:AVERAGE:Avg\: %4.0lf w/m²\\t",
    "GPRINT:sbtrackedmax:MAX:Max\: %4.0lf w/m²\\l",
    );
  
  if(rrd_last($rrd_path."sb-solartracked.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_sb1);
  }
    
 $options_mm = array(    
    "DEF:mmdirectmax=".$rrd_path."mm-solardirect.rrd:solardirect:MAX",
    "DEF:mmdirectavg=".$rrd_path."mm-solardirect.rrd:solardirect:AVERAGE",
    "AREA:mmdirectmax#DE48EC33:",
    "LINE1.5:mmdirectavg#B415C7AA:Global Horizontal Irradiance - M&M     \\t",
    "GPRINT:mmdirectavg:AVERAGE:Avg\: %4.0lf w/m²\\t",
    "GPRINT:mmdirectmax:MAX:Max\: %4.0lf w/m²\\l",
    );
  
  if(rrd_last($rrd_path."mm-solardirect.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mm);
  }
    
 $options_sb2 = array(    
    "DEF:sbdirectmax=".$rrd_path."sb-solardirect.rrd:solardirect:MAX",
    "DEF:sbdirectavg=".$rrd_path."sb-solardirect.rrd:solardirect:AVERAGE",
    "AREA:sbdirectmax#EA644A33:",
    "LINE1.5:sbdirectavg#CC3118AA:Global Horizontal Irradiance - Solys 2 \\t",
    "GPRINT:sbdirectavg:AVERAGE:Avg\: %4.0lf w/m²\\t",
    "GPRINT:sbdirectmax:MAX:Max\: %4.0lf w/m²\\l",
    );
  
  if(rrd_last($rrd_path."sb-solardirect.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_sb2);
  }
    
 $options_sb3 = array(
    "DEF:sbshadowmax=".$rrd_path."sb-solarshadow.rrd:solarshadow:MAX",
    "DEF:sbshadowavg=".$rrd_path."sb-solarshadow.rrd:solarshadow:AVERAGE",
    "AREA:sbshadowmax#54EC4833:",
    "LINE1.5:sbshadowavg#24BC14AA:Diffuse Horizontal Irradiance - Solys 2\\t",
    "GPRINT:sbshadowavg:AVERAGE:Avg\: %4.0lf w/m²\\t",
    "GPRINT:sbshadowmax:MAX:Max\: %4.0lf w/m²\\l",
    );
  
  if(rrd_last($rrd_path."sb-solarshadow.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_sb3);
  }
    
 $options_sb4 = array(  
    "DEF:sbshadowbandmax=".$rrd_path."sb-solarshadowband.rrd:solarshadowband:MAX",
    "DEF:sbshadowbandavg=".$rrd_path."sb-solarshadowband.rrd:solarshadowband:AVERAGE",
    "AREA:sbshadowbandmax#7648EC33:",
    "LINE1.5:sbshadowbandavg#4D18E4AA:Diffuse Horizontal Irrad. - Shadowband \\t",
    "GPRINT:sbshadowbandavg:AVERAGE:Avg\: %4.0lf w/m²\\t",
    "GPRINT:sbshadowbandmax:MAX:Max\: %4.0lf w/m²\\l",
  );
  
  if(rrd_last($rrd_path."sb-solarshadowband.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_sb4);
  }

  $ret = rrd_graph($output, $options);
  if (! $ret) {
    echo "<b>Graph error: </b>".rrd_error()."\n";
  }
}

echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
echo "<tr><td>";

$databaseConnector = new DatabaseConnector();
$stationDescriptions = new StationDescription($databaseConnector::getConnection());
$stationList = $stationDescriptions::getFullNames();
echo "<table><tr><th colspan=\"2\">Weather stations in use</th></tr>";
echo "<tr><th>Legend</th><th>Name</th></tr>";

foreach ($stationList as $short=>$long)
{
  echo "<tr><th>".$short."</th><td>".$long."</td></tr>";
}

echo '<tr><td colspan="2"><a href="http://weather.sun.ac.za/aboutWeatherStations.php" target="mainFrame">Click for more info</a></td></tr>';

$databaseConnector::closeConnection();

echo "</table>";

echo "</td><td style=\"width:100px; vertical-align: top;\">Areas indicate the maximum measured value during the timespan represented by one pixel's width, while lines indicate the average measurement for the same timespan.<br /><br />
The SOLYS 2 is a sun tracker made by Kipp & Zonen.<br />More info <a href=\"http://www.kippzonen.com/Product/20/SOLYS-2-Sun-Tracker\" target=\"_blank\">here</a>.<br /><br />
The diffuse horizontal irradiance is also measured by a Kipp & Zonen CM121 Shadow Ring.<br />More info <a href=\"http://www.kippzonen.com/Product/42/CM-121B-C-Shadow-Ring\" target=\"_blank\">here</a>.<br /><br />
More information about the solar observatory can be found on the <a href=\"http://weather.sun.ac.za/aboutWeatherStations.php\" target=\"mainFrame\">About - Weather stations</a> page.
</td></tr></table>";

?>
    </center>
    <br>
<?
include ("../../adsense.php");
show_ip(false);
?>
    </div>
  </body>
</html>
