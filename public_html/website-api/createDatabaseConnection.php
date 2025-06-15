<?php

  $settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/../settings.conf",true);

	mysql_connect($settings['database']['host'], $settings['database']['username'],$settings['database']['password']);
	  mysql_select_db($settings['database']['database']);
?>
