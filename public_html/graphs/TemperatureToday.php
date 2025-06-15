<?php
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_line.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_date.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_plotband.php');

//header("Content-type: image/png");

$user="weatherclient";
$password="REDACTED";
$database="weather";
mysql_connect('localhost',$user,$password);
mysql_select_db($database);

$query = "SELECT time,tempout FROM WeatherMM WHERE date = date(NOW())";
//$query = "SELECT time,tempout FROM WeatherMM WHERE 1 ORDER BY date DESC LIMIT 1440";
$result = mysql_query($query);
$tempsMM = array();
$timesMM = array();
while ($latest = (mysql_fetch_assoc($result))){
	#array_push($times, substr($latest["time"],0,5));
	array_push($timesMM, $latest["time"]);
	array_push($tempsMM, $latest["tempout"]);
}

$query = "SELECT time,tempout FROM WeatherEE WHERE date = date(NOW())";
$result = mysql_query($query);
$tempsEE = array();
$timesEE = array();
while ($latest = (mysql_fetch_assoc($result))){
	array_push($timesEE, $latest["time"]);
	array_push($tempsEE, $latest["tempout"]);
}

$query = "SELECT UNIX_TIMESTAMP(`TimeStamp`) 
AS 'time', `AirTC_Avg` as 'tempout' FROM SB_TMin 
WHERE `Date` = date(NOW())";
$result = mysql_query($query);
$tempsSB = array();
$timesSB = array();
while ($latest = (mysql_fetch_assoc($result))){
	array_push($timesSB, $latest["time"]);
	array_push($tempsSB, $latest["tempout"]);
}


function TimeCallback($aVal) {
    return date('H:i', $aVal);
}

// Setup the graph
$graph = new Graph(500,300);
$graph->SetMarginColor('white');
$graph->title->Set('Temperature');
$graph->subtitle->Set(date("l").", ".date("j")." ".date("F")." ".date("Y"));
 
// Create a simple lines
if(count($tempsMM)>0){
	$l1 = new LinePlot($tempsMM, $timesMM);
	$l1->SetColor('#8658C2');
	$l1->SetWeight(3);
	$l1->SetLegend('M&M');
	$graph->Add($l1);
}
if(count($tempsEE)>0){
	$l2 = new LinePlot($tempsEE, $timesEE);
	$l2->SetColor('#448356');
	$l2->SetWeight(3);
	$l2->SetLegend('E&E');
	$graph->Add($l2);
}
if(count($tempsSB)>0){
	$l3 = new LinePlot($tempsSB, $timesSB);
	$l3->SetColor('#CCCC66');
	$l3->SetWeight(3);
	$l3->SetLegend('SB');
	$graph->Add($l3);
}


$todayTimestamp = date('U',mktime(0,0,0,date('n'),date('j')));

//laasnag
$band = new PlotBand(VERTICAL,BAND_SOLID,
	strtotime(date("j F Y ",strtotime("-1 days")).date_sunset(strtotime("-1 days"), SUNFUNCS_RET_STRING, -33.928, 18.866, 90+50/60, 2)),
	strtotime(date("j F Y ").date_sunrise(time(), SUNFUNCS_RET_STRING, -33.928, 18.866, 90+50/60, 2)),
	'gray5@0.85');
$band->ShowFrame(false); // No border around the plot band
$graph->Add($band);

//vanaand
$band = new PlotBand(VERTICAL,BAND_SOLID,
	strtotime(date("j F Y ").date_sunset(time(), SUNFUNCS_RET_STRING, -33.928, 18.866, 90+50/60, 2)),
	strtotime(date("j F Y ",strtotime("+1 days")).date_sunrise(strtotime("+1 days"), SUNFUNCS_RET_STRING, -33.928, 18.866, 90+50/60, 2)),
	'gray5@0.85');
$band->ShowFrame(false);
$graph->Add($band);

// Setup X-axis labels
$graph->SetScale('datint',0,0,$todayTimestamp,$todayTimestamp+86400);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->SetLabelFormatCallback('TimeCallback');
$graph->xaxis->scale->ticks->Set(3600,1800);
$graph->SetTickDensity(TICKD_DENSE, TICKD_NORMAL);

$graph->xgrid->Show(true,false);
$graph->legend->SetPos(0.02,0.02,'right','top');

// Y-axis units
$graph->yaxis->title->Set("Degree Celsius (\xB0C)");

// Send back graph to client
$graph->Stroke();

?>
