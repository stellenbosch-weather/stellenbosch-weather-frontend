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
    <meta http-equiv="refresh" content="900; url=http://weather.sun.ac.za/graphs/OutdoorGraphs.php">
  </head>
  <body>
    <center>
    <div style="font-family: sans-serif;">
    <table style="text-align: center;">
    <tr>
        <td colspan="3"><p><big><big>Graphs for outdoor measurements</big></big><br></p></td>
    </tr>
    <tr>
        <td>24 Hours</td>
        <td>7 Days</td>
        <td>1 Month</td>
    </tr>
    <tr>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsday/OutsideTempHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsweek/OutsideTempHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsmonth/OutsideTempHistory.gif"></td>
    </tr>
    <tr>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsday/HeatIndexHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsweek/HeatIndexHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsmonth/HeatIndexHistory.gif"></td>
    </tr>
    <tr>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsday/THWHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsweek/THWHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsmonth/THWHistory.gif"></td>
    </tr>
    <tr>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsday/THSWHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsweek/THSWHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsmonth/THSWHistory.gif"></td>
    </tr>
    <tr>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsday/DewPointHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsweek/DewPointHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsmonth/DewPointHistory.gif"></td>
    </tr>
    <tr>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsday/OutsideHumidityHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsweek/OutsideHumidityHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsmonth/OutsideHumidityHistory.gif"></td>
    </tr>
    <tr>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsday/EMCHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsweek/EMCHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsmonth/EMCHistory.gif"></td>
    </tr>
    <tr>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsday/BarometerHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsweek/BarometerHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsmonth/BarometerHistory.gif"></td>
    </tr>
    <!--<tr>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsday/ReceptionHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsweek/ReceptionHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsmonth/ReceptionHistory.gif"></td>
    </tr>-->
    <tr>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsday/AirDensityHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsweek/AirDensityHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsmonth/AirDensityHistory.gif"></td>
    </tr>
    <tr>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsday/CoolDegDaysHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsweek/CoolDegDaysHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsmonth/CoolDegDaysHistory.gif"></td>
    </tr>
    <tr>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsday/HeatDegDaysHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsweek/HeatDegDaysHistory.gif"></td>
        <td><img style="width: 320px; height: 200px;" alt="outside temp day" src="http://weather.sun.ac.za/weatherlink/graphsmonth/HeatDegDaysHistory.gif"></td>
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
