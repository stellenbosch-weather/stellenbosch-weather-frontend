<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<?php 
$GLOBALS['adl_alt_docroot']=''.'/home/weather/public_html/trace_watch';
@include_once $GLOBALS['adl_alt_docroot'].'/twatch_include/logger.php';
?>
<html>
  <head>
    <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
    <title>
      Graphs
    </title>
    <meta http-equiv="refresh" content="900; url=http://weather.sun.ac.za/graphs/PrecipGraphs.php">
  </head>
  <body>
    <center>
    <div style="font-family: sans-serif;">
    <table style="text-align: center;">
    <tr>
        <td colspan="3"><p><big><big>Graphs for precipitation</big></big><br></p></td>
    </tr>
    <tr>
        <td>24 Hours</td>
        <td>7 Days</td>
        <td>1 Month</td>
    </tr>
    <tr>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsday/RainHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsweek/RainHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsmonth/RainHistory.gif"></td>
    </tr>
    <tr>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsday/RainRateHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsweek/RainRateHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsmonth/RainRateHistory.gif"></td>
    </tr>
    <tr>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsday/ETHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsweek/ETHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsmonth/ETHistory.gif"></td>
    </tr>
    <tr>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsday/THSWHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsweek/THSWHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsmonth/THSWHistory.gif"></td>
    </tr>
    </table>
    </div>
    </center>
    <br>
       <?php
              $visitor = $_SERVER['REMOTE_ADDR'];
              if (preg_match("/^146.232./",$visitor)) {
                    //echo "You are visiting from the Stellenbosch campus. IP = ".$visitor;
              }
              elseif (preg_match("/^10.20./",$visitor)) {
                    //echo "You are visiting from the SCN network. IP = ".$visitor;
              }
              elseif (preg_match("/^10.110./",$visitor)) {
                    //echo "You are visiting from MatiesWIFI. IP = ".$visitor;
              } 
              elseif (preg_match("/^10./",$visitor) or preg_match("/^192.168./",$visitor) or preg_match("/^172./",$visitor)) {
                    //echo "You are visiting from a private IP range. IP = ".$visitor;
              } 
              elseif (preg_match("/^127./",$visitor)) {
                    echo "Stop h4x0r1n9 this server. Your IP = ".$visitor;
              } 
              else {
                    echo ("
                    <script type=\"text/javascript\">
                    <!--
                    google_ad_client = \"pub-3293290768943558\";
                    /* 728x90, created 6/3/10 */
                    google_ad_slot = \"1039362827\";
                    google_ad_width = 728;
                    google_ad_height = 90;
                    //-->
                    <script type=\"text/javascript\" 
                    src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\">
                    </script>"); 
              }; 
        ?>
    </div>
  </body>
</html>
