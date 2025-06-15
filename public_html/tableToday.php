<?php 
include 'website-api/TableTodayAPIall.php';
include 'sources/MeasurementDescriptions.php';

$rainUnit = "mm";
$rainRateUnit = "mm/h";
$temperatureUnit = "&deg;C";
$windUnit = "km/h";
$humidUnit = "&#37;";
$baroUnit = "hPa";

$today = date('Y')."-".date('m')."-".date('d');
$yesterday = date('Y')."-".date('m')."-".(date('d') - 1);

$updateDateMM = $latestMM["date"];
$updateTimeMM = substr($latestMM["time"], 0, 5);
$updateDateEE = $latestEE["date"];
$updateTimeEE = substr($latestEE["time"], 0, 5);
$updateDateSB = $latestSB["Date"];
$updateTimeSB = substr($latestSB["Time"], 0, 5);

$fontColourMM = "gray";
$fontColourEE = "gray";
$fontColourSB = "gray";

if ($updateDateMM == $today){ 
  $updateDateMM = "Today";
  $fontColourMM = "black";
}
elseif ($updateDateMM == $yesterday){ $updateDateMM = "Yesterday";}
if ($updateDateEE == $today){ 
  $updateDateEE = "Today";
  $fontColourEE = "black";
}
elseif ($updateDateEE == $yesterday){ $updateDateEE = "Yesterday";}
if ($updateDateSB == $today){ 
  $updateDateSB = "Today";
  $fontColourSB = "black";
}
elseif ($updateDateSB == $yesterday){ $updateDateSB = "Yesterday";}

if ($latestMM["windspeed"] != "0"){ $windMM = $latestMM["windspeed"]." ".$windUnit.", ".$latestMM["direction"]; }
else{ $windMM = "Calm"; }

if ($latestEE["windspeed"] != "0"){ $windEE = $latestEE["windspeed"]." ".$windUnit.", ".$latestEE["direction"]; }
else{ $windEE = "Calm"; }

if ($latestSB["WS_ms_S_WVT"] != "0")
{ 
  $dirs = array("N","NNE","NE","ENE","E","ESE","SE","SSE",
                "S","SSW","SW","WSW","W","WNW","NW","NNW");
  $windSB = round($latestSB["WS_ms_S_WVT"]*3.6,2)." ".$windUnit.", ".
              $dirs[round($latestSB["WindDir_D1_WVT"]/22.5)]; 
}
else
{ 
  $windSB = "Calm"; 
}

if ($avgWind10MM != "0"){ $avgWind10MM = $avgWind10MM." ".$windUnit.", ".$avgDir10MM; }
else{ $avgWind10MM = "Calm"; }

if ($avgWind10EE != "0"){ $avgWind10EE = $avgWind10EE." ".$windUnit.", ".$avgDir10EE; }
else{ $avgWind10EE = "Calm"; }

if ($avgWind10SB != "0"){ $avgWind10SB = $avgWind10SB." ".$windUnit.", ".$avgDir10SB; }
else{ $avgWind10SB = "Calm"; }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Today's Measurements</title>
<script src="sources/jquery.js" type="text/javascript"></script>
<link href="sources/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<style type="text/css">
<!--
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	color: #000000;
}
body {
	background-color: #A8CBFF;
}
.style1 {color: #FFFFFF}

td.cellLink
{
  text-align: center; 
  background: #426ecf;
}
a.cellLink
{
  display: inline-block; 
  height:100%; 
  width:100%;
}
td.cellLink:hover
{
  background: #738fcf;
}
-->
</style>
<script src="sources/facebox/facebox.js" type="text/javascript"></script>
<!--<script type="text/javascript"> 
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
        loading_image : 'loading.gif',
        close_image   : 'closelabel.gif'
      }) 
    })
</script>-->
</head>

<body>
<div align="center">
  <table width="" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td colspan="6" style="padding: 0px">
      <? echo "<small>Page loaded in ".$loadTime." seconds</small>"; ?>
    </td>
  </tr>
  <tr>
    <td colspan="6" style="padding-top: 0px; padding-bottom: 10px; font-weight: bold; font-size: 24pt; text-align: center;">
      Today's Weather
    </td>
  </tr>
  
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th width="220" bgcolor="#426ecf" scope="col"><span class="style1">Vantage Pro M&amp;M</span></th>
      <th width="220" bgcolor="#426ecf" scope="col"><span class="style1">Vantage Pro E&amp;E</span></th>
      <th width="220" bgcolor="#426ecf" scope="col"><span class="style1">Sonbesie</span></th>
      <th width="55" bgcolor="#426ecf" scope="col"><span class="style1">Graph</span></th>
    </tr>
    
    
    <tr>
      <th colspan="2" bgcolor="#426ecf" scope="col"><span class="style1">Last update </span></th>
      <th bgcolor="#deefff" scope="col" style="color: <? echo $fontColourMM; ?>">
        <? echo $updateDateMM." at ".$updateTimeMM; ?>
      </th>
      <th bgcolor="#deefff" scope="col" style="color: <? echo $fontColourEE; ?>">
        <? echo $updateDateEE." at ".$updateTimeEE; ?></th>
      <th bgcolor="#deefff" scope="col" style="color: <? echo $fontColourSB; ?>">
        <? echo $updateDateSB." at ".$updateTimeSB; ?></th>
    </tr>
    <tr>
      <th colspan="2" bgcolor="#426ECF" scope="row" title="Have a look on Google Earth"><span class="style1">Location</span></th>
      <td bgcolor="#DEEFFF" style="color: <? echo $fontColourMM; ?>">
        33&deg;55'43''S&nbsp;&nbsp;18&deg;51'55''E</td>
      <td bgcolor="#DEEFFF" style="color: <? echo $fontColourEE; ?>">
        33&deg;55'43''S&nbsp;&nbsp;18&deg;52'02''E</td>
      <td bgcolor="#DEEFFF" style="color: <? echo $fontColourSB; ?>">
        33&deg;55'41''S&nbsp;&nbsp;18&deg;51'55''E</td>
    </tr>
    <tr>
      <th colspan="2" bgcolor="#426ecf" scope="row" title="Calculated by using the local measurements. Not accurate."><span class="style1">Quick Forecast</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $quickforecastMM; ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $quickforecastEE; ?></td>
      <td><!--Sonbesie quick forecast--></td>
    </tr>
    
    
    <tr>
      <td colspan="5"></td>
    </tr>
    
    
    <tr>
      <th width="110" rowspan="3" bgcolor="#426ecf" scope="row" title="Temperature measured in degrees Celsius.">
        <span class="style1">Temperature</span>
      </th>
      <th width="80" bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $latestMM["tempout"].$temperatureUnit; ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $latestEE["tempout"].$temperatureUnit; ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourSB; ?>">
        <? echo $latestSB["AirTC_Avg"].$temperatureUnit; ?></td>
      <td rowspan="3" onclick="jQuery.facebox({ image: 'graphs/TemperatureToday.php' })" class="cellLink"><a href="#" class="cellLink">
      <div style="position:relative; top:25%;"><img width="40" title="Temperature Graph" src="sources/Piebar_icon.gif"></div></a></td>
      
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $maxtempMM.$temperatureUnit." @ ".substr($maxtemptimeMM,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $maxtempEE.$temperatureUnit." @ ".substr($maxtemptimeEE,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourSB; ?>">
        <? echo $maxtempSB.$temperatureUnit." @ ".substr($maxtemptimeSB,0,5); ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Min</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $mintempMM.$temperatureUnit." @ ".substr($mintemptimeMM,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $mintempEE.$temperatureUnit." @ ".substr($mintemptimeEE,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourSB; ?>">
        <? echo $mintempSB.$temperatureUnit." @ ".substr($mintemptimeSB,0,5); ?></td>
    </tr>
    
    
    <tr>
      <td colspan="5"></td>
    </tr>
    
    
    <tr>
      <th rowspan="3" bgcolor="#426ecf" scope="row"><span class="style1">Wind</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $windMM; ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $windEE; ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourSB; ?>">
        <? echo $windSB; ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">10m Avg</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $avgWind10MM; ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $avgWind10EE; ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourSB; ?>">
        <? echo $avgWind10SB; ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $maxWindMM." ".$windUnit.", ".$maxWindDirMM." @ ".substr($maxWindTimeMM,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $maxWindEE." ".$windUnit.", ".$maxWindDirEE." @ ".substr($maxWindTimeEE,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourSB; ?>">
        <? echo $maxWindSB." ".$windUnit.", ".$maxWindDirSB." @ ".substr($maxWindTimeSB,0,5); ?></td>
    </tr>
    <tr>
      <th rowspan="2" bgcolor="#426ecf" scope="row" title="<? echo $windChillDesc; ?>"><span class="style1">Wind chill </span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $latestMM["windchill"].$temperatureUnit; ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $latestEE["windchill"].$temperatureUnit; ?></td>
      <td><!--Sonbesie--></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Min</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $minWindchillMM.$temperatureUnit." @ ".substr($minWindchillTimeMM,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $minWindchillEE.$temperatureUnit." @ ".substr($minWindchillTimeEE,0,5); ?></td>
      <td><!--Sonbesie--></td>
    </tr>
    
    
    <tr>
      <td colspan="5"></td>
    </tr>
    
    
    <tr>
      <th rowspan="3" bgcolor="#426ecf" scope="row" title="<? echo $dewPointDesc; ?>"><span class="style1">Dew Point </span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $latestMM["dew"].$temperatureUnit; ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $latestEE["dew"].$temperatureUnit; ?></td>
      <td><!--Sonbesie--></td>
      <td rowspan="3" bgcolor="#426ecf" onclick="jQuery.facebox({ image: 'graphs/DewPointToday.php' })" class="cellLink"><a href="#" class="cellLink">
      <div style="position:relative; top:25%;"><img width="40" title="Dew Point Graph" src="sources/Piebar_icon.gif" style="cursor:hand">
      </div></a></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Min</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $mindewMM.$temperatureUnit." @ ".substr($mindewtimeMM,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $mindewEE.$temperatureUnit." @ ".substr($mindewtimeEE,0,5); ?></td>
      <td><!--Sonbesie--></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $maxdewMM.$temperatureUnit." @ ".substr($maxdewtimeMM,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $maxdewEE.$temperatureUnit." @ ".substr($maxdewtimeEE,0,5); ?></td>
      <td><!--Sonbesie--></td>
    </tr>
    <tr>
      <th rowspan="3" bgcolor="#426ecf" scope="row" title="<? echo $humidityDesc; ?>"><span class="style1">Humidity</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $latestMM["outhum"].$humidUnit; ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $latestEE["outhum"].$humidUnit; ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourSB; ?>">
        <? echo $latestSB["RH"].$humidUnit; ?></td>
      <td rowspan="3" bgcolor="#426ecf" onclick="jQuery.facebox({ image: 'graphs/HumidityToday.php' })" class="cellLink"><a href="#" class="cellLink">
      <div style="position:relative; top:25%;"><img width="40" title="Humidity Graph" src="sources/Piebar_icon.gif" style="cursor:hand"></div></a></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Min</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo round($minhumMM,2)." ".$humidUnit." @ ".substr($minhumtimeMM,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo round($minhumEE,2)." ".$humidUnit." @ ".substr($minhumtimeEE,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourSB; ?>">
        <? echo round($minhumSB,2)." ".$humidUnit." @ ".substr($minhumtimeSB,0,5); ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo round($maxhumMM,2)." ".$humidUnit." @ ".substr($maxhumtimeMM,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo round($maxhumEE,2)." ".$humidUnit." @ ".substr($maxhumtimeEE,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourSB; ?>">
        <? echo round($maxhumSB,2)." ".$humidUnit." @ ".substr($maxhumtimeSB,0,5); ?></td>
    </tr>
    <tr>
      <th rowspan="3" bgcolor="#426ecf" scope="row" title="<? echo $rainDesc; ?>"><span class="style1">Rain</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Total</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo ((round($rainMM,2)!=0)?(round($rainMM,2)." ".$rainUnit):("No rain measured for today.")); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo ((round($rainEE,2)!=0)?(round($rainEE,2)." ".$rainUnit):("No rain measured for today.")); ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur Rate</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo (($latestMM["rainrate"]!=0)?($latestMM["rainrate"]." ".$rainRateUnit):("Currently not raining.")); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo (($latestEE["rainrate"]!=0)?($latestEE["rainrate"]." ".$rainRateUnit):("Currently not raining.")); ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max Rate</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo ((round($maxrainrateMM,2)!=0)?round($maxrainrateMM,2)." ".$rainRateUnit." @ ".substr($maxrainratetimeMM,0,5):"It did not rain today."); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo ((round($maxrainrateEE,2)!=0)?round($maxrainrateEE,2)." ".$rainRateUnit." @ ".substr($maxrainratetimeEE,0,5):"It did not rain today."); ?></td>
    </tr>
    <tr>
      <th colspan="2" bgcolor="#426ecf" scope="row" title="<? echo $etDesc; ?>"><span class="style1">Evapotranspiration</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo ((round($etMM,2)!=0)?(round($etMM,2)." ".$rainUnit):("No rain measured for today.")); ?></td>
      <td></td>
    </tr>
    
    
    <tr>
      <td colspan="4"></td>
    </tr>
    
    
    <tr>
      <th rowspan="4" bgcolor="#426ecf" scope="row" title="<? echo $barometerDesc; ?>"><span class="style1">Barometer</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $latestMM["bar"].$baroUnit; ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $latestEE["bar"].$baroUnit; ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourSB; ?>">
        <? echo $latestSB["BP_mB_Avg"].$baroUnit; ?></td>
      <td rowspan="3" bgcolor="#426ecf" onclick="jQuery.facebox({ image: 'graphs/BarometerToday.php' })" class="cellLink"><a href="#" class="cellLink">
      <div style="position:relative; top:25%;"><img width="40" title="Barometer Graph" src="sources/Piebar_icon.gif" style="cursor:hand"></div></a></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Min</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $minbarMM.$baroUnit." @ ".substr($minbartimeMM,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $minbarEE.$baroUnit." @ ".substr($minbartimeEE,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourSB; ?>">
        <? echo $minbarSB.$baroUnit." @ ".substr($minbartimeSB,0,5); ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $maxbarMM.$baroUnit." @ ".substr($maxbartimeMM,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $maxbarEE.$baroUnit." @ ".substr($maxbartimeEE,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourSB; ?>">
        <? echo $maxbarSB.$baroUnit." @ ".substr($maxbartimeSB,0,5); ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Trend</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $baroTrendMM; ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $baroTrendEE; ?></td>
      <td><!--Sonbesie--></td>
    </tr>
    
    
    <tr>
      <td colspan="4"></td>
    </tr>
    
    
    <tr>
      <th rowspan="3" bgcolor="#426ecf" scope="row" title="<? echo $heatIndexDesc; ?>"><span class="style1">Heat index</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#DEEFFF" style="color: <? echo $fontColourMM; ?>">
        <? echo $latestMM["heatindex"].$temperatureUnit; ?></td>
      <td bgcolor="#DEEFFF" style="color: <? echo $fontColourEE; ?>">
        <? echo $latestEE["heatindex"].$temperatureUnit; ?></td>
      <td></td>
      <td rowspan="3" bgcolor="#426ecf" onclick="jQuery.facebox({ image: 'graphs/HeatIndexToday.php' })" class="cellLink"><a href="#" class="cellLink">
      <div style="position:relative; top:25%;"><img width="40" title="Heat Index Graph" src="sources/Piebar_icon.gif" style="cursor:hand"></div></a></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Min</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $minHeatMM.$temperatureUnit." @ ".substr($minHeatTimeMM,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $minHeatEE.$temperatureUnit." @ ".substr($minHeatTimeEE,0,5); ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $maxHeatMM.$temperatureUnit." @ ".substr($maxHeatTimeMM,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $maxHeatEE.$temperatureUnit." @ ".substr($maxHeatTimeEE,0,5); ?></td>
    </tr>
    <tr>
      <th rowspan="3" bgcolor="#426ecf" scope="row" title="<? echo $thswDesc; ?>"><span class="style1">THW</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#DEEFFF" style="color: <? echo $fontColourMM; ?>">
        <? echo $latestMM["THW"].$temperatureUnit; ?></td>
      <td bgcolor="#DEEFFF" style="color: <? echo $fontColourEE; ?>">
        <? echo $latestEE["THWindex"].$temperatureUnit; ?></td>
      <td></td>
      <td rowspan="3" bgcolor="#426ecf" onclick="jQuery.facebox({ image: 'graphs/THWToday.php' })" class="cellLink"><a href="#" class="cellLink">
      <div style="position:relative; top:25%;"><img width="40" title="THW Graph" src="sources/Piebar_icon.gif" style="cursor:hand"></div></a></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Min</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $minthwMM.$temperatureUnit.' @ '.substr($minTHWtimeMM,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $minthwEE.$temperatureUnit.' @ '.substr($minTHWtimeEE,0,5); ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $maxthwMM.$temperatureUnit.' @ '.substr($maxTHWtimeMM,0,5); ?></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourEE; ?>">
        <? echo $maxthwEE.$temperatureUnit.' @ '.substr($maxTHWtimeEE,0,5); ?></td>
    </tr>
    <tr>
      <th rowspan="3" bgcolor="#426ecf" scope="row" title="<? echo $thswDesc; ?>"><span class="style1">THSW</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#DEEFFF" style="color: <? echo $fontColourMM; ?>">
        <? echo $latestMM["THSWindex"].$temperatureUnit; ?></td>
      <td></td>
      <td></td>
      <td rowspan="3" bgcolor="#426ecf" onclick="jQuery.facebox({ image: 'graphs/THSWToday.php' })" class="cellLink"><a href="#" class="cellLink">
      <div style="position:relative; top:25%;"><img width="40" title="THSW Graph" src="sources/Piebar_icon.gif" style="cursor:hand"></div></a></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Min</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $minthswMM.$temperatureUnit.' @ '.substr($minTHSWtimeMM,0,5); ?></td>
      <td></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo $maxthswMM.$temperatureUnit.' @ '.substr($maxTHSWtimeMM,0,5); ?></td>
      <td></td>
    </tr>
    
    
    <tr>
      <td colspan="4"></td>
    </tr>
    
    
    
    
    <tr>
      <th rowspan="2" bgcolor="#426ecf" scope="row" title="<? echo $solarRadDesc; ?>"><span class="style1">Solar radiation</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo (($latestMM["solarrad"]!=0)?"<div title=\"Watts per square meter\">".$latestMM["solarrad"]." Watt/m&sup2;</div>":"It is night."); ?></td>
      <td></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourSB; ?>">
        <? echo (($latestSB["SunWM_Avg"]!=0)?"<div title=\"Watts per square meter\">".$latestSB["SunWM_Avg"]." Watt/m&sup2;</div>":"It is night."); ?></td>
      <td rowspan="2" bgcolor="#426ecf" onclick="jQuery.facebox({ image: 'graphs/SolarradToday.php' })" class="cellLink"><a href="#" class="cellLink">
      <div style="position:relative; top:5%;"><img width="40" title="Solar Radiation Graph" src="sources/Piebar_icon.gif" style="cursor:hand"></div></a></td>
    </tr>
    
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo (($maxSolarradMM!=0)?"<div title=\"Watts per square meter\">".$maxSolarradMM." Watt/m&sup2; @ ".substr($maxSolarradTimeMM,0,5)."</div>":"The sun has not risen yet."); ?></td>
      <td></td>
      <td bgcolor="#deefff" style="color: <? echo $fontColourSB; ?>">
        <? echo (($maxSolarradSB!=0)?"<div title=\"Watts per square meter\">".round($maxSolarradSB)." Watt/m&sup2; @ ".substr($maxSolarradTimeSB,0,5)."</div>":"The sun has not risen yet."); ?></td>
    </tr>
    <tr>
      <th rowspan="2" bgcolor="#426ecf" scope="row" title="<? echo $langleyDesc; ?>"><span class="style1">Solar energy</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo (($latestMM["solarenergy"]!=0)?"<div title=\"Langley\">".$latestMM["solarenergy"]." Ly</div>":"It is night."); ?></td>
      <td></td>
      <td></td>
      <td rowspan="2" bgcolor="#426ecf" onclick="jQuery.facebox({ image: 'graphs/SolarenergyToday.php' })" class="cellLink"><a href="#" class="cellLink">
      <div style="position:relative; top:5%;"><img width="40" title="Solar Energy Graph" src="sources/Piebar_icon.gif" style="cursor:hand"></div></a></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo (($maxSolarEnergyMM!=0)?"<div title=\"Langley\">".$maxSolarEnergyMM." Ly @ ".substr($maxSolarEnergyTimeMM,0,5)."</div>":"The sun has not risen yet."); ?></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <th rowspan="2" bgcolor="#426ecf" scope="row" title="<? echo ($UVradiationDesc.$UVindexDesc); ?>" ><span class="style1">UV index</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo (($latestMM["UV"]!=0)?$latestMM["UV"]." units":"It is night."); ?></td>
      <td></td>
      <td></td>
      <td rowspan="2" bgcolor="#426ecf" onclick="jQuery.facebox({ image: 'graphs/UVindexToday.php' })" class="cellLink"><a href="#" class="cellLink">
      <div style="position:relative; top:5%;"><img width="40" title="UV index Graph" src="sources/Piebar_icon.gif" style="cursor:hand"></div></a></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo (($maxUVindexMM!=0)?$maxUVindexMM." units @ ".substr($maxUVindexTimeMM,0,5):"The sun has not risen yet."); ?></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <th rowspan="2" bgcolor="#426ecf" scope="row" title="<? echo $UVdoseDesc.$UVunitDesc; ?>"><span class="style1">UV dose</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo (($latestMM["UVdose"]!=0)?"<div title=\"Minimum Erythemal Dose\">".$latestMM["UVdose"]." MEDs</div>":"It is night."); ?></td>
      <td></td>
      <td></td>
      <td rowspan="2" bgcolor="#426ecf" onclick="jQuery.facebox({ image: 'graphs/UVdoseToday.php' })" class="cellLink"><a href="#" class="cellLink">
      <div style="position:relative; top:5%;"><img width="40" title="UV dose Graph" src="sources/Piebar_icon.gif" style="cursor:hand"></div></a></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff" style="color: <? echo $fontColourMM; ?>">
        <? echo (($maxUVdoseMM!=0)?"<div title=\"Minimum Erythemal Dose\">".$maxUVdoseMM." MEDs @ ".substr($maxUVdoseTimeMM,0,5)."</div>":"The sun has not risen yet."); ?></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </table>
<br />
<?
include ("adsense.php");
show_ip(false);
?>
</div>
</body>
</html>
