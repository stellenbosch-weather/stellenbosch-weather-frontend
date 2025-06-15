<?php

# kies korrekte Zodiac sterreteken en datums
$curDate = (int)(HomeAPIzodiac . phpdate("m") . date("d"));
if ($curDate>=321 and $curDate<=419){
	$zodiacSign = "Aries";
	$zodiacDesc = "The Ram";
	$zodiacDate = "21&nbsp;March&nbsp;- 19&nbsp;April";
}
elseif ($curDate>=420 and $curDate<=520){
	$zodiacSign = "Taurus";
	$zodiacDesc = "The Bull";
	$zodiacDate = "20&nbsp;April&nbsp;- 20&nbsp;May";
}
elseif ($curDate>=521 and $curDate<=620){
	$zodiacSign = "Gemini";
	$zodiacDesc = "The Twins";
	$zodiacDate = "21&nbsp;May&nbsp;- 20&nbsp;June";
}
elseif ($curDate>=621 and $curDate<=722){
	$zodiacSign = "Cancer";
	$zodiacDesc = "The Crab";
	$zodiacDate = "21&nbsp;June&nbsp;- 22&nbsp;July";
}
elseif ($curDate>=723 and $curDate<=822){
	$zodiacSign = "Leo";
	$zodiacDesc = "The Lion";
	$zodiacDate = "23&nbsp;July - 22&nbsp;August";
}
elseif ($curDate>=823 and $curDate<=922){
	$zodiacSign = "Virgo";
	$zodiacDesc = "The Virgin";
	$zodiacDate = "23&nbsp;August&nbsp;- 22&nbsp;September";
}
elseif ($curDate>=923 and $curDate<=1022){
	$zodiacSign = "Libra";
	$zodiacDesc = "The Scales";
	$zodiacDate = "23&nbsp;September&nbsp;- 22&nbsp;October";
}
elseif ($curDate>=1023 and $curDate<=1121){
	$zodiacSign = "Scorpio";
	$zodiacDesc = "The Scorpion";
	$zodiacDate = "23&nbsp;October&nbsp;- 21&nbsp;November";
}
elseif ($curDate>=1122 and $curDate<=1221){
	$zodiacSign = "Sagittarius";
	$zodiacDesc = "Centaur The Archer";
	$zodiacDate = "22&nbsp;November&nbsp;- 21&nbsp;December";
}
elseif ($curDate>=1222 or $curDate<=119){ # Hierdie een moet OR, anders val dit altyd deur na unknown
	$zodiacSign = "Capricorn";
	$zodiacDesc = "Goat-horned (The&nbsp;Sea-Goat)";
	$zodiacDate = "22&nbsp;December&nbsp;- 19&nbsp;January";
}
elseif ($curDate>=120 and $curDate<=218){
	$zodiacSign = "Aquarius";
	$zodiacDesc = "The Water Bearer";
	$zodiacDate = "20&nbsp;January&nbsp;- 18&nbsp;February";
}
elseif ($curDate>=219 and $curDate<=320){
	$zodiacSign = "Pisces";
	$zodiacDesc = "Fish";
	$zodiacDate = "19&nbsp;February&nbsp;- 20&nbsp;March";
}
else {
	$zodiacSign = "unknown";
	$zodiacDesc = "Unknown Zodiac sign";
	$zodiacDate = "";
}

?>
