<?php

# Bereken maan se naam
function moonphase ($mdaysd,$mpct) {
  // Definitions from http://www.answers.com/topic/lunar-phase
  //  * Dark Moon - Not visible
  //  * New Moon - Not visible, or traditionally, the first visible crescent of the Moon
  //  * Waxing Crescent Moon - Right 1-49% visible
  //  * First Quarter Moon - Right 50% visible
  //  * Waxing gibbous Moon - Right 51-99% visible
  //  * Full Moon - Fully visible
  //  * Waning gibbous Moon - Left 51-99% visible
  //  * Third Quarter Moon - Left 50% visible
  //  * Waning Crescent Moon - Left 1-49% visible
  //  * New Moon - Not visible

  if ($mdaysd <= 29.53/2) { // increasing illumination
    $ph = "Waxing";
	$qtr = "First";
  } else { // decreasing illumination
    $ph = "Waning";
	$qtr = "Last";
  }
  if ($mpct < 1 ) { return("Dark Moon"); }
  if ($mpct == 1 ) { return("New Moon"); }
  if ($mpct <= 49) { return("$ph Crescent"); }
  if ($mpct < 51) { return("$qtr Quarter"); }
  if ($mpct < 99) { return("$ph Gibbous"); }
	return("Full Moon");
}


$nowTime = date("H:i");

# bereken son se stand
$sunrise = date_sunrise(time(), SUNFUNCS_RET_STRING, -33.928, 18.866, 90+50/60, 2);
$sunset = date_sunset(time(), SUNFUNCS_RET_STRING, -33.928, 18.866, 90+50/60, 2);
$sunDist = number_format($SunDist, 0, '.', '\'');
$sunAng = number_format($SunAng, 5, '.', '\'');

if ($nowTime > $sunset or $nowTime < $sunrise) {
  $isNight=1;
  $icon[0] = "nt_".$icon[0];
}
else
{
  $isNight=0;
}

# bereken maan se stand
$moonPerc = number_format($MoonPhase*100, 1, '.', '');
$moonAge = number_format($MoonAge, 0, '.', '');
$moonDist = number_format($MoonDist, 0, '.', '\'');
$moonAng = number_format($MoonAng, 5, '.', '');
$moonPhase = moonphase($moonAge,$moonPerc);
?>
