<?php
include_once $_SERVER[ 'DOCUMENT_ROOT' ].'/include/FunctionsGeneral.php';

class DatabaseConnector {
  
  private $connection;
  
  function __construct($user="", $password="")
  { // ja ek weet dis plaintext, maar hierdie is php :(
    global $connection;
    
    $settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/../settings.conf",true);
    
	  $database="weather";
	  $con = mysql_connect($settings['database']['host'], $settings['database']['username'],$settings['database']['password']);
	  mysql_select_db($settings['database']['database'], $con);
	  
	  $connection = $con;
  }
  
  function getConnection()
  {
	  global $connection;
	  return $connection;
  }

  function closeConnection()
  {
	  global $connection;
    mysql_close($connection);
  }

}
?>
