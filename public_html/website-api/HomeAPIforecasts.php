<?php

# Kyk of internet af is
if (file_exists("/home/weather/public_html/sources/internet_down"))
{
	$errorString = "<small>Error updating forecasts. </small>".$errorString;
}


# parse weervoorspellings vanaf wunderground xml file
function handleError($errno, $errstr, $errfile, $errline, array $errcontext)
{
    if (0 === error_reporting()) {
        return false;
    }
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler('handleError');

// try {
	
	$string = file_get_contents("/home/weather/public_html/sources/weather.com_forecast.json");
	$json_data = json_decode($string, true);

	//$timeMin = date('i', $forecast_time);

	// $predTimeH = $xml->xpath("/response/forecast/simpleforecast/forecastdays/forecastday/date/hour");
	// $predTimeM = $xml->xpath("/response/forecast/simpleforecast/forecastdays/forecastday/date/min");
	$dayName = $json_data["dayOfWeek"];
	$forecastDate = $json_data["validTimeLocal"];

	$predMaxTemp = $json_data["calendarDayTemperatureMax"];
	$predMinTemp = $json_data["calendarDayTemperatureMin"];
	$conditions = $json_data["narrative"];
	$icon = ["",""];
// }
// catch (Exception $e){
// 	$errorString = "<small>Unrecoverable error with forecasts. </small>".$errorString;
// 	$error = "pred";
// 	$timeHour = array("--","--");
// 	$timeMin = array("--","--");

// 	$predTimeH = array("--","--");
// 	$predTimeM = array("--","--");
// 	$dayName = array("--","--");
// 	$dateDay = array("0","0");
// 	$dateMonth = array("0","0");
// 	$dateMonthText = array("--","--");
// 	$dateYear = array("0","0");

// 	$predMaxTemp = array("--","--");
// 	$predMinTemp = array("--","--");
// 	$conditions = array("--","--");
// 	$icon = array(array("error"),array("error"));
// }

?>
