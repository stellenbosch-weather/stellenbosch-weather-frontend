<?php
/*
$windString
$rainString
$humidity.$humidUnit
$barometer.$baroUnit.", ".$baroTrend
$quickforecast
$temperature.$temperatureUnit
$minTemp.$temperatureUnit." at ".$minTempTime
$maxTemp.$temperatureUnit." at ".$maxTempTime
$updateDate." at ".$updateTime." from ".$weerstasie

tag = moonSun
-$moonPhase
-$moonPerc
-$moonAge
$moonDist
$moonAng

-$zodiacSign
-$zodiacDesc
-$zodiacDate
-$sunrise
-$sunset
$sunDist
$sunAng


*/
include_once ("/home/weather/public_html/sources/maan.php");



if (isset($_GET['moonSun'])){
	# Laat moon.php sy werk doen. Dit moet heel bo aan die script gedoen word.
	list($MoonPhase, $MoonAge, $MoonDist, $MoonAng, $SunDist, $SunAng, $mpfrac) = Moon::phase(date("Y"), date("m"), date("d"), date("G"), date("i"), date("s"));
	ini_set(precision, "14"); # Maak weer floats reg sodat round() weer werk.

	# bereken maan se stand
	$moonDist = number_format($MoonDist, 0, '.', '\'');
	$moonAng = number_format($MoonAng, 5, '.', '');

	# bereken son se stand
	$sunDist = number_format($SunDist, 0, '.', '\'');
	$sunAng = number_format($SunAng, 5, '.', '\'');

	echo $moonDist."-".$moonAng."-".$sunDist."-".$sunAng;

}

else if (isset($_GET['weer'])){
  include("HomeAPIall.php");

	// echo out the correct string
	echo $windString."<>".$rainString."<>".$humidString."<>".$baroString."<>".$quickforecast."<>".$temperString."<>".$minTemperString."<>".$maxTemperString."<>".$updateTimeString."<>".$time;

}

?>
