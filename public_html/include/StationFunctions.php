<? 

//new proper functions takes a database connection as parameter
function getFirstEntryDateTime($databaseConnection, $tableName, $dateTimeField, $usesUTC)
{
  $query = "SELECT MIN(".$dateTimeField.") FROM ".$tableName;
  $result = mysql_query($query, $databaseConnection);
  if($result==False) return Null;
  while($row = mysql_fetch_row($result))
  {
    $dateTime = $row[0];
    $timestamp = strtotime($dateTime);
    if(is_numeric($timestamp) and $timestamp!= -1)
    {
      if($usesUTC)
        return $timestamp+7200;
      else
        return $timestamp;
    }
    else return Null;
  }
  return Null;
}

function getLastEntryDateTime($databaseConnection, $tableName, $dateTimeField, $usesUTC)
{
  $query = "SELECT MAX(".$dateTimeField.") FROM ".$tableName;
  $result = mysql_query($query, $databaseConnection);
  if($result==False) return Null;
  while($row = mysql_fetch_row($result))
  {
    $dateTime = $row[0];
    $timestamp = strtotime($dateTime);
    if(is_numeric($timestamp) and $timestamp!= -1)
    {
      if($usesUTC)
        return $timestamp+7200;
      else
        return $timestamp;
    }
    else return Null;
  }
  return Null;
}
  
function getNowActiveStationCount($databaseConnection, $stationList, $stationDescriptions)
{
  global $databaseConnection;
  $count=0;
  foreach ($stationList as $station)
  {
    $tableName=$stationDescriptions::getTableName($station["ShortName"]);
    $dateTimeField=$stationDescriptions::getDateTimeField($station["ShortName"]);
    $usesUTC=$stationDescriptions::usesUTC($station["ShortName"]);
  
    $lastTimestamp = getLastEntryDateTime($databaseConnection, $tableName, $dateTimeField, $usesUTC);
    if($lastTimestamp>time()-600) //newer than 10 minutes
    {
      $count++;
    }
  }
  return $count;
}

?>
