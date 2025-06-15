<?php 
include_once $_SERVER[ 'DOCUMENT_ROOT' ].'/include/FunctionsGeneral.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ].'/include/Units.php';
include $_SERVER[ 'DOCUMENT_ROOT' ].'/sources/MeasurementDescriptions.php';
include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/DatabaseConnector.php';


$databaseConnector = new DatabaseConnector();
$databaseConnection=$databaseConnector::getConnection();

$sql = "SELECT YEAR(MIN(`Date`)) AS \"year\" FROM `aggregate_rain`";
$result = mysql_query($sql, $databaseConnection);
$assoc = mysql_fetch_assoc($result);
$startYear = $assoc["year"];

$sql = "SELECT YEAR(MAX(`Date`)) AS \"year\" FROM `aggregate_rain`";
$result = mysql_query($sql, $databaseConnection);
$assoc = mysql_fetch_assoc($result);
$endYear = $assoc["year"];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Rain Measurements</title>
<script src="sources/jquery.js" type="text/javascript"></script>
<style type="text/css">
<!--
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	color: #000000;
}
body {
	background-color: #A8CBFF;
}

td.cellLink
{
  text-align: center; 
  background: #426ecf;
}
a.cellLink
{
  display: inline-block; 
  height:100%; 
  width:100%;
}
td.cellLink:hover
{
  background: #738fcf;
}

.tableHeadings 
{
  color: #FFFFFF;
  background-color: #426ecf;
  text-align: center;
  font-weight:bold;
  padding:5;
}

.tableContent
{
  background-color: #deefff;
  color: #000000;
  text-align: center;
}

-->
</style>

</head>

<?php

?>

<body>
<div width="200px" align="center">
  <table>
    <!--small open line-->
    <tr>
      <td height="10px" colspan="5"></td>
    </tr>
    <!--Big heading-->
    <tr><td colspan="14" style="padding-top: 0px; padding-bottom: 10px; font-weight: bold; font-size: 24pt; text-align: center;">
      Monthly Rain Totals
    </td></tr>
    
    <!--small open line-->
    <tr>
      <td height="2px" colspan="5"></td>
    </tr>
    
    <!--table headings-->
    <tr>
      <td width="110"><!--Year\Month--></td>
      <?php
      foreach (range(1,12) as $month)
      {
        $dateObj = DateTime::createFromFormat('!m', $month);
        echo '<td width="80" class="tableHeadings">';
        echo $dateObj->format('F');
        echo '</td>';
      }
      echo '<td></td>';
      echo '<th width="80">';
      echo 'Total';
      echo '</th>';
      ?>
    </tr>
    
    <!--small open line-->
    <tr>
      <td height="2px" colspan="5"></td>
    </tr>
    
    <!--measurements and period-->
    <?php
	foreach (range($startYear,$endYear) as $year)
	{
		echo '<tr>';
		echo '<th class="tableHeadings">'.$year.'</th>';

		foreach (range(1,12) as $month)
		{
			#Stellenbosch
			$sql = "SELECT SUM(`Rain`) AS \"value\" FROM `aggregate_rain` WHERE `Rain`<>-1 AND YEAR(`Date`)=".$year." AND MONTH(`Date`)=".$month." GROUP BY MONTH(`Date`)";
			//echo $sql;
			$result = mysql_query($sql, $databaseConnection);
			$assoc = mysql_fetch_assoc($result);
			$value = round($assoc["value"],1);

			echo '<td width="80" class="tableContent">';
			if($value!="0") echo number_format ( $value , $decimals = 1 );
			echo '</td>';

			//       #Elsenburg
			//       $sql = "SELECT SUM(`Rain`) AS \"value\" FROM `Elsenburg` WHERE YEAR(`Date`)=".$year." AND MONTH(`Date`)=".$month." GROUP BY MONTH(`Date`)";
			//     	//echo $sql;
			// $result = mysql_query($sql, $databaseConnection);
			// $assoc = mysql_fetch_assoc($result);
			// $value = round($assoc["value"],1);

			//       echo '<td width="80" class="tableContent">';
			//       if($value!="0") echo number_format ( $value , $decimals = 1 );
			//       echo '</td>';
		}

		$sql = "SELECT SUM(`Rain`) AS \"value\" FROM `aggregate_rain` WHERE `Rain`<>-1 AND YEAR(`Date`)=".$year." GROUP BY YEAR(`Date`)";
		//echo $sql;
		$result = mysql_query($sql, $databaseConnection);
		$assoc = mysql_fetch_assoc($result);
		$value = round($assoc["value"],1);

		echo '<td></td>';

		echo '<td width="80" class="tableContent" style="font-style: italic;">';
		if($value!="0") echo number_format ( $value , $decimals = 1 );
		echo '</td>';

		echo '</tr>';
		echo '
		<!--small open line-->
		<tr>
		<td height="1px" colspan="5"></td>
		</tr>
		';
    }

	echo '<tr>';
	echo '<th>Average</th>';
    
    foreach (range(1,12) as $month)
	{
		#Stellenbosch
		$sql = "SELECT AVG(sums) AS value FROM (SELECT SUM(`Rain`) AS \"sums\" FROM `aggregate_rain` WHERE `Rain`<>-1 AND MONTH(`Date`)=".$month." GROUP BY YEAR(`Date`)) AS t1";
		//echo $sql;
		$result = mysql_query($sql, $databaseConnection);
		$assoc = mysql_fetch_assoc($result);
		$value = round($assoc["value"],1);

		echo '<td width="80" class="tableContent" style="font-style: italic;">';
		if($value!="0") echo number_format ( $value , $decimals = 1 );
		echo '</td>';
	}
      
    ?>
    
    <!--small open line-->
    <tr>
      <td height="50px" colspan="5"></td>
    </tr>
    
    
  </table>




  <table>

	<tr>
      <td height="50px" colspan="5"><a href="http://weather.sun.ac.za/graphs/rain" target="mainFrame">Click here to view the graphs</a></td>
    </tr>
    
    <!--Big heading-->
    <tr><td colspan="<?php echo (date("t")+2);?>" style="padding-top: 0px; padding-bottom: 10px; font-weight: bold; font-size: 24pt; text-align: center;">
      Daily Rain Totals - <?php echo date('Y'); ?>
    </td></tr>
    
    <!--small open line-->
    <tr>
      <td height="2px" colspan="5"></td>
    </tr>
    
    <!--table headings-->
    <tr>
    <?php
      echo '<td width="110"></td>';
      foreach (range(1,31) as $day)
      {
        echo '<td width="80" class="tableHeadings">';
        echo $day;
        echo '</td>';
      }
      echo '<td></td>';
      echo '<th width="80">';
      echo 'Total';
      echo '</th>';
    ?>
    </tr>
    
    <!--small open line-->
    <tr>
      <td height="1px" colspan="5"></td>
    </tr>
    
    <?php
    foreach (range(1,12) as $month)
    {
    	echo '<tr>';
    	$dateObj   = DateTime::createFromFormat('!m', $month);
		$monthName = $dateObj->format('F');
    	echo '<td width="80" class="tableHeadings">';
        echo $monthName;
        echo '</td>';

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));

        foreach (range(1,$daysInMonth) as $day)
        {
	        $sql = "SELECT SUM(`Rain`) AS value FROM `aggregate_rain` WHERE `Date`=\"".date('Y')."-".$month."-".$day."\"";
			//echo $sql;
			$result = mysql_query($sql, $databaseConnection);
			$assoc = mysql_fetch_assoc($result);
			$value = round($assoc["value"],1);

			if($value=="-1")
			{
				echo '<td class="tableContent" style="color: lightgray;">?</td>';
			}
			elseif($value=="0")
			{
				echo '<td class="tableContent"></td>';
			}
			else
			{
				echo '<td width="80" class="tableContent">';
				echo number_format ( $value , $decimals = 1 );
				echo '</td>';
			}
		}

		if($daysInMonth!=31)
		{
			foreach (range($daysInMonth+1,31) as $i)
			{
				echo '<td></td>';
			}
		}
		echo '<td></td>';

		$sql = "SELECT SUM(`Rain`) AS \"value\" FROM `aggregate_rain` WHERE `Rain`<>-1 AND YEAR(`Date`)=".date('Y')." AND MONTH(`Date`)=".$month." GROUP BY MONTH(`Date`)";
		//echo $sql;
		$result = mysql_query($sql, $databaseConnection);
		$assoc = mysql_fetch_assoc($result);
		$value = round($assoc["value"],1);

		echo '<td width="80" class="tableContent" style="font-style: italic;">';
		if($value!="0") echo number_format ( $value , $decimals = 1 );
		echo '</td>';

        echo '</tr>';
        echo '<tr>';
        echo '<td height="1px" colspan="5"></td>';
        echo '</tr>';
    }

    ?>
    
  </table>
<br />
<?
include ("adsense.php");
show_ip(false);
?>
</div>
</body>
</html>
<?php
$databaseConnector::closeConnection();
?>
