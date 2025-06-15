<?php

$stationDefinition = array(
"EE" =>
  array(
    "stationName"=>"Vantage Pro E&E",
    "tableName"=>"WeatherEE",
    "dateField"=>"date",
    "timeField"=>"time",
    "temperature"=>"tempout",
    "humidity"=>"outhum",
    "barometer"=>"bar",
    "windspeed"=>"windspeed",
    "winddirec"=>"direction",
    "hiwindspeed"=>"hispeed",
    "hiwinddirec"=>"hidir",
    "rain"=>"rain"
    ),
"MM" =>
  array(
    "stationName"=>"Vantage Pro M&M",
    "tableName"=>"WeatherMM",
    "dateField"=>"date",
    "timeField"=>"time",
    "temperature"=>"tempout",
    "humidity"=>"outhum",
    "barometer"=>"bar",
    "windspeed"=>"windspeed",
    "winddirec"=>"direction",
    "windspeed"=>"hispeed",
    "winddirec"=>"hidir",
    "rain"=>"rain"
    ),
"SB" =>
  array(
    "stationName"=>"Sonbesie",
    "tableName"=>"SB_TMin",
    "dateField"=>"Date",
    "timeField"=>"Time",
    "temperature"=>"AirTC_Avg",
    "humidity"=>"RH",
    "barometer"=>"BP_mB_Avg",
    "windspeed"=>"WS_ms_S_WVT",
    "winddirec"=>"WindDir_D1_WVT",
    "windspeed"=>"WS_ms_S_WVT",
    "winddirec"=>"WindDir_D1_WVT"
    )
);


function getListOfStations()
{
  //this is the list of possible weather stations this class can handle
  global $stationDefinition;
  return array_keys($stationDefinition);
}

function getListOfStationsWith($sensor)
{
  //this is the list of possible weather stations this class can handle
  global $stationDefinition;
  $stations = array();
  foreach (array_keys($stationDefinition) as $key)
  {
    $station = $stationDefinition[$key];
    if(isset($station[$sensor]))
    {
      $stations[] = $key;
    }
  }
  return $stations;
}

function getStationName($station)
{
  global $stationDefinition;
  $value = $stationDefinition[$station]["stationName"];
  if($value==NULL) return False;
  else return $value;
}

function getStationTableName($station)
{
  global $stationDefinition;
  $value = $stationDefinition[$station]["tableName"];
  if($value==NULL) return False;
  else return $value;
}

function getStationDateField($station)
{
  global $stationDefinition;
  $value = $stationDefinition[$station]["dateField"];
  if($value==NULL) return False;
  else return $value;
}

function getStationTimeField($station)
{
  global $stationDefinition;
  $value = $stationDefinition[$station]["timeField"];
  if($value==NULL) return False;
  else return $value;
}

function getStationTemperatureField($station)
{
  global $stationDefinition;
  $value = $stationDefinition[$station]["temperature"];
  if($value==NULL) return False;
  else return $value;
}

function getStationHumidityField($station)
{
  global $stationDefinition;
  $value = $stationDefinition[$station]["humidity"];
  if($value==NULL) return False;
  else return $value;
}

function getStationBarometerField($station)
{
  global $stationDefinition;
  $value = $stationDefinition[$station]["barometer"];
  if($value==NULL) return False;
  else return $value;
}

function getStationRainField($station)
{
  global $stationDefinition;
  $value = $stationDefinition[$station]["rain"];
  if($value==NULL) return False;
  else return $value;
}

?>
