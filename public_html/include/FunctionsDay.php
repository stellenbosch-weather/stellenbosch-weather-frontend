<?php
include_once $_SERVER[ 'DOCUMENT_ROOT' ].'/include/FunctionsGeneral.php';

class FunctionsDay {
  private $databaseConnection;
  private $stationList;
  private $date;

  function __construct($day, $con)
  {
    global $date, $databaseConnection, $stationList;
    
    $date = $day;
    $databaseConnection = $con;
    $stationList = getListOfStations();
    
	  //build temporary table of today's value
	  foreach ($stationList as $station)
	  {
	    // Drop temp table just in case they still exist. 
	    // Likely I don't have permission for this anyway.
	    $sql = "DROP TABLE IF EXISTS `".$station.date_format($date, "Ymd")."`;";
	    $result = mysql_query($sql, $databaseConnection);
	    
	    //Create the temporary table
	    $sql = "CREATE TEMPORARY TABLE `".$station.date_format($date, "Ymd")."` AS (SELECT * FROM `".getStationTableName($station)."` WHERE `".getStationDateField($station)."` = '".date_format($date, "Y-m-d")."')";
	    
	    #echo $sql;
	    
	    $result = mysql_query($sql, $databaseConnection);
	    if($result == False)
	    {
	      //if it failed, remove the station as a possible source
	      unset($stationList[$station]);
	      echo "Removed ".$station;
	    }
	  }
  }

//measurements
  function getSampleCount($station)
  {
    global $date, $databaseConnection;
    
    $sql = "SELECT COUNT(*) AS value FROM `".$station.date_format($date, "Ymd")."`";
    
    $result = mysql_query($sql, $databaseConnection);
    
    if($result == False) return False;
    if(mysql_num_rows($result)!=1) return False;

    $assoc = mysql_fetch_assoc($result);
    
    $value = $assoc["value"];
    
    if(!is_numeric($value)) return False;
    
    return $value;
  }
  
  function getStartTime($station)
  {
    global $date, $databaseConnection;
    
    $sql = "SELECT * FROM `".$station.date_format($date, "Ymd")."` WHERE `".getStationTimeField($station)."` IS NOT NULL ORDER BY `".getStationTimeField($station)."` ASC LIMIT 1";
    
    $result = mysql_query($sql, $databaseConnection);
    
    if($result == False) return False;
    if(mysql_num_rows($result)!=1) return False;

    $assoc = mysql_fetch_assoc($result);
    
    $time = $assoc[getStationTimeField($station)];
    $time = date_create_from_format('H:i:s', $time);
    if($time==False) return $False;
    
    return $time;
  }
  
  function getEndTime($station)
  {
    global $date, $databaseConnection;
    
    $sql = "SELECT * FROM `".$station.date_format($date, "Ymd")."` WHERE `".getStationTimeField($station)."` IS NOT NULL ORDER BY `".getStationTimeField($station)."` DESC LIMIT 1";
    
    $result = mysql_query($sql, $databaseConnection);
    
    if($result == False) return False;
    if(mysql_num_rows($result)!=1) return False;

    $assoc = mysql_fetch_assoc($result);
    
    $time = $assoc[getStationTimeField($station)];
    $time = date_create_from_format('H:i:s', $time);
    if($time==False) return $False;
    
    return $time;
  }

//temperature
  function getMaxTemp($station)
  {
    global $date, $databaseConnection;
    
    $sql = "SELECT * FROM `".$station.date_format($date, "Ymd")."` WHERE `".getStationTemperatureField($station)."` IS NOT NULL ORDER BY `".getStationTemperatureField($station)."` DESC LIMIT 1";
    
    $result = mysql_query($sql, $databaseConnection);
    
    if($result == False) return False;
    if(mysql_num_rows($result)!=1) return False;

    $assoc = mysql_fetch_assoc($result);
    
    $value = $assoc[getStationTemperatureField($station)];
    $time = $assoc[getStationTimeField($station)];
    
    if(!is_numeric($value)) return False;
    
    $value = round($value,2);
    
    $time = date_create_from_format('H:i:s', $time);
    
    if($time==False) return $False;
    
    return array($value,$time);
  }
  
  function getAvgTemp($station)
  {
    global $date, $databaseConnection;
    
    $sql = "SELECT AVG(`".getStationTemperatureField($station)."`) AS average FROM `".$station.date_format($date, "Ymd")."` WHERE `".getStationTemperatureField($station)."` IS NOT NULL";
    
    $result = mysql_query($sql, $databaseConnection);
    
    if($result == False) return False;
    if(mysql_num_rows($result)!=1) return False;

    $assoc = mysql_fetch_assoc($result);
    
    $value = $assoc["average"];
    
    if(!is_numeric($value)) return False;
    
    $value = round($value, 2);
    
    return $value;
  }
  
  function getMinTemp($station)
  {
    global $date, $databaseConnection;
    
    $sql = "SELECT * FROM `".$station.date_format($date, "Ymd")."` WHERE `".getStationTemperatureField($station)."` IS NOT NULL ORDER BY `".getStationTemperatureField($station)."` ASC LIMIT 1";
    #echo $sql;
    $result = mysql_query($sql, $databaseConnection);
    #var_dump($result);
    if($result == False) return False;
    if(mysql_num_rows($result)!=1) return False;

    $assoc = mysql_fetch_assoc($result);
    
    $value = $assoc[getStationTemperatureField($station)];
    $time = $assoc[getStationTimeField($station)];
    
    if(!is_numeric($value)) return False;
    
    $value = round($value,2);
    
    $time = date_create_from_format('H:i:s', $time);
    
    if($time==False) return $False;
    
    return array($value,$time);
  }

//humidity
  function getMaxHumid($station)
  {
    global $date, $databaseConnection;
    
    $sql = "SELECT * FROM `".$station.date_format($date, "Ymd")."` WHERE `".getStationHumidityField($station)."` IS NOT NULL ORDER BY `".getStationHumidityField($station)."` DESC LIMIT 1";
    
    $result = mysql_query($sql, $databaseConnection);
    
    if($result == False) return False;
    if(mysql_num_rows($result)!=1) return False;

    $assoc = mysql_fetch_assoc($result);
    
    $value = $assoc[getStationHumidityField($station)];
    $time = $assoc[getStationTimeField($station)];
    
    if(!is_numeric($value)) return False;
    
    $value = round($value,2);
    
    $time = date_create_from_format('H:i:s', $time);
    
    if($time==False) return $False;
    
    return array($value,$time);
  }
  
  function getAvgHumid($station)
  {
    global $date, $databaseConnection;
    
    $sql = "SELECT AVG(`".getStationHumidityField($station)."`) AS average FROM `".$station.date_format($date, "Ymd")."` WHERE `".getStationHumidityField($station)."` IS NOT NULL";
    
    $result = mysql_query($sql, $databaseConnection);
    
    if($result == False) return False;
    if(mysql_num_rows($result)!=1) return False;

    $assoc = mysql_fetch_assoc($result);
    
    $value = $assoc["average"];
    
    if(!is_numeric($value)) return False;
    
    $value = round($value, 2);
    
    return $value;
  }
  
  function getMinHumid($station)
  {
    global $date, $databaseConnection;
    
    $sql = "SELECT * FROM `".$station.date_format($date, "Ymd")."` WHERE `".getStationHumidityField($station)."` IS NOT NULL ORDER BY `".getStationHumidityField($station)."` ASC LIMIT 1";
    #echo $sql;
    $result = mysql_query($sql, $databaseConnection);
    #var_dump($result);
    if($result == False) return False;
    if(mysql_num_rows($result)!=1) return False;

    $assoc = mysql_fetch_assoc($result);
    
    $value = $assoc[getStationHumidityField($station)];
    $time = $assoc[getStationTimeField($station)];
    
    if(!is_numeric($value)) return False;
    
    $value = round($value,2);
    
    $time = date_create_from_format('H:i:s', $time);
    
    if($time==False) return $False;
    
    return array($value,$time);
  }

//barometer
  function getMaxBaro($station)
  {
    global $date, $databaseConnection;
    
    $sql = "SELECT * FROM `".$station.date_format($date, "Ymd")."` WHERE `".getStationBarometerField($station)."` IS NOT NULL ORDER BY `".getStationBarometerField($station)."` DESC LIMIT 1";
    
    $result = mysql_query($sql, $databaseConnection);
    
    if($result == False) return False;
    if(mysql_num_rows($result)!=1) return False;

    $assoc = mysql_fetch_assoc($result);
    
    $value = $assoc[getStationBarometerField($station)];
    $time = $assoc[getStationTimeField($station)];
    
    if(!is_numeric($value)) return False;
    
    $value = round($value,2);
    
    $time = date_create_from_format('H:i:s', $time);
    
    if($time==False) return $False;
    
    return array($value,$time);
  }
  
  function getAvgBaro($station)
  {
    global $date, $databaseConnection;
    
    $sql = "SELECT AVG(`".getStationBarometerField($station)."`) AS average FROM `".$station.date_format($date, "Ymd")."` WHERE `".getStationBarometerField($station)."` IS NOT NULL";
    
    $result = mysql_query($sql, $databaseConnection);
    
    if($result == False) return False;
    if(mysql_num_rows($result)!=1) return False;

    $assoc = mysql_fetch_assoc($result);
    
    $value = $assoc["average"];
    
    if(!is_numeric($value)) return False;
    
    $value = round($value, 2);
    
    return $value;
  }
  
  function getMinBaro($station)
  {
    global $date, $databaseConnection;
    
    $sql = "SELECT * FROM `".$station.date_format($date, "Ymd")."` WHERE `".getStationBarometerField($station)."` IS NOT NULL ORDER BY `".getStationBarometerField($station)."` ASC LIMIT 1";
    #echo $sql;
    $result = mysql_query($sql, $databaseConnection);
    #var_dump($result);
    if($result == False) return False;
    if(mysql_num_rows($result)!=1) return False;

    $assoc = mysql_fetch_assoc($result);
    
    $value = $assoc[getStationBarometerField($station)];
    $time = $assoc[getStationTimeField($station)];
    
    if(!is_numeric($value)) return False;
    
    $value = round($value,2);
    
    $time = date_create_from_format('H:i:s', $time);
    
    if($time==False) return $False;
    
    return array($value,$time);
  }
}
?>
