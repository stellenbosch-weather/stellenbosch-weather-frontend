<?php
$rrd_path = "/home/weather/public_html/graphs/rain/";
$sensor_type = "rain";
$sensor_unit = "mm";
$y_label = "Total rain in mm";
$description = "Rain";

create_graph("hour_month.png", "-1 hour", "Yearly cumulative rain over past hour", "averaged over a minute.");
create_graph("day_month.png", "-1 day", "Yearly cumulative rain over past 24 hours", "averaged over a minute.");
create_graph("week_month.png", "-1 week", "Yearly cumulative rain over past week", "averaged over a minute.");
create_graph("month_month.png", "-1 month", "Yearly cumulative rain over past month", "averaged over ten minutes.");
create_graph("year_month.png", "-1 year", "Yearly cumulative rain over past year", "averaged over an hour.");
create_graph("7year_month.png","-7 year", "Yearly cumulative rain over past 7 years", "averaged over a day.");

create_graph_rate("hour_rate.png", "-1 hour", "$description rate past hour", "averaged over a minute.");
create_graph_rate("day_rate.png", "-1 day", "$description rate past 24 hours", "averaged over a minute.");
create_graph_rate("week_rate.png", "-1 week", "$description rate past week", "averaged over a minute.");
create_graph_rate("month_rate.png", "-1 month", "$description rate past month", "averaged over ten minutes.");
create_graph_rate("year_rate.png", "-1 year", "$description rate past year", "averaged over an hour.");
create_graph_rate("7year_rate.png","-7 year", "$description rate past 7 years", "averaged over a day.");
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
echo "<tr><th colspan=\"2\">$description Accumulation</th></tr>";
echo "<tr><td>";
echo "<img src='hour_month.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='hour_rate.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='day_month.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='day_rate.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='week_month.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='week_rate.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='month_month.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='month_rate.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='year_month.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='year_rate.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='7year_month.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='7year_rate.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "</table>";


function create_graph($output, $start, $title, $comment) {
 global $rrd_path, $sensor_type, $sensor_unit, $y_label;
 
 $options = array(
    //"--slope-mode",
    "--full-size-mode",
    "-w 600",
    "-h 250",
    "--start", $start,
    "--title=$title",
    //"--vertical-label=$comment",
    "--vertical-label=$y_label",
    //"--lower=0",
    "--watermark=weather.sun.ac.za",
    //"--lazy", //only update the graph if necessary
    );
 $options_mm = array(
    "DEF:mm=".$rrd_path."mm-".$sensor_type."-month.rrd:".$sensor_type.":MAX",
    "CDEF:cleanmm=mm",
    "AREA:cleanmm#00FF0055:M&M",
    "GPRINT:cleanmm:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );

  if(rrd_last($rrd_path."mm-".$sensor_type."-month.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mm);
  }
    
  $options_ee = array(
    "DEF:ee=".$rrd_path."ee-".$sensor_type."-month.rrd:".$sensor_type.":MAX",
    "CDEF:cleanee=ee",
    "AREA:cleanee#AA000055:E&E",
    "GPRINT:cleanee:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."ee-".$sensor_type."-month.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_ee);
  }
  
  $options_rm = array(
    "DEF:rm=".$rrd_path."rm-".$sensor_type."-month.rrd:".$sensor_type.":MAX",
    "CDEF:cleanrm=rm,UN,PREV,rm,IF",
    "AREA:cleanrm#00AAAA55:R-M",
    "GPRINT:cleanrm:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."rm-".$sensor_type."-month.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_rm);
  }
  
  $options_dc = array(
    "DEF:dc=".$rrd_path."dc-".$sensor_type."-month.rrd:".$sensor_type.":MAX",
    "CDEF:cleandc=dc,UN,PREV,dc,IF",
    "AREA:cleandc#0000FF55:D-C",
    "GPRINT:cleandc:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."dc-".$sensor_type."-month.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_dc);
  }

  $ret = rrd_graph($output, $options, count($options));
  if (! $ret) {
    echo "<b>Graph error: </b>".rrd_error()."\n";
  }
}

function create_graph_rate($output, $start, $title, $comment) {
 global $rrd_path, $sensor_type, $sensor_unit, $y_label;
 
 $options = array(
    "--slope-mode",
    "--full-size-mode",
    "-w 600",
    "-h 250",
    "--start", $start,
    "--title=$title",
    //"--vertical-label=$comment",
    "--vertical-label=Rate in hundredths of mm",
    //"--lower=0",
    "--watermark=weather.sun.ac.za",
    //"--lazy", //only update the graph if necessary
    );
 $options_mm = array(
    "DEF:mm=".$rrd_path."mm-".$sensor_type."-rate.rrd:".$sensor_type.":AVERAGE",
    "LINE1.5:mm#00FF0055:M&M",
    "GPRINT:mm:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );

  if(rrd_last($rrd_path."mm-".$sensor_type."-rate.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mm);
  }
    
  $options_ee = array(
    "DEF:ee=".$rrd_path."ee-".$sensor_type."-rate.rrd:".$sensor_type.":AVERAGE",
    "LINE1.5:ee#AA000055:E&E",
    "GPRINT:ee:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."ee-".$sensor_type."-rate.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_ee);
  }
  
  $options_rm = array(
    "DEF:rm=".$rrd_path."rm-".$sensor_type."-rate.rrd:".$sensor_type.":AVERAGE",
    "LINE1.5:rm#00AAAA55:R-M",
    "GPRINT:rm:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."rm-".$sensor_type."-rate.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_rm);
  }
  
  $options_dc = array(
    "DEF:dc=".$rrd_path."dc-".$sensor_type."-rate.rrd:".$sensor_type.":AVERAGE",
    "LINE1.5:dc#0000FF55:D-C",
    "GPRINT:dc:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."dc-".$sensor_type."-rate.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_dc);
  }

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
