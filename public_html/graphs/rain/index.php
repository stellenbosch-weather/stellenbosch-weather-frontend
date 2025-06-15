<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_STRICT);

header("Cache-Control: max-age=300"); //5minutes

include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/DatabaseConnector.php';
include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/StationDescription.php';

$rrd_path = "/home/weather/public_html/graphs/rain/";
$sensor_type = "rain";
$sensor_unit = "mm";
$y_label = "Total rain in mm";
$description = "Rain";

#create_graph_year("hour_year.png", "-1 hour", "Yearly rain accumulation over past hour", "averaged over a minute.");
#create_graph_year("day_year.png", "-1 day", "Yearly rain accumulation over past 24 hours", "averaged over a minute.");
create_graph_year("week_year.png", "-1 week", "Yearly rain accumulation over past week", "averaged over a minute.");
create_graph_year("month_year.png", "-1 month", "Yearly rain accumulation over past month", "averaged over ten minutes.");
create_graph_year("year_year.png", "-1 year", "Yearly rain accumulation over past year", "averaged over an hour.");
create_graph_year("5year_year.png","-5 year", "Yearly rain accumulation over past 5 years", "averaged over a day.");
create_graph_year("20year_year.png","-20 year", "Yearly rain accumulation over past 20 years", "averaged over a day.");

#create_graph_month("hour_month.png", "-1 hour", "Monthly rain accumulation over past hour", "averaged over a minute.");
#create_graph_month("day_month.png", "-1 day", "Monthly rain accumulation over past 24 hours", "averaged over a minute.");
create_graph_month("week_month.png", "-1 week", "Monthly rain accumulation over past week", "averaged over a minute.");
create_graph_month("month_month.png", "-1 month", "Monthly rain accumulation over past month", "averaged over ten minutes.");
create_graph_month("year_month.png", "-1 year", "Monthly rain accumulation over past year", "averaged over an hour.");
create_graph_month("5year_month.png","-5 year", "Monthly rain accumulation over past 5 years", "averaged over a day.");
create_graph_month("20year_month.png","-20 year", "Monthly rain accumulation over past 20 years", "averaged over a day.");

#create_graph_rate("hour_rate.png", "-1 hour", "$description rate past hour", "averaged over a minute.");
#create_graph_rate("day_rate.png", "-1 day", "$description rate past 24 hours", "averaged over a minute.");
create_graph_rate("week_rate.png", "-1 week", "$description rate past week", "averaged over a minute.");
create_graph_rate("month_rate.png", "-1 month", "$description rate past month", "averaged over ten minutes.");
create_graph_rate("year_rate.png", "-1 year", "$description rate past year", "averaged over an hour.");
create_graph_rate("5year_rate.png","-5 year", "$description rate past 5 years", "averaged over a day.");
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
echo "<img src='5year_year.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='5year_month.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='5year_rate.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='20year_year.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='20year_month.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<!--<img src='20year_rate.png' alt='Generated RRD image'>-->";
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
 $options_mm = array(
    "DEF:mm=".$rrd_path."mm-".$sensor_type."-year.rrd:".$sensor_type.":AVERAGE",
    "CDEF:cleanmm=mm,UN,PREV,mm,IF",
    "LINE1.5:cleanmm#30FC30AA:M&M",
    "AREA:cleanmm#30FC3033:",
    "GPRINT:cleanmm:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );

  if(rrd_last($rrd_path."mm-".$sensor_type."-year.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mm);
  }
    
  $options_mr = array(
    "DEF:mr=".$rrd_path."mr-".$sensor_type."-year.rrd:".$sensor_type.":AVERAGE",
    "CDEF:cleanmr=mr,UN,PREV,mr,IF",
    "LINE1.5:cleanmr#FFC930AA:M-R",
    "AREA:cleanmr#FFC93033:",
    "GPRINT:cleanmr:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."mr-".$sensor_type."-year.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mr);
  }
    
  $options_ee = array(
    "DEF:ee=".$rrd_path."ee-".$sensor_type."-year.rrd:".$sensor_type.":AVERAGE",
    "CDEF:cleanee=ee,UN,PREV,ee,IF",
    "LINE1.5:cleanee#FFC930AA:E&E",
    "AREA:cleanee#FFC93033:",
    "GPRINT:cleanee:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."ee-".$sensor_type."-year.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_ee);
  }
  
  $options_rm = array(
    "DEF:rm=".$rrd_path."rm-".$sensor_type."-year.rrd:".$sensor_type.":AVERAGE",
    "CDEF:cleanrm=rm,UN,PREV,rm,IF",
    "LINE:cleanrm#FF3030AA:R-M",
    "AREA:cleanrm#FF303033:",
    "GPRINT:cleanrm:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."rm-".$sensor_type."-year.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_rm);
  }
  
  $options_dc = array(
    "DEF:dc=".$rrd_path."dc-".$sensor_type."-year.rrd:".$sensor_type.":AVERAGE",
    "CDEF:cleandc=dc,UN,PREV,dc,IF",
    "LINE1.5:cleandc#30CC30AA:D-C",
    "AREA:cleandc#30CC3033:",
    "GPRINT:cleandc:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."dc-".$sensor_type."-year.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_dc);
  }
  
  $options_eb = array(
    "DEF:eb=".$rrd_path."eb-".$sensor_type."-year.rrd:".$sensor_type.":AVERAGE",
    "LINE1:eb#000000:E-B",
    "GPRINT:eb:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."eb-".$sensor_type."-month.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_eb);
  }

//var_dump($options);
  $ret = rrd_graph($output, $options);
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
 $options_mm = array(
    "DEF:mm=".$rrd_path."mm-".$sensor_type."-month.rrd:".$sensor_type.":MAX",
    "CDEF:cleanmm=mm",
    "LINE1.5:cleanmm#30FC30AA:M&M",
    "AREA:cleanmm#30FC3033:",
    "GPRINT:cleanmm:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );

  if(rrd_last($rrd_path."mm-".$sensor_type."-month.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mm);
  }
    
  $options_mr = array(
    "DEF:mr=".$rrd_path."mr-".$sensor_type."-month.rrd:".$sensor_type.":MAX",
    "CDEF:cleanmr=mr",
    "LINE1.5:cleanmr#FFC930AA:M-R",
    "AREA:cleanmr#FFC93033:",
    "GPRINT:cleanmr:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."mr-".$sensor_type."-month.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mr);
  }
    
  $options_ee = array(
    "DEF:ee=".$rrd_path."ee-".$sensor_type."-month.rrd:".$sensor_type.":MAX",
    "CDEF:cleanee=ee",
    "LINE1.5:cleanee#FFC930AA:E&E",
    "AREA:cleanee#FFC93033:",
    "GPRINT:cleanee:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."ee-".$sensor_type."-month.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_ee);
  }
  
  $options_rm = array(
    "DEF:rm=".$rrd_path."rm-".$sensor_type."-month.rrd:".$sensor_type.":MAX",
    "CDEF:cleanrm=rm,UN,PREV,rm,IF",
    "LINE1.5:cleanrm#FF3030AA:R-M",
    "AREA:cleanrm#FF303033:",
    "GPRINT:cleanrm:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."rm-".$sensor_type."-month.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_rm);
  }
  
  $options_dc = array(
    "DEF:dc=".$rrd_path."dc-".$sensor_type."-month.rrd:".$sensor_type.":MAX",
    "CDEF:cleandc=dc,UN,PREV,dc,IF",
    "LINE1.5:cleandc#30CC30AA:D-C",
    "AREA:cleandc#30CC3033:",
    "GPRINT:cleandc:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."dc-".$sensor_type."-month.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_dc);
  }
  
  $options_eb = array(
    "DEF:eb=".$rrd_path."eb-".$sensor_type."-month.rrd:".$sensor_type.":MAX",
    "LINE1:eb#000000:E-B",
    "GPRINT:eb:LAST:Latest total\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."eb-".$sensor_type."-month.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_eb);
  }

  $ret = rrd_graph($output, $options);
  if (! $ret) {
    echo "<b>Graph error: </b>".rrd_error()."\n";
  }
}

function create_graph_rate($output, $start, $title, $comment) {
 global $rrd_path, $sensor_type, $sensor_unit, $y_label;
 
 $options = array(
    "--slope-mode",
    "--full-size-mode",
    "-w 540",
    "-h 250",
    "--start", $start,
    "--title=$title",
    //"--vertical-label=$comment",
    "--vertical-label=Rate in mm per hour",
    //"--lower=0",
    "--watermark=weather.sun.ac.za",
    //"--lazy", //only update the graph if necessary
    );
  $options_mm = array(
    "DEF:mm=".$rrd_path."mm-".$sensor_type."-rate.rrd:".$sensor_type.":MAX",
    "CDEF:mmmmhr=mm,0.6,*", // hundredths of mm per minute to mm per hour
    "LINE1.5:mmmmhr#30FC3055:M&M",
    "VDEF:mmmax=mmmmhr,MAXIMUM",
    "GPRINT:mmmmhr:AVERAGE:Avg\: %6.2lf mm/hr",
    'GPRINT:mmmax:Max\: %6.2lf mm/hr',  
    );
  
  //only print maximum time if the graph plots less than 3024000 seconds
  if(time()-3024000>strtotime ( $start ))
  {
    $options_mm[] = 'GPRINT:mmmax:@ %F\\n:strftime';
  }
  else
  {
    $options_mm[] = 'GPRINT:mmmax:@ %F %R\\n:strftime';
  }

  if(rrd_last($rrd_path."mm-".$sensor_type."-rate.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mm);
  }
    
  $options_mr = array(
    "DEF:mr=".$rrd_path."mr-".$sensor_type."-rate.rrd:".$sensor_type.":MAX",
    "CDEF:mrmmhr=mr,0.6,*", // hundredths of mm per minute to mm per hour
    "LINE1:mrmmhr#FFC930:M-R",
    "VDEF:mrmax=mrmmhr,MAXIMUM",
    "GPRINT:mrmmhr:AVERAGE:Avg\: %6.2lf mm/hr",
    'GPRINT:mrmax:Max\: %6.2lf mm/hr',
    );
  
  if(time()-3024000>strtotime ( $start ))
  {
    $options_mr[] = 'GPRINT:mrmax:@ %F\\n:strftime';
  }
  else
  {
    $options_mr[] = 'GPRINT:mrmax:@ %F %R\\n:strftime';
  }
    
  if(rrd_last($rrd_path."mr-".$sensor_type."-rate.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mr);
  }
    
  $options_ee = array(
    "DEF:ee=".$rrd_path."ee-".$sensor_type."-rate.rrd:".$sensor_type.":MAX",
    "CDEF:eemmhr=ee,0.6,*", // hundredths of mm per minute to mm per hour
    "LINE1:eemmhr#FFC930:E&E",
    "VDEF:eemax=eemmhr,MAXIMUM",
    "GPRINT:eemmhr:AVERAGE:Avg\: %6.2lf mm/hr",
    'GPRINT:eemax:Max\: %6.2lf mm/hr',
    );
  
  if(time()-3024000>strtotime ( $start ))
  {
    $options_ee[] = 'GPRINT:eemax:@ %F\\n:strftime';
  }
  else
  {
    $options_ee[] = 'GPRINT:eemax:@ %F %R\\n:strftime';
  }
    
  if(rrd_last($rrd_path."ee-".$sensor_type."-rate.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_ee);
  }
  
  $options_rm = array(
    "DEF:rm=".$rrd_path."rm-".$sensor_type."-rate.rrd:".$sensor_type.":MAX",
    "CDEF:rmmmhr=rm,0.6,*", // hundredths of mm per minute to mm per hour
    "LINE1:rmmmhr#FF3030:R-M",
    "VDEF:rmmax=rmmmhr,MAXIMUM",
    "GPRINT:rmmmhr:AVERAGE:Avg\: %6.2lf mm/hr",
    'GPRINT:rmmax:Max\: %6.2lf mm/hr',
    );
  
  if(time()-3024000>strtotime ( $start ))
  {
    $options_rm[] = 'GPRINT:rmmax:@ %F\\n:strftime';
  }
  else
  {
    $options_rm[] = 'GPRINT:rmmax:@ %F %R\\n:strftime';
  }
    
  if(rrd_last($rrd_path."rm-".$sensor_type."-rate.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_rm);
  }
  
  $options_dc = array(
    "DEF:dc=".$rrd_path."dc-".$sensor_type."-rate.rrd:".$sensor_type.":MAX",
    "CDEF:dcmmhr=dc,0.6,*", // hundredths of mm per minute to mm per hour
    "LINE1:dcmmhr#30CC30:D-C",
    "VDEF:dcmax=dcmmhr,MAXIMUM",
    "GPRINT:dcmmhr:AVERAGE:Avg\: %6.2lf mm/hr",
    'GPRINT:dcmax:Max\: %6.2lf mm/hr',
    );
  
  if(time()-3024000>strtotime ( $start ))
  {
    $options_dc[] = 'GPRINT:dcmax:@ %F\\n:strftime';
  }
  else
  {
    $options_dc[] = 'GPRINT:dcmax:@ %F %R\\n:strftime';
  }
    
  if(rrd_last($rrd_path."dc-".$sensor_type."-rate.rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_dc);
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
echo "<tr><th>E-B</th><td>Elsenburg Rainfall</td></tr>";

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
