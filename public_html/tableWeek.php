<?php 
include 'website-api/TableWeekAPIall.php';
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

if ($updateDateMM == $today){ $updateDateMM = "Today";}
elseif ($updateDateMM == $yesterday){ $updateDateMM = "Yesterday";}
if ($updateDateEE == $today){ $updateDateEE = "Today";}
elseif ($updateDateEE == $yesterday){ $updateDateEE = "Yesterday";}

if ($latestMM["windspeed"] != "0"){ $windMM = $latestMM["windspeed"]." ".$windUnit.", ".$latestMM["direction"]; }
else{ $windMM = "Calm"; }

if ($latestEE["windspeed"] != "0"){ $windEE = $latestEE["windspeed"]." ".$windUnit.", ".$latestEE["direction"]; }
else{ $windEE = "Calm"; }

if ($avgWind10MM != "0"){ $avgWind10MM = $avgWind10MM." ".$windUnit.", ".$avgDir10MM; }
else{ $avgWind10MM = "Calm"; }

if ($avgWind10EE != "0"){ $avgWind10EE = $avgWind10EE." ".$windUnit.", ".$avgDir10EE; }
else{ $avgWind10EE = "Calm"; }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Statistics for the past 7 days</title>
<script src="sources/jquery.js" type="text/javascript"></script>
<link href="sources/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="sources/facebox/facebox.js" type="text/javascript"></script>
<!--<script type="text/javascript"> 
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
        loading_image : 'loading.gif',
        close_image   : 'closelabel.gif'
      }) 
    })
</script>-->
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
-->
</style></head>

<body>
<div align="center">
  <h1>Statistics for the past 7 days</h1>
  <table width="" border="0" cellspacing="2" cellpadding="2">

<!-- General Info -->
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th bgcolor="#426ecf" scope="col"><span class="style1">Mechanical &amp; Mechatronic</span></th>
      <th bgcolor="#426ecf" scope="col"><span class="style1">Electrical &amp; Electronic</span></th>
    </tr>
    <tr>
      <th colspan="2" bgcolor="#426ecf" scope="col"><span class="style1">Last update </span></th>
      <th bgcolor="#deefff" scope="col"><? echo $updateDateMM." at ".$updateTimeMM; ?></th>
      <th bgcolor="#deefff" scope="col"><? echo $updateDateEE." at ".$updateTimeEE; ?></th>
    </tr>
    <tr>
      <th colspan="2" bgcolor="#426ECF" scope="row" title="Have a look on Google Earth"><span class="style1">Location</span></th>
      <td bgcolor="#DEEFFF">33&deg;55'43''S&nbsp;&nbsp;18&deg;51'55''E</td>
      <td bgcolor="#DEEFFF">33&deg;55'43''S&nbsp;&nbsp;18&deg;52'02''E</td>
    </tr>

    <tr>
      <td colspan="4"></td>
    </tr>

<!-- Temperature -->
    <tr>
      <th rowspan="3" bgcolor="#426ecf" scope="row" title="Temperature measured in degrees Celsius."><span class="style1">Temperature</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Avg</span></th>
      <td bgcolor="#deefff"><? echo $avgtempMM.$temperatureUnit; ?></td>
      <td bgcolor="#deefff"><? echo $avgtempEE.$temperatureUnit; ?></td>
      <td rowspan="3"><img width="40" title="Temperature Graph" src="sources/Piebar_icon.gif" style="cursor:hand" onclick="jQuery.facebox({ image: 'graphs/Temperature7days.php' })"></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff"><? echo $maxtempMM.$temperatureUnit." @ ".substr($maxtemptimeMM,0,5); ?></td>
      <td bgcolor="#deefff"><? echo $maxtempEE.$temperatureUnit." @ ".substr($maxtemptimeEE,0,5); ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Min</span></th>
      <td bgcolor="#deefff"><? echo $mintempMM.$temperatureUnit." @ ".substr($mintemptimeMM,0,5); ?></td>
      <td bgcolor="#deefff"><? echo $mintempEE.$temperatureUnit." @ ".substr($mintemptimeEE,0,5); ?></td>
    </tr>

    <tr>
      <td colspan="4"></td>
    </tr>

<!-- Wind -->
    <tr>
      <th rowspan="3" bgcolor="#426ecf" scope="row"><span class="style1">Wind</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff"><? echo $windMM; ?></td>
      <td bgcolor="#deefff"><? echo $windEE; ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">10m Avg</span></th>
      <td bgcolor="#deefff"><? echo $avgWind10MM; ?></td>
      <td bgcolor="#deefff"><? echo $avgWind10EE; ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff"><? echo $maxWindMM." ".$windUnit.", ".$maxWindDirMM." @ ".substr($maxWindTimeMM,0,5); ?></td>
      <td bgcolor="#deefff"><? echo $maxWindEE." ".$windUnit.", ".$maxWindDirEE." @ ".substr($maxWindTimeEE,0,5); ?></td>
    </tr>
    <tr>
      <th rowspan="2" bgcolor="#426ecf" scope="row" title="<? echo $windChillDesc; ?>"><span class="style1">Wind chill </span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff"><? echo $latestMM["windchill"].$temperatureUnit; ?></td>
      <td bgcolor="#deefff"><? echo $latestEE["windchill"].$temperatureUnit; ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Min</span></th>
      <td bgcolor="#deefff"><? echo $minWindchillMM.$temperatureUnit." @ ".substr($minWindchillTimeMM,0,5); ?></td>
      <td bgcolor="#deefff"><? echo $minWindchillEE.$temperatureUnit." @ ".substr($minWindchillTimeEE,0,5); ?></td>
    </tr>
    <tr>
      <td colspan="4"></td>
    </tr>
    <tr>
      <th rowspan="3" bgcolor="#426ecf" scope="row" title="<? echo $dewPointDesc; ?>"><span class="style1">Dew Point </span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff"><? echo $latestMM["dew"].$temperatureUnit; ?></td>
      <td bgcolor="#deefff"><? echo $latestEE["dew"].$temperatureUnit; ?></td>
      <td rowspan="3"><img width="40" title="Dew Point Graph" src="sources/Piebar_icon.gif" style="cursor:hand" onclick="jQuery.facebox({ image: 'graphs/DewPointToday.php' })"></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Min</span></th>
      <td bgcolor="#deefff"><? echo $mindewMM.$temperatureUnit." @ ".substr($mindewtimeMM,0,5); ?></td>
      <td bgcolor="#deefff"><? echo $mindewEE.$temperatureUnit." @ ".substr($mindewtimeEE,0,5); ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff"><? echo $maxdewMM.$temperatureUnit." @ ".substr($maxdewtimeMM,0,5); ?></td>
      <td bgcolor="#deefff"><? echo $maxdewEE.$temperatureUnit." @ ".substr($maxdewtimeEE,0,5); ?></td>
    </tr>
    <tr>
      <th rowspan="3" bgcolor="#426ecf" scope="row" title="<? echo $humidityDesc; ?>"><span class="style1">Humidity</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff"><? echo $latestMM["outhum"].$humidUnit; ?></td>
      <td bgcolor="#deefff"><? echo $latestEE["outhum"].$humidUnit; ?></td>
      <td rowspan="3"><img width="40" title="Humidity Graph" src="sources/Piebar_icon.gif" style="cursor:hand" onclick="jQuery.facebox({ image: 'graphs/HumidityToday.php' })"></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Min</span></th>
      <td bgcolor="#deefff"><? echo round($minhumMM,2)." ".$humidUnit." @ ".substr($minhumtimeMM,0,5); ?></td>
      <td bgcolor="#deefff"><? echo round($minhumEE,2)." ".$humidUnit." @ ".substr($minhumtimeEE,0,5); ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff"><? echo round($maxhumMM,2)." ".$humidUnit." @ ".substr($maxhumtimeMM,0,5); ?></td>
      <td bgcolor="#deefff"><? echo round($maxhumEE,2)." ".$humidUnit." @ ".substr($maxhumtimeEE,0,5); ?></td>
    </tr>
    <tr>
      <th rowspan="3" bgcolor="#426ecf" scope="row" title="<? echo $rainDesc; ?>"><span class="style1">Rain</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Total</span></th>
      <td bgcolor="#deefff"><? echo ((round($rainMM,2)!=0)?(round($rainMM,2)." ".$rainUnit):("No rain measured for today.")); ?></td>
      <td bgcolor="#deefff"><? echo ((round($rainEE,2)!=0)?(round($rainEE,2)." ".$rainUnit):("No rain measured for today.")); ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur Rate</span></th>
      <td bgcolor="#deefff"><? echo (($latestMM["rainrate"]!=0)?($latestMM["rainrate"]." ".$rainRateUnit):("Currently not raining.")); ?></td>
      <td bgcolor="#deefff"><? echo (($latestEE["rainrate"]!=0)?($latestEE["rainrate"]." ".$rainRateUnit):("Currently not raining.")); ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max Rate</span></th>
      <td bgcolor="#deefff"><? echo ((round($maxrainrateMM,2)!=0)?round($maxrainrateMM,2)." ".$rainRateUnit." @ ".substr($maxrainratetimeMM,0,5):"It did not rain today."); ?></td>
      <td bgcolor="#deefff"><? echo ((round($maxrainrateEE,2)!=0)?round($maxrainrateEE,2)." ".$rainRateUnit." @ ".substr($maxrainratetimeEE,0,5):"It did not rain today."); ?></td>
    </tr>
    <tr>
      <th colspan="2" bgcolor="#426ecf" scope="row" title="<? echo $etDesc; ?>"><span class="style1">Evapotranspiration</span></th>
      <td bgcolor="#deefff"><? echo ((round($etMM,2)!=0)?(round($etMM,2)." ".$rainUnit):("No rain measured for today.")); ?></td>
      <td></td>
    </tr>
    <tr>
      <td colspan="4"></td>
    </tr>
    <tr>
      <th rowspan="4" bgcolor="#426ecf" scope="row" title="<? echo $barometerDesc; ?>"><span class="style1">Barometer</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff"><? echo $latestMM["bar"].$baroUnit; ?></td>
      <td bgcolor="#deefff"><? echo $latestEE["bar"].$baroUnit; ?></td>
      <td rowspan="4"><img width="40" title="Barometer Graph" src="sources/Piebar_icon.gif" style="cursor:hand" onclick="jQuery.facebox({ image: 'graphs/BarometerToday.php' })"></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Min</span></th>
      <td bgcolor="#deefff"><? echo $minbarMM.$baroUnit." @ ".substr($minbartimeMM,0,5); ?></td>
      <td bgcolor="#deefff"><? echo $minbarEE.$baroUnit." @ ".substr($minbartimeEE,0,5); ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff"><? echo $maxbarMM.$baroUnit." @ ".substr($maxbartimeMM,0,5); ?></td>
      <td bgcolor="#deefff"><? echo $maxbarEE.$baroUnit." @ ".substr($maxbartimeEE,0,5); ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Trend</span></th>
      <td bgcolor="#deefff"><? echo $baroTrendMM; ?></td>
      <td bgcolor="#deefff"><? echo $baroTrendEE; ?></td>
    </tr>
    <tr>
      <td colspan="4"></td>
    </tr>
    <tr>
      <th rowspan="3" bgcolor="#426ecf" scope="row" title="<? echo $heatIndexDesc; ?>"><span class="style1">Heat index</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#DEEFFF"><? echo $latestMM["heatindex"].$temperatureUnit; ?></td>
      <td bgcolor="#DEEFFF"><? echo $latestEE["heatindex"].$temperatureUnit; ?></td>
      <td rowspan="3"><img width="40" title="Heat Index Graph" src="sources/Piebar_icon.gif" style="cursor:hand" onclick="jQuery.facebox({ image: 'graphs/HeatIndexToday.php' })"></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Min</span></th>
      <td bgcolor="#deefff"><? echo $minHeatMM.$temperatureUnit." @ ".substr($minHeatTimeMM,0,5); ?></td>
      <td bgcolor="#deefff"><? echo $minHeatEE.$temperatureUnit." @ ".substr($minHeatTimeEE,0,5); ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff"><? echo $maxHeatMM.$temperatureUnit." @ ".substr($maxHeatTimeMM,0,5); ?></td>
      <td bgcolor="#deefff"><? echo $maxHeatEE.$temperatureUnit." @ ".substr($maxHeatTimeEE,0,5); ?></td>
    </tr>
    <tr>
      <th rowspan="3" bgcolor="#426ecf" scope="row" title="<? echo $thswDesc; ?>"><span class="style1">THW</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#DEEFFF"><? echo $latestMM["THW"].$temperatureUnit; ?></td>
      <td bgcolor="#DEEFFF"><? echo $latestEE["THWindex"].$temperatureUnit; ?></td>
      <td rowspan="3"><img width="40" title="THW Graph" src="sources/Piebar_icon.gif" style="cursor:hand" onclick="jQuery.facebox({ image: 'graphs/THWToday.php' })"></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Min</span></th>
      <td bgcolor="#deefff"><? echo $minthwMM.$temperatureUnit.' @ '.substr($minTHWtimeMM,0,5); ?></td>
      <td bgcolor="#deefff"><? echo $minthwEE.$temperatureUnit.' @ '.substr($minTHWtimeEE,0,5); ?></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff"><? echo $maxthwMM.$temperatureUnit.' @ '.substr($maxTHWtimeMM,0,5); ?></td>
      <td bgcolor="#deefff"><? echo $maxthwEE.$temperatureUnit.' @ '.substr($maxTHWtimeEE,0,5); ?></td>
    </tr>
    <tr>
      <th rowspan="3" bgcolor="#426ecf" scope="row" title="<? echo $thswDesc; ?>"><span class="style1">THSW</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#DEEFFF"><? echo $latestMM["THSWindex"].$temperatureUnit; ?></td>
      <!--<td></td>-->
      <td rowspan="3"><img width="40" title="THSW Graph" src="sources/Piebar_icon.gif" style="cursor:hand" onclick="jQuery.facebox({ image: 'graphs/THSWToday.php' })"></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Min</span></th>
      <td bgcolor="#deefff"><? echo $minthswMM.$temperatureUnit.' @ '.substr($minTHSWtimeMM,0,5); ?></td>
      <td></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff"><? echo $maxthswMM.$temperatureUnit.' @ '.substr($maxTHSWtimeMM,0,5); ?></td>
      <td></td>
    </tr>
    <tr>
      <td colspan="4"></td>
    </tr>
    <tr>
      <th rowspan="2" bgcolor="#426ecf" scope="row" title="<? echo $solarRadDesc; ?>"><span class="style1">Solar radiation</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff"><? echo (($latestMM["solarrad"]!=0)?"<div title=\"Watts per square meter\">".$latestMM["solarrad"]." Watt/m&sup2;</div>":"It is night."); ?></td>
      <!--<td></td>-->
      <td rowspan="2"><img width="40" title="Solar Radiation Graph" src="sources/Piebar_icon.gif" style="cursor:hand" onclick="jQuery.facebox({ image: 'graphs/SolarradToday.php' })"></td>
    </tr>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff"><? echo (($maxSolarradMM!=0)?"<div title=\"Watts per square meter\">".$maxSolarradMM." Watt/m&sup2; </div>@ ".substr($maxSolarradTimeMM,0,5):"The sun has not risen yet."); ?></td>
      <td></td>
    </tr>
    <tr>
      <th rowspan="2" bgcolor="#426ecf" scope="row" title="<? echo $langleyDesc; ?>"><span class="style1">Solar energy</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff"><? echo (($latestMM["solarenergy"]!=0)?"<div title=\"Langley\">".$latestMM["solarenergy"]." Ly</div>":"It is night."); ?></td>
      <!--<td></td>-->
      <td rowspan="2"><img width="40" title="Solar Energy Graph" src="sources/Piebar_icon.gif" style="cursor:hand" onclick="jQuery.facebox({ image: 'graphs/SolarenergyToday.php' })"></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff"><? echo (($maxSolarEnergyMM!=0)?"<div title=\"Langley\">".$maxSolarEnergyMM." Ly</div>@ ".substr($maxSolarEnergyTimeMM,0,5):"The sun has not risen yet."); ?></td>
      <td></td>
    </tr>
    <tr>
      <th rowspan="2" bgcolor="#426ecf" scope="row" title="<? echo ($UVradiationDesc.$UVindexDesc); ?>" ><span class="style1">UV index</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff"><? echo (($latestMM["UV"]!=0)?$latestMM["UV"]." units":"It is night."); ?></td>
      <!--<td></td>-->
      <td rowspan="2"><img width="40" title="UV index Graph" src="sources/Piebar_icon.gif" style="cursor:hand" onclick="jQuery.facebox({ image: 'graphs/UVindexToday.php' })"></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff"><? echo (($maxUVindexMM!=0)?$maxUVindexMM." units @ ".substr($maxUVindexTimeMM,0,5):"The sun has not risen yet."); ?></td>
      <td></td>
    </tr>
    <tr>
      <th rowspan="2" bgcolor="#426ecf" scope="row" title="<? echo $UVdoseDesc.$UVunitDesc; ?>"><span class="style1">UV dose</span></th>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Cur</span></th>
      <td bgcolor="#deefff"><? echo (($latestMM["UVdose"]!=0)?"<div title=\"Minimum Erythemal Dose\">".$latestMM["UVdose"]." MEDs</div>":"It is night."); ?></td>
      <!--<td></td>-->
      <td rowspan="2"><img width="40" title="UV dose Graph" src="sources/Piebar_icon.gif" style="cursor:hand" onclick="jQuery.facebox({ image: 'graphs/UVdoseToday.php' })"></td>
    </tr>
    <tr>
      <th bgcolor="#426ecf" scope="row"><span class="style1">Max</span></th>
      <td bgcolor="#deefff"><? echo (($maxUVdoseMM!=0)?"<div title=\"Minimum Erythemal Dose\">".$maxUVdoseMM." MEDs </div>@ ".substr($maxUVdoseTimeMM,0,5):"The sun has not risen yet."); ?></td>
      <td></td>
    </tr>
  </table>
<br />
<?
include ("adsense.php");
show_ad(false);
?>
</div>
</body>
<center>
</center>
</html>
