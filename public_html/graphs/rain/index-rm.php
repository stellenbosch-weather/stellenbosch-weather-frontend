<?php
$rrd_path = "/home/weather/public_html/graphs/rain/";
$sensor_type = "rain";
$sensor_unit = "mm";
$y_label = "Rain in mm";
$description = "Rain";

create_graph("hour.png", "-1h", "Hourly $description", "averaged over a minute.");
create_graph("day.png", "-1d", "Daily $description", "averaged over a minute.");
create_graph("week.png", "-1w", "Weekly $description", "averaged over a minute.");
create_graph("month.png", "-1m", "Monthly $description", "averaged over ten minutes.");
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
echo "<img src='hour.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='day.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='week.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='month.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='year.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='7year.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "</table>";


function create_graph($output, $start, $title, $comment) {
 global $rrd_path, $sensor_type, $sensor_unit, $y_label;
 
 $timeFactor = 60;
 if($start=="-1h") $timeFactor=2;
 if($start=="-1w") $timeFactor=1440;
 if($start=="-1m") $timeFactor=10080;
 if($start=="-1y") $timeFactor=43200;
 if($start=="-7y") $timeFactor=525600;
 
 $options = array(
    //"--slope-mode",
    "--start", $start,
    "--title=$title",
    //"--vertical-label=$comment",
    "--vertical-label=$y_label",
    //"--lower=0",
    "--watermark=weather.sun.ac.za",
    //"--lazy", //only update the graph if necessary

    "DEF:rm=".$rrd_path."rm-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "CDEF:rainmm=rm,$timeFactor,*",#i only have an rra containing mm/minute, so multiply by 1 minute
    "LINE1:rainmm#00FFFF:",
    "AREA:rainmm#00FFFF44:R-M",
    "GPRINT:rainmm:MAX:Max\: %6.2lf$sensor_unit",
    "GPRINT:rainmm:MIN:Min\: %6.2lf$sensor_unit",
    "GPRINT:rainmm:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
  );

  $ret = rrd_graph($output, $options, count($options));
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
