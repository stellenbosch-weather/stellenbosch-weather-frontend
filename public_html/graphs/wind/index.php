<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_STRICT);

header("Cache-Control: max-age=300"); //5minutes

$rrd_path = "/home/weather/public_html/graphs/wind/";
$sensor_type = "windspeed";
$second_sensor_type = "winddir";
$sensor_unit = "km/h";
$second_sensor_unit = "°";
$y_label = "Windspeed in km/h";
$second_y_label = "Wind direction";
$description = "Windspeed";
$second_description = "wind direction";

//create_graph("hour.png", "-1h", "Hourly $description", "averaged over a minute.");
create_speed_graph("speedday.png", "-1 day", "Daily $description", "averaged over a minute.");
create_speed_graph("speedweek.png", "-1 week", "Weekly $description", "averaged over a minute.");
create_speed_graph("speedmonth.png", "-1 month", "Monthly $description", "averaged over ten minutes.");
create_speed_graph("speed3month.png", "-3 month", "3 Monthly $description", "averaged over a minute.");
create_speed_graph("speedyear.png", "-1 year", "Annual $description", "averaged over an hour.");
create_speed_graph("speed7year.png","-7 year", "$description past 7 years", "averaged over a day.");

create_dir_graph("dirday.png", "-1 day", "Daily $second_description", "averaged over a minute.");
create_dir_graph("dirweek.png", "-1 week", "Weekly $second_description", "averaged over a minute.");
create_dir_graph("dirmonth.png", "-1 month", "Monthly $second_description", "averaged over ten minutes.");
create_dir_graph("dir3month.png", "-3 month", "3 Monthly $second_description", "averaged over a minute.");
create_dir_graph("diryear.png", "-1 year", "Annual $second_description", "averaged over an hour.");
create_dir_graph("dir7year.png","-7 year", "$second_description past 7 years", "averaged over a day.");

create_dirscaled_graph("dirscaledday.png", "-1 day", "Daily $second_description scaled by speed", "averaged over a minute.");
create_dirscaled_graph("dirscaledweek.png", "-1 week", "Weekly $second_description scaled by speed", "averaged over a minute.");
create_dirscaled_graph("dirscaledmonth.png", "-1 month", "Monthly $second_description scaled by speed", "averaged over ten minutes.");
create_dirscaled_graph("dirscaled3month.png", "-3 month", "3 Monthly $second_description scaled by speed", "averaged over a minute.");
create_dirscaled_graph("dirscaledyear.png", "-1 year", "Annual $second_description scaled by speed", "averaged over an hour.");
create_dirscaled_graph("dirscaled7year.png","-7 year", "$second_description scaled by speed, past 7 years", "averaged over a day.");
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
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
  </head>
  <body>
    <center>
<?php
echo "<table>";
echo "<tr><th colspan=\"3\">Wind Measurements</th></tr>";
echo "<tr><td>";
echo "<img src='speedday.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='dirday.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='dirscaledday.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='speedweek.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='dirweek.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='dirscaledweek.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='speedmonth.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='dirmonth.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='dirscaledmonth.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='speed3month.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='dir3month.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='dirscaled3month.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='speedyear.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='diryear.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='dirscaledyear.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='speed7year.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='dir7year.png' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='dirscaled7year.png' alt='Generated RRD image'>";
echo "</td></tr>";
echo "</table>";


function create_speed_graph($output, $start, $title, $comment) {
 global $rrd_path, $sensor_type, $sensor_unit, $y_label;
 $options = array(
    "--slope-mode",
    "--full-size-mode",
    "-w 497",
    "-h 235",
    "--start", $start,
    "--title=$title",
    //"--vertical-label=$comment",
    "--vertical-label=$y_label",
    //"--lower=0",
    "--watermark=weather.sun.ac.za",
    //"--lazy", //only update the graph if necessary
    );
    
 $options_mm = array(
    "DEF:mmavg=".$rrd_path."mm-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "DEF:mmmin=".$rrd_path."mm-".$sensor_type.".rrd:".$sensor_type.":MIN",
    "DEF:mmmax=".$rrd_path."mm-".$sensor_type.".rrd:".$sensor_type.":MAX",
    "LINE1:mmavg#30FC30AA:M&M",
    "AREA:mmmax#30FC3033",
    //"AREA:mmmin#FFFFFF",
    "GPRINT:mmmax:MAX:Max\: %6.2lf$sensor_unit",
    "GPRINT:mmavg:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
  
  if(rrd_last($rrd_path."mm-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mm);
  }
    
  $options_mr = array(
    "DEF:mravg=".$rrd_path."mr-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "DEF:mrmin=".$rrd_path."mr-".$sensor_type.".rrd:".$sensor_type.":MIN",
    "DEF:mrmax=".$rrd_path."mr-".$sensor_type.".rrd:".$sensor_type.":MAX",
    "LINE1:mravg#FFC93088:M-R",
    "AREA:mrmax#FFC93033",
    //"AREA:mrmin#FFFFFF",
    "GPRINT:mrmax:MAX:Max\: %6.2lf$sensor_unit",
    "GPRINT:mravg:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."mr-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mr);
  }
    
  $options_ee = array(
    "DEF:eeavg=".$rrd_path."ee-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "DEF:eemin=".$rrd_path."ee-".$sensor_type.".rrd:".$sensor_type.":MIN",
    "DEF:eemax=".$rrd_path."ee-".$sensor_type.".rrd:".$sensor_type.":MAX",
    "LINE1:eeavg#FFC930AA:E&E",
    "AREA:eemax#FFC93033",
    //"AREA:eemin#FFFFFF",
    "GPRINT:eemax:MAX:Max\: %6.2lf$sensor_unit",
    "GPRINT:eeavg:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."ee-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_ee);
  }
  
  $options_sb = array(
    "DEF:sbavg=".$rrd_path."sb-".$sensor_type.".rrd:".$sensor_type.":AVERAGE",
    "DEF:sbmin=".$rrd_path."sb-".$sensor_type.".rrd:".$sensor_type.":MIN",
    "DEF:sbmax=".$rrd_path."sb-".$sensor_type.".rrd:".$sensor_type.":MAX",
    "LINE1:sbavg#3096FCAA:S-B",
    "AREA:sbmax#3096FC33",
    //"AREA:sbmin#FFFFFF",
    "GPRINT:sbmax:MAX:Max\: %6.2lf$sensor_unit",
    //"GPRINT:sbmin:MIN:Min\: %6.2lf$sensor_unit",
    "GPRINT:sbavg:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
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

function create_dir_graph($output, $start, $title, $comment) {
 global $rrd_path, $second_sensor_type, $second_sensor_unit, $second_y_label;
 $sensor_type = $second_sensor_type;
 $sensor_unit = $second_sensor_unit;
 $y_label = $second_y_label;
 $options = array(
    "--slope-mode",
    "--full-size-mode",
    "-w 497",
    "-h 235",
    "--start", $start,
    "--title=$title",
    //"--vertical-label=$comment",
    "--vertical-label=$y_label",
    "--lower=0",
    "--upper-limit=360",
    "--alt-autoscale-max",
    "--y-grid=45:1",
    "--watermark=weather.sun.ac.za",
    //"--lazy", //only update the graph if necessary
    
    //Shaded bands
    "AREA:45#FFCCCC88:North:STACK",
    "AREA:90#CCFFCC88:East:STACK",
    "AREA:90#FFFFCC88:South:STACK",
    "AREA:90#CCCCFF88:West\\n:STACK",
    "AREA:45#FFCCCC88::STACK",
    );
    
 $options_mm = array(
    "DEF:mmx=".$rrd_path."mm-".$sensor_type.".rrd:dirx:AVERAGE",
    "DEF:mmy=".$rrd_path."mm-".$sensor_type.".rrd:diry:AVERAGE",
    
    "CDEF:mmangle=mmx,mmy,ATAN2,RAD2DEG",
    "CDEF:mmwinddir=mmangle,0,LT,mmangle,360,+,mmangle,IF",
    
    "LINE1:mmwinddir#30FC30AA:M&M",
    "GPRINT:mmwinddir:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
  
  if(rrd_last($rrd_path."mm-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mm);
  }
    
  $options_mr = array(
    "DEF:mrx=".$rrd_path."mr-".$sensor_type.".rrd:dirx:AVERAGE",
    "DEF:mry=".$rrd_path."mr-".$sensor_type.".rrd:diry:AVERAGE",
    
    //"CDEF:mrxi=mrx,-1,*",
    //"CDEF:mryi=mry,-1,*",
    "CDEF:mrangle=mrx,mry,ATAN2,RAD2DEG",
    "CDEF:mrwinddir=mrangle,0,LT,mrangle,360,+,mrangle,IF",
    
    "LINE1:mrwinddir#FFC930AA:M-R",
    "GPRINT:mrwinddir:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."mr-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mr);
  }
    
  $options_ee = array(
    "DEF:eex=".$rrd_path."ee-".$sensor_type.".rrd:dirx:AVERAGE",
    "DEF:eey=".$rrd_path."ee-".$sensor_type.".rrd:diry:AVERAGE",
    
    "CDEF:eeangle=eex,eey,ATAN2,RAD2DEG",
    "CDEF:eewinddir=eeangle,0,LT,eeangle,360,+,eeangle,IF",
    
    "LINE1:eewinddir#FFC930AA:E&E",
    "GPRINT:eewinddir:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."ee-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_ee);
  }
    
  $options_sb = array(
    "DEF:sbx=".$rrd_path."sb-".$sensor_type.".rrd:dirx:AVERAGE",
    "DEF:sby=".$rrd_path."sb-".$sensor_type.".rrd:diry:AVERAGE",
    
    "CDEF:sbangle=sbx,sby,ATAN2,RAD2DEG",
    "CDEF:sbwinddir=sbangle,0,LT,sbangle,360,+,sbangle,IF",
    
    "LINE1:sbwinddir#3096FCAA:S-B",
    "GPRINT:sbwinddir:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
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

function create_dirscaled_graph($output, $start, $title, $comment) {
 global $rrd_path, $second_sensor_type, $second_sensor_unit, $second_y_label;
 $sensor_type = "winddirscaled";
 $sensor_unit = $second_sensor_unit;
 $y_label = $second_y_label;
 $options = array(
    "--slope-mode",
    "--full-size-mode",
    "-w 497",
    "-h 235",
    "--start", $start,
    "--title=$title",
    //"--vertical-label=$comment",
    "--vertical-label=$y_label",
    "--lower=0",
    "--upper-limit=360",
    "--alt-autoscale-max",
    "--y-grid=45:1",
    "--watermark=weather.sun.ac.za",
    //"--lazy", //only update the graph if necessary
    
    //Shaded bands
    "AREA:45#FFCCCC88:North:STACK",
    "AREA:90#CCFFCC88:East:STACK",
    "AREA:90#FFFFCC88:South:STACK",
    "AREA:90#CCCCFF88:West\\n:STACK",
    "AREA:45#FFCCCC88::STACK",
    );
    
 $options_mm = array(
    "DEF:mmx=".$rrd_path."mm-".$sensor_type.".rrd:dirx:AVERAGE",
    "DEF:mmy=".$rrd_path."mm-".$sensor_type.".rrd:diry:AVERAGE",
    
    "CDEF:mmangle=mmx,mmy,ATAN2,RAD2DEG",
    "CDEF:mmwinddir=mmangle,0,LT,mmangle,360,+,mmangle,IF",
    
    "LINE1:mmwinddir#30FC30AA:M&M",
    "GPRINT:mmwinddir:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
  
  if(rrd_last($rrd_path."mm-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mm);
  }
    
  $options_mr = array(
    "DEF:mrx=".$rrd_path."mr-".$sensor_type.".rrd:dirx:AVERAGE",
    "DEF:mry=".$rrd_path."mr-".$sensor_type.".rrd:diry:AVERAGE",
    
    //"CDEF:mrxi=mrx,-1,*",
    //"CDEF:mryi=mry,-1,*",
    "CDEF:mrangle=mrx,mry,ATAN2,RAD2DEG",
    "CDEF:mrwinddir=mrangle,0,LT,mrangle,360,+,mrangle,IF",
    
    "LINE1:mrwinddir#FFC930AA:M-R",
    "GPRINT:mrwinddir:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."mr-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_mr);
  }
    
  $options_ee = array(
    "DEF:eex=".$rrd_path."ee-".$sensor_type.".rrd:dirx:AVERAGE",
    "DEF:eey=".$rrd_path."ee-".$sensor_type.".rrd:diry:AVERAGE",
    
    "CDEF:eeangle=eex,eey,ATAN2,RAD2DEG",
    "CDEF:eewinddir=eeangle,0,LT,eeangle,360,+,eeangle,IF",
    
    "LINE1:eewinddir#FFC930AA:E&E",
    "GPRINT:eewinddir:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
    );
    
  if(rrd_last($rrd_path."ee-".$sensor_type.".rrd") > strtotime ( $start ))
  {
    $options = array_merge($options, $options_ee);
  }
    
  $options_sb = array(
    "DEF:sbx=".$rrd_path."sb-".$sensor_type.".rrd:dirx:AVERAGE",
    "DEF:sby=".$rrd_path."sb-".$sensor_type.".rrd:diry:AVERAGE",
    
    "CDEF:sbangle=sbx,sby,ATAN2,RAD2DEG",
    "CDEF:sbwinddir=sbangle,0,LT,sbangle,360,+,sbangle,IF",
    
    #"CDEF:wrongdata=sbangle,UN,INF,UNKN,IF",
    #"AREA:wrongdata#FF0000:no wind",
    
    "LINE1:sbwinddir#3096FCAA:S-B",
    "GPRINT:sbwinddir:AVERAGE:Avg\: %6.2lf$sensor_unit\\n",
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
