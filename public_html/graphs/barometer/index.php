<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_STRICT);

header("Cache-Control: max-age=300"); //5minutes

$rrd_path = "/home/weather/public_html/graphs/barometer/";
$sensor_type = "barometer";
$sensor_unit = "hPa";
$y_label = "Pressure in hPa";
$description = "Relative Barometric Pressure";

create_graph("hour.png", "-1 hour", "Hourly $description", "averaged over a minute.");
create_graph("day.png", "-1 day", "Daily $description", "averaged over a minute.");
create_graph("week.png", "-1 week", "Weekly $description", "averaged over a minute.");
create_graph("month.png", "-1 month", "Monthly $description", "averaged over ten minutes.");
create_graph("3month.png", "-3 month", "3 Monthly $description", "averaged over ten minutes.");
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
echo "<tr><td colspan=\"2\">In Stellenbosch the absolute barometric pressure is about 15hPa lower than the equivalent at sea level (relative barometric pressure).<br />
The dashed line indicates the definition of 1 atmosphere, which is the mean absolute barometric pressure at the mean sea level.</td></tr>";
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
    "--alt-autoscale",
    "--alt-y-grid",
    "--units-exponent=0",
    "--watermark=weather.sun.ac.za",
    //"--lazy", //only update the graph if necessary
    
    "HRULE:1013.25#000000:Dashes\: Mean atmospheric pressure at mean sea level (1 atm = 1013.25hPa)\\n:dashes",
    );
    
 $options_mm = array(
    "DEF:mm=".$rrd_path."mm-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "LINE1.5:mm#FF0000AA:M&M",
    "VDEF:mmavg=mm,AVERAGE",
    "GPRINT:mm:MAX:Max\: %6.2lf$sensor_unit\t",
    "GPRINT:mm:MIN:Min\: %6.2lf$sensor_unit\t",
    "GPRINT:mm:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
  
  if(rrd_last($rrd_path."mm-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mm);
  }
    
  $options_mr = array(
    "DEF:mr=".$rrd_path."mr-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "LINE1.5:mr#00FF00AA:M-R",
    "VDEF:mravg=mr,AVERAGE",
    "GPRINT:mr:MAX:Max\: %6.2lf$sensor_unit\t",
    "GPRINT:mr:MIN:Min\: %6.2lf$sensor_unit\t",
    "GPRINT:mr:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."mr-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mr);
  }
    
  $options_ee = array(
    "DEF:ee=".$rrd_path."ee-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "LINE1.5:ee#00FF00AA:E&E",
    "VDEF:eeavg=ee,AVERAGE",
    "GPRINT:ee:MAX:Max\: %6.2lf$sensor_unit\t",
    "GPRINT:ee:MIN:Min\: %6.2lf$sensor_unit\t",
    "GPRINT:ee:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."ee-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_ee);
  }
  
  $options_dc = array(
    "DEF:dc=".$rrd_path."dc-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "LINE1.5:dc#00FFFFAA:D-C",
    "GPRINT:dc:MAX:Max\: %6.2lf$sensor_unit\t",
    "GPRINT:dc:MIN:Min\: %6.2lf$sensor_unit\t",
    "GPRINT:dc:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."dc-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_dc);
  }
  
  $options_sb = array(
    "DEF:sb=".$rrd_path."sb-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "LINE1.5:sb#0000FFAA:S-B",
    "VDEF:sbavg=sb,AVERAGE",
    "GPRINT:sb:MAX:Max\: %6.2lf$sensor_unit\t",
    "GPRINT:sb:MIN:Min\: %6.2lf$sensor_unit\t",
    "GPRINT:sb:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
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
