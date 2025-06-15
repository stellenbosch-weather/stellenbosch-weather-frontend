<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_STRICT);

header("Cache-Control: max-age=300"); //5minutes

include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/DatabaseConnector.php';
include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/StationDescription.php';

$rrd_path = "/home/weather/public_html/graphs/temperature/";
$sensor_type = "temperature";
$sensor_unit = "°C";
$y_label = "Temperature in °C";
$description = "Temperature";

//create_graph("hour.png", "-1h", "Hourly $description", "averaged over a minute.");
create_graph("day.png", "-1 day", "Daily $description", "averaged over a minute.");
create_graph("week.png", "-1 week", "Weekly $description", "averaged over a minute.");
create_graph("month.png", "-1 month", "Monthly $description", "averaged over ten minutes.");
create_graph("3month.png", "-3 month", "3 Monthly $description", "averaged over a minute.");
create_graph("year.png", "-1 year", "Annual $description", "averaged over an hour.");
create_graph("5year.png","-5 year", "$description past 5 years", "averaged over a day.");
create_graph("10year.png","-10 year", "$description past 10 years", "averaged over a day.");
create_graph("20year.png","-20 year", "$description past 20 years", "averaged over a day.");
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
echo "<img src='5year.png?" . filemtime('5year.png') . "' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='10year.png?" . filemtime('10year.png') . "' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='20year.png?" . filemtime('20year.png') . "' alt='Generated RRD image'>";
echo "</td></tr>";
//echo "</table>";

/*
        my @colorsin = ( 
                '30FC30', 
                '3096FC', 
                'FFC930', 
                'FF3030', 
                '30CC30', 
                '3030CC', 
                'FFFF00', 
                'CC3030' 
        ); 
        my @colorsout = ( 
                '00CC00', 
                '0066CC', 
                'FF9900', 
                'CC0000', 
                '009900', 
                '000099', 
                'CCCC00', 
                '990000' 
        );
        
        	LIGHT	DARK
RED	#EA644A	#CC3118
ORANGE	#EC9D48	#CC7016
YELLOW	#ECD748	#C9B215
GREEN	#54EC48	#24BC14
BLUE	#48C4EC	#1598C3
PINK	#DE48EC	#B415C7
PURPLE	#7648EC	#4D18E4

*/        

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
    );
    
 
  $options_eb = array(
    "DEF:ebmax=".$rrd_path."eb-".$sensor_type.".rrd:".$sensor_type."-max:MAX",
    "DEF:ebmin=".$rrd_path."eb-".$sensor_type.".rrd:".$sensor_type."-min:MIN",
    "DEF:eb=".$rrd_path."eb-".$sensor_type.".rrd:".$sensor_type."-avg:AVERAGE",
    "VDEF:ebavg=eb,AVERAGE",
    
    "HRULE:ebavg#000000:Dashed line\: E-B Avg Temperature for graph period\\n:dashes",
    
    "LINE1:eb#000000AA:E-B",
    "GPRINT:ebmax:MAX:Max\: %6.2lf$sensor_unit",
    "GPRINT:ebmin:MIN:Min\: %6.2lf$sensor_unit",
    "GPRINT:eb:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    
    );
    
  if(rrd_last($rrd_path."eb-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_eb);
  }
 
 $options_mm = array(
    "DEF:mm=".$rrd_path."mm-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "DEF:mmmin=".$rrd_path."mm-".$sensor_type.".rrd:".$sensor_type.":MIN",
    "DEF:mmmax=".$rrd_path."mm-".$sensor_type.".rrd:".$sensor_type.":MAX",
    "LINE1.5:mm#30FC30:M&M",
    "VDEF:mmavg=mm,AVERAGE",
    "GPRINT:mmmax:MAX:Max\: %6.2lf$sensor_unit",
    "GPRINT:mmmin:MIN:Min\: %6.2lf$sensor_unit",
    "GPRINT:mm:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
  
  if(rrd_last($rrd_path."mm-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mm);
  }
    
  $options_mr = array(
    "DEF:mr=".$rrd_path."mr-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "DEF:mrmin=".$rrd_path."mr-".$sensor_type.".rrd:".$sensor_type.":MIN",
    "DEF:mrmax=".$rrd_path."mr-".$sensor_type.".rrd:".$sensor_type.":MAX",
    "LINE1.5:mr#FFC930:M-R",
    "VDEF:mravg=mr,AVERAGE",
    "GPRINT:mrmax:MAX:Max\: %6.2lf$sensor_unit",
    "GPRINT:mrmin:MIN:Min\: %6.2lf$sensor_unit",
    "GPRINT:mr:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."mr-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mr);
  }
    
  $options_ee = array(
    "DEF:ee=".$rrd_path."ee-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "DEF:eemin=".$rrd_path."ee-".$sensor_type.".rrd:".$sensor_type.":MIN",
    "DEF:eemax=".$rrd_path."ee-".$sensor_type.".rrd:".$sensor_type.":MAX",
    "LINE1.5:ee#FFC930:E&E",
    "VDEF:eeavg=ee,AVERAGE",
    "GPRINT:eemax:MAX:Max\: %6.2lf$sensor_unit",
    "GPRINT:eemin:MIN:Min\: %6.2lf$sensor_unit",
    "GPRINT:ee:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."ee-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_ee);
  }
  
  $options_rm = array(
    "DEF:rm=".$rrd_path."rm-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "DEF:rmmin=".$rrd_path."rm-".$sensor_type.".rrd:".$sensor_type.":MIN",
    "DEF:rmmax=".$rrd_path."rm-".$sensor_type.".rrd:".$sensor_type.":MAX",
    "LINE1.5:rm#FF303033:R-M",
    "VDEF:rmavg=rm,AVERAGE",
    "GPRINT:rmmax:MAX:Max\: %6.2lf$sensor_unit",
    "GPRINT:rmmin:MIN:Min\: %6.2lf$sensor_unit",
    "GPRINT:rm:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."rm-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_rm);
  }
  
  $options_dc = array(
    "DEF:dc=".$rrd_path."dc-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "DEF:dcmin=".$rrd_path."dc-".$sensor_type.".rrd:".$sensor_type.":MIN",
    "DEF:dcmax=".$rrd_path."dc-".$sensor_type.".rrd:".$sensor_type.":MAX",
    "LINE1.5:dc#30CC3077:D-C",
    "GPRINT:dcmax:MAX:Max\: %6.2lf$sensor_unit",
    "GPRINT:dcmin:MIN:Min\: %6.2lf$sensor_unit",
    "GPRINT:dc:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."dc-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_dc);
  }
  
  $options_sb = array(
    "DEF:sb=".$rrd_path."sb-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "DEF:sbmin=".$rrd_path."sb-".$sensor_type.".rrd:".$sensor_type.":MIN",
    "DEF:sbmax=".$rrd_path."sb-".$sensor_type.".rrd:".$sensor_type.":MAX",
    "LINE1.5:sb#3096FC:S-B",
    "VDEF:sbavg=sb,AVERAGE",
    "GPRINT:sbmax:MAX:Max\: %6.2lf$sensor_unit",
    "GPRINT:sbmin:MIN:Min\: %6.2lf$sensor_unit",
    "GPRINT:sb:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",

    "HRULE:sbavg#3096FCAA:Horizontal line\: S-B Avg Temperature for graph period\\n",
    );
    
  if(rrd_last($rrd_path."sb-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_sb);
  }

  $ret = rrd_graph($output, $options);
  if (! $ret) {
    echo "<b>Graph error: </b>".rrd_error()."\n";
  }
}

//echo "<br />";
echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
echo "<tr><td colspan=\"2\">";

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

echo "</td></tr></table>";

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
