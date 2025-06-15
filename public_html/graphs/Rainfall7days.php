<?php
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_bar.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_line.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_date.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_plotband.php');

//header("Content-type: image/png");

function TimeCallback($aVal) {
	if(date('H',$aVal) == "00") return "";
	else return date('D', $aVal)."\n".date('d/m', $aVal);
}
function MMCallback($aVal) {
    return round($aVal, 1);
}

$user="weatherclient";
$password="REDACTED";
$database="weather";
mysql_connect('localhost',$user,$password);
mysql_select_db($database);

/*
http://forums.mysql.com/read.php?10,202789,202807#msg-202807

From artfulsoftware: 

http://www.artfulsoftware.com/infotree/queries.php 

Group data by datetime periods 
To group rows by a time period whose length in minutes divides evenly into 60, use this formula: 

GROUP BY ((60/periodMinutes) * HOUR( thistime ) + FLOOR( MINUTE( thistime ) / periodMinutes )) 

where thistime is the TIME column and periodMinutes is the period length in minutes. So to group by 15-min periods, write ... 

SELECT ... 
GROUP BY ( 4 * HOUR( thistime ) + FLOOR( MINUTE( thistime ) / 15 )) 
*/


$query = "SELECT UNIX_TIMESTAMP(ADDTIME(`date`,`time`)) AS 'time',round(sum(`rain`),1) as rain FROM `WeatherMM` WHERE `date` >= DATE_SUB(CURDATE(),INTERVAL 7 DAY) GROUP BY UNIX_TIMESTAMP(ADDTIME(`date`,HOUR(`time`)))";
$result = mysql_query($query);
$rainMM = array();
$timesMM = array();
while ($latest = (mysql_fetch_assoc($result))){
	array_push($timesMM, $latest["time"]);
	array_push($rainMM, $latest["rain"]);
}
$query = "SELECT UNIX_TIMESTAMP(ADDTIME(`date`,`time`)) AS 'time',round(sum(`rain`),1) as rain FROM `WeatherEE` WHERE `date` >= DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND ADDTIME(`date`,`time`) >= SUBTIME(NOW(),'23:50:00') GROUP BY ( (60/60) * HOUR( `time` ) + FLOOR( MINUTE( `time` ) / 60 ))";
$result = mysql_query($query);
$rainEE = array();
$timesEE = array();
while ($latest = (mysql_fetch_assoc($result))){
	array_push($timesEE, $latest["time"]);
	array_push($rainEE, $latest["rain"]);
}

// Setup the graph
$graph = new Graph(500, 300);
$graph->SetScale('datlin');
$graph->SetMarginColor('white');
$graph->title->Set('Rain per hour');
$graph->subtitle->Set(date("d F Y", strtotime("-7 days"))." - ".date("j F Y"));

// Fonts
$graph->SetUserFont('msttcorefonts/verdanab.ttf');
$graph->title->SetFont(FF_USERFONT,FS_NORMAL,12);

// Create the bar plots
$b1plot = new BarPlot($rainMM,$timesMM);
$b1plot->SetAbsWidth(3);
$graph->Add($b1plot);

//nagte
for ($i = -1; $i <= 7; $i++) {
	$band = new PlotBand(VERTICAL,BAND_SOLID,
		strtotime(date("j F Y ",strtotime("-".($i+1)." days")).date_sunset(strtotime("-".($i+1)." days"), SUNFUNCS_RET_STRING, -33.928, 18.866, 90+50/60, 2)),
		strtotime(date("j F Y ",strtotime("-".($i)." days")).date_sunrise(strtotime("-".($i)." days"), SUNFUNCS_RET_STRING, -33.928, 18.866, 90+50/60, 2)),
		'gray5@0.85');
	$band->ShowFrame(false); // No border around the plot band
	$graph->Add($band);
}

//X as
#$graph->xaxis->SetLabelAngle(90);
#$graph->xaxis->scale->ticks->Set(1800,3600);
$graph->xaxis->SetLabelFormatCallback('TimeCallback');
$graph->xgrid->Show(true,false);

//Y as
$graph->yaxis->title->Set("Millimeter");
$graph->yscale->SetGrace(20);
$graph->yaxis->SetLabelFormatCallback('MMCallback');

// Send back graph to client
$graph->Stroke();

?>
