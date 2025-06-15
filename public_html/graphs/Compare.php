<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
    <meta http-equiv="refresh" content="900; url=http://weather.sun.ac.za/graphs/Compare.php">
    <script type="text/javascript">
    // http://www.somacon.com/p143.php
    // return the value of the radio button that is checked
    // return an empty string if none are checked, or
    // there are no radio buttons
    function getCheckedValue(radioObj) {
        if(!radioObj)
            return "";
        var radioLength = radioObj.length;
        if(radioLength == undefined)
            if(radioObj.checked)
                return radioObj.value;
            else
                return "";
        for(var i = 0; i < radioLength; i++) {
            if(radioObj[i].checked) {
                return radioObj[i].value;
            }
        }
        return "";
    }
    // set the radio button with the given value as being checked
    // do nothing if there are no radio buttons
    // if the given value does not exist, all the radio buttons
    // are reset to unchecked
    function setCheckedValue(radioObj, newValue) {
        if(!radioObj)
            return;
        var radioLength = radioObj.length;
        if(radioLength == undefined) {
            radioObj.checked = (radioObj.value == newValue.toString());
            return;
        }
        for(var i = 0; i < radioLength; i++) {
            radioObj[i].checked = false;
            if(radioObj[i].value == newValue.toString()) {
                radioObj[i].checked = true;
            }
        }
    }
    function populateLists()
    {
        var availableGraphs = { 'OutsideTemp':'Outdoor Temperature',
                                'HeatIndex':'Outdoor Heat Index',
                                'THW':'THW',
                                'THSW':'THSW',
                                'DewPoint':'Dew Point',
                                'OutsideHumidity':'Outdoor Humidity',
                                'EMC':'Equilibrium Moisture Content',
                                'Barometer':'Barometer',
                                'Reception':'Wireless Station Reception',
                                'AirDensity':'Air Density',
                                'CoolDegDays':'Cool Degree Days',
                                'HeatDegDays':'Heat Degree Days',
                                'InsideTemp':'Indoor Temperature',
                                'InsideHeatIndex':'Indoor Heat Index',
                                'InsideDewPoint':'Indoor Dew Point',
                                'InsideHumidity':'Indoor Humidity',
                                'SolarRad':'Solar Radiation',
                                'HighSolarRad':'Solar Radiation Highs',
                                'SolarEnergy':'Solar Energy',
                                'UV':'Ultraviolet',
                                'HighUV':'UV Highs',
                                'UVDose':'UV Dose',
                                'WindDirection':'Wind Direction',
                                'WindSpeed':'Wind Speed',
                                'WindRun':'Wind Run',
                                'WindChill':'Wind Chill',
                                'HighWindDir':'Direction of Wind Highs',
                                'HiWindSpeed':'Speed of Wind Highs',
                                'Rain':'Rain',
                                'RainRate':'Rain Rate',
                                'ET':'Evapotranspiration'
                                };
        document.getElementById("graph1").options.length=0;
        document.getElementById("graph2").options.length=0;
        var tel=0;
        for (i in availableGraphs)
        {
            document.getElementById("graph1").options[tel]=new Option(availableGraphs[i], i, false, false);
            document.getElementById("graph2").options[tel]=new Option(availableGraphs[i], i, false, false);
            tel++;
        }
        document.getElementById("graph1").selectedIndex=0;
        document.getElementById("graph2").selectedIndex=7;
    }
    function reloadGraphs()
    {
        var period = getCheckedValue(document.radioForm.period);
        var graph1 = document.getElementById("graph1").options[document.getElementById("graph1").selectedIndex];
        graph1 = graph1.value;
        var graph2 = document.getElementById("graph2").options[document.getElementById("graph2").selectedIndex];
        graph2 = graph2.value;
        
        document["image1"].src="http://weather.sun.ac.za/weatherlink/graphs"+period+"/"+graph1+"History.gif";
        document["image2"].src="http://weather.sun.ac.za/weatherlink/graphs"+period+"/"+graph2+"History.gif";;
        
    }
    </script>
  </head>
  <body>
    <center>
    <div style="font-family: sans-serif;">
    <table style="text-align: center;">
    <tr>
        <td colspan="3"><p><big><big>Compare graphs</big></big><br></p></td>
    </tr>
    <tr>
        <form id="radioForm" name="radioForm">
        <td colspan="2">Period to compare: 
            <!--<select id="period" name="period" onChange="reloadGraphs()">
                <option value="day">24 Hours</option>
                <option value="week">7 days</option>
                <option value="month" selected="selected">1 Month</option>
            </select>-->
            <input type="radio" id="day" name="period" value="day" onClick="reloadGraphs()"/> Day
            <input type="radio" id="week" name="period" value="week" onClick="reloadGraphs()"/> Week
            <input type="radio" id="month" name="period" value="month" onClick="reloadGraphs()"/> Month
        </td>
        </form>
    </tr>
    <tr>
        <td>
            <select id="graph1" name="graph1" size="11" style="background-color:white; width:200px" onkeyup="reloadGraphs()" onChange="reloadGraphs()">
            </select>
        </td>
        <td><img name="image1" style="width: 320px; height: 200px;" alt="Graph 1" src="http://weather.sun.ac.za/weatherlink/graphsweek/WindDirectionHistory.gif"></td>
    </tr>
    <tr>
        <td>
            <select id="graph2" name="graph2" size="11" style="background-color:white;  width:200px" onkeyup="reloadGraphs()" onChange="reloadGraphs()">
            </select>
        </td>
        <td><img name="image2" style="width: 320px; height: 200px;" alt="Graph 2" src="http://weather.sun.ac.za/weatherlink/graphsweek/WindDirectionHistory.gif"></td>
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
    <script type="text/javascript">
        populateLists();
        setCheckedValue(document.radioForm.period,"month");
        reloadGraphs(); //first set defaults, then reload
    </script>
  </body>
</html>
