<?php
ini_set('display_errors', 1);
ini_set('html_errors', 1);
error_reporting(E_ERROR);

include("website-api/HomeAPIall.php"); #Die API vir die homepage.

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta content="JP Meijers" name="author" />
<meta http-equiv="Refresh" content="3600; url=http://weather.sun.ac.za/home.php" />
<title>Stellenbosch Weather - Home</title>

<!-- popup box -->
<script src="sources/jquery.js" type="text/javascript"></script>
<link href="sources/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="sources/facebox/facebox.js" type="text/javascript"></script>

<!-- refresh data -->
<script type="text/javascript">
function weather() {
	$.get('/website-api/HomeAPIparams.php?weer&'+(new Date()).getTime(), splitReplaceWeer);
}
function splitReplaceWeer(data) {
	var apart = data.split("<>");
	document.getElementById('wind').innerHTML = apart[0];
	document.getElementById('rain').innerHTML = apart[1];
	document.getElementById('humidity').innerHTML = apart[2];
	document.getElementById('barometer').innerHTML = apart[3];
	document.getElementById('trend').innerHTML = apart[4];
	document.getElementById('temperature').innerHTML = apart[5];
	document.getElementById('minTemp').innerHTML = apart[6];
	document.getElementById('maxTemp').innerHTML = apart[7];
	document.getElementById('update').innerHTML = apart[8];

	var currentTime = new Date()
  var hours = currentTime.getHours()
  var minutes = currentTime.getMinutes()

  if (minutes < 10)
  minutes = "0" + minutes

	document.getElementById('gentime').innerHTML = "Page refreshed in "+apart[9]+" seconds @ "+hours+":"+minutes;
}

function moonSun() {
	$.get('/website-api/HomeAPIparams.php?moonSun&'+(new Date()).getTime(), splitReplaceMoonSun);
}
function splitReplaceMoonSun(data) {
	var apart = data.split("-");
	document.getElementById('moonDist').innerHTML = apart[0];
	document.getElementById('moonAng').innerHTML = apart[1];
	document.getElementById('sunDist').innerHTML = apart[2];
	document.getElementById('sunAng').innerHTML = apart[3];
}

window.setInterval("moonSun()", 10000);
window.setInterval("weather()", 60000);
</script>

<!-- Style sheet -->
<style type="text/css">
<!--
body {
	background-color: #A8CBFF;
}
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	color: #000000;
	font-weight: normal;
}
a:link {
	color: #000000;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #000000;
}
a:hover {
	text-decoration: underline;
	color: #000000;
}
a:active {
	text-decoration: none;
	color: #000000;
}
.style3 {color: #FFFFFF; font-weight: bold; font-family: Arial, Helvetica, sans-serif; }
.style6 {font-size: 50px}
.style7 {
	color: #426ECF;
	font-size: 14px;
}
.style8 {
	color: #FF3333;
	font-size: 14px;
}
.style9 {
	color: #FFFFFF;
	font-weight: bold;
}
.style10 {font-size: 14px}
-->
</style>
</head>

<body>
<?php
if (isset($errorString) and $errorString!="")
{
  echo '<small><span id="error">'.$errorString.'</span></small><br />';
}
?>
<?php echo '<small><span id="gentime">Page generated in '.$time.' seconds</span></small>'; ?>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <th scope="col"><table width="100%" border="0" cellspacing="1" cellpadding="2">
      <tr bgcolor="#426ECF">
        <td colspan="2"><div align="left"><strong><a href="tableToday.php" target="mainFrame" title="Click to see more."><span class="style9">Current Weather</span></a></strong></div></td>

        <td width="40%" scope="col"><div align="left"><strong><a href="forecasts.php" target="mainFrame" title="Click to see more."><span class="style9">Forecast</span></a></strong></div></td>
      </tr>
      <tr>
        <td bgcolor="#DEEFFF"><div align="left"><strong>Date:</strong> <?php echo date("l"); ?>, <?php echo date("j"); ?> <?php echo date("F"); ?> <?php echo date("Y"); ?><br />
            <strong>Wind: </strong>
              <a href="http://weather.sun.ac.za/graphs/wind" target="mainFrame" title="Click for graphs.">
                <span id="wind"><?php echo $windString; ?></span></a><br />
            <strong>Rain: </strong><span id="rain"><?php echo $rainString; ?></span><br />
            <strong>Humidity: </strong>
              <a href="http://weather.sun.ac.za/graphs/humidity" target="mainFrame" title="Click for graphs.">
                <span id="humidity"><?php echo $humidString; ?></span></a><br />
            <strong>Barometer: </strong>
              <a href="http://weather.sun.ac.za/graphs/barometer" target="mainFrame" title="Click for graphs.">
                <span id="barometer"><?php echo $baroString; ?></span></a><br />
            <strong>Trend: </strong><?php
            echo "<small><span id=\"trend\" title=\"According to the Sager Weathercaster\"";
            if($quickforecast=="Forecast Unavailable") echo " style=\"color: gray\"";
            echo ">".$quickforecast;
            ?></span></small></div></td>
        <!--<td width="20%" bgcolor="#DEEFFF" title="Click to view the graph." onclick="jQuery.facebox({ image: 'graphs/Temperature24h.php' })">-->
        <td width="20%" bgcolor="#DEEFFF" title="Click to view the graph." onclick="jQuery.facebox({ image: 'graphs/temperature/24hours.php' })">
            <a href="#" style="text-decoration:none;">
	    <div align="center">Outside Temperature:<br />
            <span class="style6"><span id="temperature"><?php echo $temperString; ?></span></span><br />
            <span class="style7">Min: <span id="minTemp"><?php echo $minTemperString; ?></span></span><br />
            <span class="style8">Max: <span id="maxTemp"><?php echo $maxTemperString; ?></span></span>
	</div></a></td>
    <th width="33%" bgcolor="#DEEFFF">
	    <table width="100%" border="0" cellspacing="2" cellpadding="3">
          <tr>
            <td scope="col">
        		<a href="forecast-table.php">Click here for forecast table.</a>
            </td>
          </tr>
        </table>

      </th>
      </tr>
      <tr bgcolor="#426ECF">
        <td colspan="2" bgcolor="#DEEFFF">
	    <span class="style10">
		<div align="left">
		    Last update: <span id="update"><?php echo $updateTimeString; ?></span>
		</div>
	    </span>
	</td>
        <td width="33%" bgcolor="#DEEFFF">
	    <span class="style10">
    <div align="left">
    <?php if(!isset($error)){
      // echo 'Last Update:'. $forecastUpdateTime/*Time*/.' from <a href="http://www.wunderground.com/" target="_blank">Wunderground.com</a>';
      echo 'Forecast by met.no';
    }
    ?>
        
    </div>
	    </span>
	</td>
      </tr>
    </table>
    &nbsp;</th>
  </tr>
  <tr>
    <th scope="row"><table width="100%" border="0" cellspacing="1" cellpadding="2">
      <tr>
        <td width="33%" bgcolor="#426ECF" scope="col"><div align="left"><span class="style3">Moon</span></div></td>
        <td width="0" bgcolor="#426ECF" scope="col"><div align="left"><span class="style3">Stars</span></div></td>
        <td width="50%" bgcolor="#426ECF" scope="col"><div align="left"><span class="style3">Sun</span></div></td>
      </tr>
      <tr>
        <td width="50%" bgcolor="#DEEFFF" scope="row"><div align="left">
          <strong>&nbsp;<?php echo $moonPhase; ?></strong><br />
          <strong>&nbsp;Illuminated:</strong>&nbsp;<?php echo $moonPerc; ?>%, &nbsp;<strong>Age:</strong>&nbsp;<?php echo $moonAge; ?>&nbsp;days<br />
          <strong>&nbsp;Distance:</strong>&nbsp;<span id="moonDist"><?php echo $moonDist; ?></span>&nbsp;km <br />
          <strong>&nbsp;Angular&nbsp;diameter:</strong>&nbsp;<span id="moonAng"><?php echo $moonAng; ?></span>&deg;
          </div></td>
        <th width="0%" bgcolor="#DEEFFF"><?php echo $zodiacSign." - <i>".$zodiacDesc."</i><br /><br />&nbsp;".$zodiacDate."&nbsp;"; ?></th>
        <td width="33%" bgcolor="#DEEFFF"><div align="left">
          <strong>&nbsp;Sunrise:</strong> <?php echo $sunrise; ?><br />
          <strong>&nbsp;Sunset:</strong> <?php echo $sunset; ?><br />
          <strong>&nbsp;Distance:</strong> <span id="sunDist"><?php echo $sunDist; ?></span>&nbsp;km <br />
          <strong>&nbsp;Angular diameter:</strong> <span id="sunAng"><?php echo $sunAng; ?></span>&deg; </div></td>
      </tr>
      <tr>
        <th width="33%" bgcolor="#000000" scope="row">
	    <div align="center">
		<a href="http://www.die.net/moon/" target="_blank">
		    <img src="sources/moon400.jpg" alt="The <?php echo $moonPhase; ?> of the moon. &#xA;Click for a bigger image." title="The <?php echo $moonPhase; ?> of the moon. &#xA;Click for a bigger image." height="150" width="150" border="0" />
		</a>
	    </div>
	</th>
	<th width="0%" bgcolor="#33009A">
	    <img src="sources/zodiac/<?php echo $zodiacSign; ?>_constellation.png" alt="Wikimedia Commons - Zodiac" title="<?php echo $zodiacDesc; ?>" height="150" longdesc="http://commons.wikimedia.org/wiki/File:Zodiac_woodcut.png" />
	</th>
	<th bgcolor="#000000">
	    <div align="center">
		<a href="http://www.die.net/earth/" target="_blank">
		    <img src="sources/earth400.jpg" alt="Earth with live cloud cover. &#xA;Click for a bigger image." title="Earth with live cloud cover. &#xA;Click for a bigger image." height="150" border="0" />
		</a>
	    </div>
	</th>
        </tr>
    </table>
    &nbsp;</th>
  </tr>
  <tr>
    <th scope="row"><table width="100%" border="0" cellspacing="1" cellpadding="2">
      <tr>
        <td bgcolor="#426ECF" class="style3" scope="col"><div align="left">Tides</div></td>
      </tr>
      <tr>
        <td bgcolor="#DEEFFF" scope="row"><div align="left">Visit <a href="http://www.satides.co.za/" target="_blank">http://www.satides.co.za/</a> for information on ocean tides.</div></td>
      </tr>
    </table></th>
  </tr>
</table>
</body>

<footer>
<!--<span style="text-align: left; font-size: 10px;">&nbsp;* The rain measurement is done by a digital sensor that can get clogged easily.</span><br />-->
<br />
<center>
<i>"The trouble with weather forecasting is that it's right too often for us to ignore it and wrong too often for us to rely on it."</i> - Patrick Young
<br />
<br />
<?
//include ("adsense.php");
//show_ip(true);
?>
</center>
</footer>

</html>
