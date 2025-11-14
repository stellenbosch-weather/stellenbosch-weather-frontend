<?php 
include "include/DatabaseConnector.php";

$dbConnector = new DatabaseConnector();
$con = $dbConnector::getConnection();

// M&M
$sql = "SELECT MIN(`date`) FROM `WeatherMM` WHERE 1";
$result = mysql_query($sql, $con);
$row = mysql_fetch_row($result);
$minmm = $row[0];

$sql = "SELECT MAX(`date`) FROM `WeatherMM` WHERE 1";
$result = mysql_query($sql, $con);
$row = mysql_fetch_row($result);
$maxmm = $row[0];

// M-R
$sql = "SELECT MIN(`date`) FROM `Weather_mmroof` WHERE 1";
$result = mysql_query($sql, $con);
$row = mysql_fetch_row($result);
$minmr = $row[0];

$sql = "SELECT MAX(`date`) FROM `Weather_mmroof` WHERE 1";
$result = mysql_query($sql, $con);
$row = mysql_fetch_row($result);
$maxmr = $row[0];

// E&E
$sql = "SELECT MIN(`date`) FROM `WeatherEE` WHERE 1";
$result = mysql_query($sql, $con);
$row = mysql_fetch_row($result);
$minee = $row[0];

$sql = "SELECT MAX(`date`) FROM `WeatherEE` WHERE 1";
$result = mysql_query($sql, $con);
$row = mysql_fetch_row($result);
$maxee = $row[0];

// Sonbesie
$sql = "SELECT MIN(`Date`) FROM `SB_TMin` WHERE 1";
$result = mysql_query($sql, $con);
$row = mysql_fetch_row($result);
$minsb = $row[0];

$sql = "SELECT MAX(`Date`) FROM `SB_TMin` WHERE 1";
$result = mysql_query($sql, $con);
$row = mysql_fetch_row($result);
$maxsb = $row[0];

$dbConnector::closeConnection();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
<title>Download Data</title>

<!-- My style -->
<style type="text/css">
<!--
body {
    background-color: #DEEFFF;
}
body,td,th {
    font-family: Helvetica, Arial, sans-serif;
    color: #000000;
}
DIV.outer
{
    position: left;
    left: 50%;
    //top: 45%;
    width: 600px;
    height: 200px;
    margin-left: 20px; /* half of width */
    margin-top: 10px;  /* half of height */
    font-family: sans-serif;
    font-size: 16px;
    //padding-left: 20px;
}
a:link {
    text-decoration: none;
    color: #000000;
}
a:visited {
    text-decoration: none;
    color: #000000;
}
a:hover {
    text-decoration: underline;
    color: #000033;
}
a:active {
    text-decoration: none;
    color: #000033;
}
.headings {
    font-size: 22px;
}

.subheadings {
    font-size: 20px;
}

.subsubheadings {
    font-size: 18px;
}
-->
</style>

<!-- jquery style -->
<link rel="stylesheet" href="sources/jquery-ui/css/smoothness/jquery-ui-1.8.16.custom.css">

<!-- java scripts -->
<script src="sources/jquery.js" type="text/javascript"></script>
<script src="sources/jquery-ui/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<script>
    $(function() {
        $( "#startdatepicker" ).datepicker({ dateFormat: 'dd MM yy', defaultDate: '-1d' });
    });
    $(function() {
        var myEndDate=new Date();
        $( "#enddatepicker" ).datepicker({ dateFormat: 'dd MM yy', defaultDate: 0 });
    });
</script>

</head>

<body>
<div class="outer">
<span class="headings">Download Weather Data</span><br>
Select the data you wish to download, fill in your details and click on "Download". Processing may take up to 5 minutes before the download starts. Afterwards you will have a .zip-archive containing files with <a href="http://en.wikipedia.org/wiki/Comma-separated_values">comma-seperated values (CSV)</a>. You can open these with a program like Excel or Matlab.<br>

<form name="selection" action="download/php_download.php" method="post">
<center>
<table border="0" cellpadding="10" cellspacing="1" width="420px">
<tr>
<td><!--
    <span class="subheadings">NOAA Summaries</span><br>
    <input type="checkbox" name="files[]" value="noaa_month" />This month<br>
    <input type="checkbox" name="files[]" value="noaa_prev_month" />Last month<br>
    <br>
    <input type="checkbox" name="files[]" value="noaa_year" />This year<br>
    <input type="checkbox" name="files[]" value="noaa_prev_year" />Last year<br>
    <br>-->
    
    <!--<span class="subheadings">Detailed reports</span><br>-->
    <table border="0">
    <tr><td colspan="2">
    <span class="subsubheadings">Select a date range for which you need the data</span><br>
    <span style="font-size: 14px">Please keep the date range within a few days. A bigger range will take too long due to server load and it will time out.</span>
    </td></tr>
    <tr><td>
    Start date: 
    </td><td>
    <input type="date" id="startdatepicker" name="startdate"><br>
    </td></tr>
    <tr><td>
    End date: 
    </td><td>
    <input type="date" id="enddatepicker" name="enddate"><br>
    </td></tr>
    </table>
    <br>
    <span class="subsubheadings">Select the data you need</span><br>
    <input type="checkbox" name="detail[]" value="mm" />M&M tower Weather Station 
                <small>(<?php echo $minmm." to ".$maxmm; ?>)</small><br>
    <input type="checkbox" name="detail[]" value="mr" />M&M roof Weather Station 
                <small>(<?php echo $minmr." to ".$maxmr; ?>)</small><br>
    <input type="checkbox" name="detail[]" value="ee" />E&E Weather Station 
                <small>(<?php echo $minee." to ".$maxee; ?>)</small><br>
    <br>
    <input type="checkbox" name="detail[]" value="sb_tdaily" />Sonbesie Daily (Minimums and maximums)<br>
    <input type="checkbox" name="detail[]" value="sb_thour" />Sonbesie Hour (Includes weather data)<br>
    <input type="checkbox" name="detail[]" value="sb_tmin" />Sonbesie Minute (Minutely solar data only)<br>
    <small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sonbesie data available for <?php echo $minsb." to ".$maxsb; ?></small><br>
<!--<input type="checkbox" name="detail[]" value="sb_thermo" />Sonbesie Thermocouple<br>-->
</td></tr>
</table>
</center>
<br>
<span class="subheadings">Disclaimer</span>
<ul>
<li>This weather and solar data are made available under the <br>
<b>Public Domain Dedication and License version v1.0</b> <br>
whose full text can be found at <a href="http://opendatacommons.org/licenses/pddl/1.0/">http://opendatacommons.org/licenses/pddl/1.0/</a> and <a href="http://weather.sun.ac.za/ODC_PDDL.pdf">http://weather.sun.ac.za/ODC_PDDL.pdf</a>
<li>The accuracy and completeness of the data can not be guaranteed.
<li>Although it is not legally necessary to attribute the University of Stellenbosch, it will be aprreciated.
</ul>
<input type="radio" name="agree" value="yes" /> I agree<br />
<input type="radio" name="agree" value="no" checked="yes"/> I do not agree<br>
Email address: <input type="text" name="email" /><br>
<input type="submit" value="Download" />
    
<br>

    
<br>
</form>
<br>
</div>
</body>
</html>
