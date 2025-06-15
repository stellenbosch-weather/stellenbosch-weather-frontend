<?php

class StationDescription {
  private $databaseConnection;
  
  function __construct($con)
  {
    global $databaseConnection;
    
    $databaseConnection = $con;
    
  }
  
  function getFullNames()
  {
    global $databaseConnection;
    
    $dictionary = array();
    
    $sql = "SELECT ShortName, FullName FROM WeatherStations";
    $result = mysql_query($sql, $databaseConnection);
    
    while($row = mysql_fetch_assoc($result))
    {
      $dict = array($row["ShortName"]=>$row["FullName"]);
      $dictionary = array_merge($dictionary,$dict);
    }
    return $dictionary;
  }
  
  function getFullDescription()
  {
    global $databaseConnection;
    
    $list = array();
    
    $sql = "SELECT ShortName, FullName, StationType, Accuracy, Comment, Location, Status FROM WeatherStations";
    $result = mysql_query($sql, $databaseConnection);
    
    while($row = mysql_fetch_assoc($result))
    {
      $entry = array("ShortName"=>$row["ShortName"],"FullName"=>$row["FullName"],"StationType"=>$row["StationType"],"Accuracy"=>$row["Accuracy"],"Comment"=>$row["Comment"],"Location"=>$row["Location"],"Status"=>$row["Status"]);
      $list[] = $entry;
    }
    return $list;
  }
  
  function getLongActiveStationsCount()
  {
    global $databaseConnection;
    
    $sql = "SELECT * FROM WeatherStations WHERE `Status`!=0";
    $result = mysql_query($sql, $databaseConnection);
    return mysql_num_rows($result);
  }
  
  function getTableName($shortName)
  {
    global $databaseConnection;
    
    $sql = "SELECT TableName FROM WeatherStations WHERE ShortName = \"".mysql_real_escape_string($shortName)."\"";
    $result = mysql_query($sql, $databaseConnection);
    
    while($row = mysql_fetch_assoc($result))
    {
      return $row["TableName"];
    }
    return False;
  }
  
  function getDateTimeField($shortName)
  {
    global $databaseConnection;
    
    $sql = "SELECT DateTimeField FROM WeatherStations WHERE ShortName = \"".mysql_real_escape_string($shortName)."\"";
    $result = mysql_query($sql, $databaseConnection);
    
    while($row = mysql_fetch_assoc($result))
    {
      return $row["DateTimeField"];
    }
    return False;
  }
  
  function usesUTC($shortName)
  {
    global $databaseConnection;
    
    $sql = "SELECT DateTimeUTC FROM WeatherStations WHERE ShortName = \"".mysql_real_escape_string($shortName)."\"";
    $result = mysql_query($sql, $databaseConnection);
    
    while($row = mysql_fetch_assoc($result))
    {
      if($row["DateTimeUTC"]==1)
        return True;
      else 
        return False;
    }
    return False;
  }

}

?>
