<?php 
include("website-api/HomeAPIall.php"); #Die API vir die homepage.
?>
  
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
  <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
  <meta name="verify-v1" content="Gttw+xMz4t8wDmPbr9UP93eyfzVzp4EBZ5o3i5KCrr4=" />
  <meta content="JP Meijers" name="author" />
  <meta http-equiv="Refresh" content="300; url=http://weather.sun.ac.za/mobile" />
  <title>Mobile Weather</title>
  <style type="text/css">
<!--
.style7 {
	font-size: x-large;
	font-weight: bold;
}
.style8 {font-size: small}
.style10 {font-size: large}
-->
  </style>
</head>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-103781011-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-103781011-1');
</script>

<body style="color: rgb(0, 0, 0); background-color: rgb(252, 243, 209);" alink="#000099" link="#000099" vlink="#990099">
  <font face="Arial, Helvetica, sans-serif" class="style7">Stellenbosch Weather</font>  <br />
  <font face="Arial, Helvetica, sans-serif"><a href="http://weather.sun.ac.za/full.php" class="style8">Go to main website </a><br />
<span class="style10"><br />
<strong>Current Weather</strong></span><br />
<span class="style8">Last update: <?php echo $updateDate." at ".$updateTime; ?></span><br />
<strong>Temperature:</strong> <?php echo $temperature.$temperatureUnit ?><br />
<strong>Wind:</strong> <?php echo $windString; ?><br />
<strong>Rain:</strong> <?php echo $rainString; ?><br />
<strong>Humidity:</strong> <?php echo $humidity.$humidUnit; ?><br />
<strong>Barometer:</strong> <?php echo $baroString; ?><br />
<strong>Trend:</strong> <?php echo $quickforecast; ?><br />
<a href="current.php" class="style8">Click for more</a><br />
<br>


<span class="style10"><strong>Expected Weather</strong></span><br />
<font class="style8">Last Update: <?php echo $timeHour[0][0].":".$timeMin[0][0];/*Time*/?></font><br />

<?php /*Today's prediction*/
echo date("l", mktime(0, 0, 0, trim($dateMonth[0][0]), trim($dateDay[0][0]), trim($dateYear[0][0]))).", ".$dateDay[0][0]." ".$dateMonthText[0][0]." ".$dateYear[0][0]."<br>
"."Max: ".$predMaxTemp[0][0]."&deg;C, Min: ".$predMinTemp[0][0]."&deg;C. ".$conditions[0][0].".";?>
<br />
<img title="<?php echo $icon[0][0];/*Today's icon title*/?>;;" style="width: 50px; height: 50px;" alt="Weather Icon - <?php echo $icon[0][0];/*Today's icon alternative text*/?>;;" src="sources/images/set1/<?php echo $icon[0][0];/*Today%27s%20icon%20location*/?>.gif" hspace="40" /><br />

<?php /*Tomorrow's prediction*/
echo date("l", mktime(0, 0, 0, trim($dateMonth[1][0]), trim($dateDay[1][0]), trim($dateYear[1][0]))).", ".$dateDay[1][0]." ".$dateMonthText[1][0]." ".$dateYear[1][0]."<br>
"."Max: ".$predMaxTemp[1][0]."&deg;C, Min: ".$predMinTemp[1][0]."&deg;C. ".$conditions[1][0].".";?>
<br />
<img title="<?php echo $icon[1][0];/*Tomorrow's icon title*/?>;;" style="width: 50px; height: 50px;" alt="Weather Icon - <?php echo $icon[1][0];/*Tomorrow's icon alternative text*/?>;" src="sources/images/set1/<?php echo $icon[1][0];/*Tomorrow%27s%20icon%20location*/?>.gif" hspace="40" /><br />

<a href="forecasts.php" class="style8">Click for more</a><font size="-1"><br />
</font><span class="style10"><strong><br />
Moon phase</strong></span><br />
Illuminated: 
<?php echo $moonPerc; ?>%<br />
Age of moon: 
<?php echo $moonAge; ?> days<br />
<?php echo $moonPhase; ?>
<br />
<br />
Sunrise: 
<?php echo $sunrise; ?><br />
Sunset: 
<?php echo $sunset; ?><br />
<br />
</font>

<?php 
//include ("sources/googleMobileAd.php");
?>
</body>
</html>
