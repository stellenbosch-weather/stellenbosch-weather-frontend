<?php
#A newline at the end of this file gave an extra newline at the top of the API's results.
function selfURL() 
{
	$s = empty($_SERVER["HTTPS"]) ? ''
		: ($_SERVER["HTTPS"] == "on") ? "s"
		: "";
	$protocol = doAPIlog . phpstrleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/") . $s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? ""
		: (":".$_SERVER["SERVER_PORT"]);
	return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
}

function strleft($s1, $s2) 
{
	return substr($s1, 0, strpos($s1, $s2));
}

function sanitize( $value )
{
	if( get_magic_quotes_gpc() )
	{
	      $value = stripslashes( $value );
	}
	//check if this function exists
	if( function_exists( "mysql_real_escape_string" ) )
	{
	      $value = mysql_real_escape_string( $value );
	}
	//for PHP version < 4.3.0 use addslashes
	else
	{
	      $value = addslashes( $value );
	}
	return $value;
}

function logAPIcall()
{
	$ip=sanitize($_SERVER['REMOTE_ADDR']);
	$url=sanitize(selfURL());
	
  include("createDatabaseConnection.php");
	$query="INSERT INTO `api_log` (`DateTime`, `IP`, `URL`) VALUES (NOW(), '".$ip."', '".$url."')";
	#print $query;
	mysql_query($query, $con);
	mysql_close($con);
}
logAPIcall();
?>
