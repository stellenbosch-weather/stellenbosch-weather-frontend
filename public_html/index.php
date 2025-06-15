<?php
ini_set('display_errors', 1);
ini_set('html_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

include('sources/mobile_device_detect.php');
mobile_device_detect('true','true','true','true','true','true','http://weather.sun.ac.za/mobile.php','');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="google-site-verification" content="byA82YUIQj-oegOdOL6EW70cAI7Atosb2gSjcCKvnXA" />
  <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
  <title>Stellenbosch Weather</title>
</head>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-103781011-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-103781011-1');
</script>

<frameset rows="150,*" cols="*" framespacing="1" frameborder="yes" border="1" bordercolor="#426ECF">
  <frame src="heading.html" name="topFrame" scrolling="no" noresize="noresize" id="topFrame" title="topFrame" />
  <frameset rows="*" cols="184,*" framespacing="1" frameborder="yes" border="1" bordercolor="#426ECF">
    <frame src="menu.php" name="leftFrame" noresize="noresize" id="leftFrame" title="leftFrame" />
<? 
if(count($_GET) <= 0){
	echo "    <frame src=\"home.php\" name=\"mainFrame\" id=\"mainFrame\" title=\"mainFrame\" />";
}
elseif(isset($_GET["p"]) and $_GET["p"] != ""){
	echo "    <frame src=\"".$_GET["p"].".php\" name=\"mainFrame\" id=\"mainFrame\" title=\"mainFrame\" />";
}
else {
	echo "    <frame src=\"home.php\" name=\"mainFrame\" id=\"mainFrame\" title=\"mainFrame\" />";
}
?>

  </frameset>
</frameset><noframes></noframes>
</html>
