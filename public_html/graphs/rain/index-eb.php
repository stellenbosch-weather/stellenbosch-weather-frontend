<?php
include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/DatabaseConnector.php';
include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/StationDescription.php';

$rrd_path = "/home/weather/public_html/graphs/rain/";
$sensor_type = "rain";
$sensor_unit = "mm";
$y_label = "Total rain in mm";
$description = "Rain";

create_graph_year("hour_year.png", "-1 hour", "Yearly rain accumulation over past hour", "averaged over a minute.");
create_graph_year("day_year.png", "-1 day", "Yearly rain accumulation over past 24 hours", "averaged over a minute.");
create_graph_year("week_year.png", "-1 week", "Yearly rain accumulation over past week", "averaged over a minute.");
create_graph_year("month_year.png", "-1 month", "Yearly rain accumulation over past month", "averaged over ten minutes.");
create_graph_year("year_year.png", "-1 year", "Yearly rain accumulation over past year", "averaged over an hour.");
create_graph_year("7year_year.png","-7 year", "Yearly rain accumulation over past 7 years", "averaged over a day.");

create_graph_month("hour_month.png", "-1 hour", "Monthly rain accumulation over past hour", "averaged over a minute.");
create_graph_month("day_month.png", "-1 day", "Monthly rain accumulation over past 24 hours", "averaged over a minute.");
create_graph_month("week_month.png", "-1 week", "Monthly rain accumulation over past week", "averaged over a minute.");
create_graph_month("month_month.png", "-1 month", "Monthly rain accumulation over past month", "averaged over ten minutes.");
create_graph_month("year_month.png", "-1 year", "Monthly rain accumulation over past year", "averaged over an hour.");
create_graph_month("7year_month.png","-7 year", "Monthly rain accumulation over past 7 years", "averaged over a day.");

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
echo "<tr><th colspan=\"3\">$description Accumulation</th></tr>";
/*echo "<tr><td>";
echo "<img src='hour_year.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='hour_month.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='hour_rate.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='day_year.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='day_month.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='day_rate.png' alt='Generated RRD image'>";
echo "</td></tr>";*/
echo "<tr><td>";
echo "<img src='week_year.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='week_month.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='week_rate.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='month_year.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='month_month.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='month_rate.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='year_year.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='year_month.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='year_rate.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='7year_year.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='7year_month.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='7year_rate.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "</table>";


function create_graph_year($output, $start, $title, $comment) {
 global $rrd_path, $sensor_type, $sensor_unit, $y_label;
 
 $options = array(
    //"--slope-mode",
    "--full-size-mode",
    "-w 540",
    "-h 250",
    "--start", $start,
    "--title=$title",
    //"--vertical-label=$comment",
    "--vertical-label=$y_label",
    //"--lower=0",
    "--watermark=weather.sun.ac.za",
    //"--lazy", //only update the graph if necessary
    );
 
  
  $options_eb = array(
    "DEF:eb=".$rrd_path."eb-".$sensor_type."-year.rrd:".$sensor_type.":AVERAGE",
    "LINE1.5:eb#000000:E-B",
    "GPRINT:eb:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."eb-".$sensor_type."-year.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_eb);
  }

  $ret = rrd_graph($output, $options, count($options));
  if (! $ret) {
    echo "<b>Graph error: </b>".rrd_error()."\n";
  }
}

function create_graph_month($output, $start, $title, $comment) {
 global $rrd_path, $sensor_type, $sensor_unit, $y_label;
 
 $options = array(
    //"--slope-mode",
    "--full-size-mode",
    "-w 540",
    "-h 250",
    "--start", $start,
    "--title=$title",
    //"--vertical-label=$comment",
    "--vertical-label=$y_label",
    //"--lower=0",
    "--watermark=weather.sun.ac.za",
    //"--lazy", //only update the graph if necessary
    );
 
  
  $options_eb = array(
    "DEF:eb=".$rrd_path."eb-".$sensor_type."-month.rrd:".$sensor_type.":MAX",
    "LINE1.5:eb#000000:E-B",
    "GPRINT:eb:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );
    
  #if(rrd_last($rrd_path."eb-".$sensor_type."-month.rrd") > strtotime ( $start ))
  #{
    $options = array_merge($options, $options_eb);
  #}

  $ret = rrd_graph($output, $options, count($options));
  if (! $ret) {
    echo "<b>Graph error: </b>".rrd_error()."\n";
  }
}




echo "</td></tr></table>";

?>
    </center>
    <br>
    </div>
  </body>
</html>
