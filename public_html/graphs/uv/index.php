<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_STRICT);

header("Cache-Control: max-age=300"); //5minutes

$rrd_path = "/home/weather/public_html/graphs/uv/";
$sensor_type = "uv";
$sensor_unit = "W/m²";
$y_label = "UV in Watt per square metre";
$description = "UV Intensity";

create_graph("day.png", "-1d", "Daily $description", "averaged over a minute.");
create_graph("week.png", "-1w", "Weekly $description", "averaged over a minute.");
create_graph("month.png", "-1m", "Monthly $description", "averaged over ten minutes.");
create_graph("3month.png", "-3m", "3 Monthly $description", "averaged over ten minutes.");
create_graph("year.png", "-1y", "Annual $description", "averaged over an hour.");
create_graph("7year.png","-7y", "$description past 7 years", "averaged over a day.");
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
echo "<img src='day.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='week.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='month.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='3month.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='year.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='7year.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "</table>";


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
    
    "DEF:uva=".$rrd_path."sb-".$sensor_type.".rrd:".$sensor_type."A:AVERAGE",
    "DEF:uvamax=".$rrd_path."sb-".$sensor_type.".rrd:".$sensor_type."A:MAX",
    "LINE1.5:uva#CC3118AA:UV-A - Solys 2",
    "AREA:uvamax#EA644A33",
    "GPRINT:uvamax:MAX:Max\: %6.2lf $sensor_unit",
    "GPRINT:uva:AVERAGE:Avg\: %6.2lf $sensor_unit\\n",
    
    "DEF:uvb=".$rrd_path."sb-".$sensor_type.".rrd:".$sensor_type."B:AVERAGE",
    "DEF:uvbmax=".$rrd_path."sb-".$sensor_type.".rrd:".$sensor_type."B:MAX",
    "LINE1.5:uvb#4D18E4AA:UV-B - Solys 2",
    "AREA:uvbmax#7648EC33",
    "GPRINT:uvbmax:MAX:Max\: %6.2lf $sensor_unit",
    "GPRINT:uvb:AVERAGE:Avg\: %6.2lf $sensor_unit\\n",

  );

  $ret = rrd_graph($output, $options);
  if (! $ret) {
    echo "<b>Graph error: </b>".rrd_error()."\n";
  }
}

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
