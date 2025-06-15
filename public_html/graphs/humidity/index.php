<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_STRICT);

header("Cache-Control: max-age=300"); //5minutes

$rrd_path = "/home/weather/public_html/graphs/humidity/";
$sensor_type = "humidity";
$sensor_unit = "%%";
$y_label = "Humidity in %";
$description = "Humidity";

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
    );
 $options_mm = array(
    "DEF:mm=".$rrd_path."mm-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "LINE1.5:mm#30FC30AA:M&M",
    "VDEF:mmavg=mm,AVERAGE",
    "GPRINT:mm:MAX:Max\: %6.2lf$sensor_unit",
    "GPRINT:mm:MIN:Min\: %6.2lf$sensor_unit",
    "GPRINT:mm:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
  
  if(rrd_last($rrd_path."mm-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mm);
  }
    
  $options_mr = array(
    "DEF:mr=".$rrd_path."mr-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "LINE1.5:mr#FFC930AA:M-R",
    "VDEF:mravg=mr,AVERAGE",
    "GPRINT:mr:MAX:Max\: %6.2lf$sensor_unit",
    "GPRINT:mr:MIN:Min\: %6.2lf$sensor_unit",
    "GPRINT:mr:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."mr-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mr);
  }
    
  $options_ee = array(
    "DEF:ee=".$rrd_path."ee-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "LINE1.5:ee#FFC930AA:E&E",
    "VDEF:eeavg=ee,AVERAGE",
    "GPRINT:ee:MAX:Max\: %6.2lf$sensor_unit",
    "GPRINT:ee:MIN:Min\: %6.2lf$sensor_unit",
    "GPRINT:ee:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."ee-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_ee);
  }
  
  $options_rm = array(
/*     "DEF:rm=".$rrd_path."rm-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
     "LINE1.5:rm#FF303044:R-M",
     "VDEF:rmavg=rm,AVERAGE",
     "GPRINT:rm:MAX:Max\: %6.2lf$sensor_unit",
     "GPRINT:rm:MIN:Min\: %6.2lf$sensor_unit",
     "GPRINT:rm:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",*/
    );
    
  if(rrd_last($rrd_path."rm-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_rm);
  }
  
  $options_dc = array(
    "DEF:dc=".$rrd_path."dc-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "LINE1.5:dc#30CC3066:D-C",
    "VDEF:dcavg=dc,AVERAGE",
    "GPRINT:dc:MAX:Max\: %6.2lf$sensor_unit",
    "GPRINT:dc:MIN:Min\: %6.2lf$sensor_unit",
    "GPRINT:dc:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."dc-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_dc);
  }
  
  $options_sb = array(
    "DEF:sb=".$rrd_path."sb-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "LINE1.5:sb#3096FCAA:S-B",
    "VDEF:sbavg=sb,AVERAGE",
    "GPRINT:sb:MAX:Max\: %6.2lf$sensor_unit",
    "GPRINT:sb:MIN:Min\: %6.2lf$sensor_unit",
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
