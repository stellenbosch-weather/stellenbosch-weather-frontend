<?php

$rrd_path = "/home/weather/public_html/graphs/temperature/";
$sensor_type = "temperature";
$sensor_unit = "°C";
$y_label = "Temperature in C";
$description = "Temperature";

create_graph("day-homepage.png", "Daily $description", "averaged over a minute.");
header("content-type: image/png");
readfile("day-homepage.png");
/*
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
<?php
echo "<img src='day.png' alt='Generated RRD image'>";
*/

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

function create_graph($output, $title, $comment) {
 global $rrd_path, $sensor_type, $sensor_unit, $y_label;
 $options = array(
    "--slope-mode",
    "--full-size-mode",
    "-w 550",
    "-h 300",
    "--start", "now-24h",
    "--title=$title",
    //"--vertical-label=$comment",
    "--vertical-label=$y_label",
    //"--lower=0",
    "--watermark=weather.sun.ac.za",
    //"--lazy", //only update the graph if necessary
    );
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
  
  if(rrd_last($rrd_path."mm-".$sensor_type.".rrd") > strtotime ( "-1 day" ))
  {
    $options = array_merge($options, $options_mm);
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
    
  if(rrd_last($rrd_path."ee-".$sensor_type.".rrd") > strtotime ( "-1 day" ))
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
    
  if(rrd_last($rrd_path."rm-".$sensor_type.".rrd") > strtotime ( "-1 day" ))
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
    
  if(rrd_last($rrd_path."dc-".$sensor_type.".rrd") > strtotime ( "-1 day" ))
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
    );
    
  if(rrd_last($rrd_path."sb-".$sensor_type.".rrd") > strtotime ( "-1 day" ))
  {
    $options = array_merge($options, $options_sb);
  }

  // print(implode(" ",$options));

  $ret = rrd_graph($output, $options);
  if (! $ret) {
    echo "<b>Graph error: </b>".rrd_error()."\n";
  }
}

/*
?>
  </body>
</html>*/
