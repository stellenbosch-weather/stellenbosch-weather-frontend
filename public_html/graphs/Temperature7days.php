<?php
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_line.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_date.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_plotband.php');

#header("Content-type: image/png");

// Toets of grafiek nie reeds in die afgelope 10min geteken is nie.
#$graph = new Graph(500, 300, 'Temperature7days', 10);
$graph = new Graph(500, 300);

$user="weatherclient";
$password="REDACTED";
$database="weather";
mysql_connect('localhost',$user,$password);
mysql_select_db($database);

#$query = "SELECT UNIX_TIMESTAMP(ADDTIME(`date`,`time`)) AS 'time',tempout FROM WeatherMM WHERE UNIX_TIMESTAMP(ADDTIME(`date`,`time`)) >= (UNIX_TIMESTAMP() - 86400)";
$query = "SELECT UNIX_TIMESTAMP( ADDTIME(  `date` ,  `time` ) ) 
AS  'time', tempout
FROM WeatherMM
WHERE  `date` >= DATE_SUB( CURDATE( ) , INTERVAL 7 DAY )";
$result = mysql_query($query);
$tempsMM = array();
$timesMM = array();
while ($latest = (mysql_fetch_assoc($result))){
	array_push($timesMM, $latest["time"]);
	array_push($tempsMM, $latest["tempout"]);
}
#$query = "SELECT UNIX_TIMESTAMP(ADDTIME(`date`,`time`)) AS 'time',tempout FROM WeatherEE WHERE UNIX_TIMESTAMP(ADDTIME(`date`,`time`)) >= (UNIX_TIMESTAMP() - 86400)";
$query = "SELECT UNIX_TIMESTAMP( ADDTIME(  `date` ,  `time` ) ) 
AS  'time', tempout
FROM WeatherEE
WHERE  `date` >= DATE_SUB( CURDATE( ) , INTERVAL 7 DAY )";
$result = mysql_query($query);
$tempsEE = array();
$timesEE = array();
while ($latest = (mysql_fetch_assoc($result))){
	array_push($timesEE, $latest["time"]);
	array_push($tempsEE, $latest["tempout"]);
}

//laas jaar
$query = "SELECT UNIX_TIMESTAMP( DATE_ADD( ADDTIME(  `date` ,  `time` ) , INTERVAL 1 YEAR ) ) AS  'time', tempout
FROM WeatherMM
WHERE  `date` >= DATE_SUB( DATE_SUB( CURDATE( ), INTERVAL 7 
DAY ) , INTERVAL 1 YEAR ) 
AND `date` <= DATE_SUB( CURDATE( ), INTERVAL 1 YEAR )";
$result = mysql_query($query);
$tempsLJ = array();
$timesLJ = array();
while ($latest = (mysql_fetch_assoc($result))){
	array_push($timesLJ, $latest["time"]);
	array_push($tempsLJ, $latest["tempout"]);
}

//llaas jaar
$query = "SELECT UNIX_TIMESTAMP( DATE_ADD( ADDTIME(  `date` ,  `time` ) , INTERVAL 2 YEAR ) ) AS  'time', tempout
FROM WeatherMM
WHERE  `date` >= DATE_SUB( DATE_SUB( CURDATE( ), INTERVAL 7 
DAY ) , INTERVAL 2 YEAR ) 
AND `date` <= DATE_SUB( CURDATE( ), INTERVAL 2 YEAR )";
$result = mysql_query($query);
$tempsLLJ = array();
$timesLLJ = array();
while ($latest = (mysql_fetch_assoc($result))){
	array_push($timesLLJ, $latest["time"]);
	array_push($tempsLLJ, $latest["tempout"]);
}

// Setup the graph
function TimeCallback($aVal) {
	if(date('H',$aVal) == "00") return "";
	else return date('D', $aVal)."\n".date('d/m', $aVal);
}

$graph->SetScale('datint');
$graph->SetMarginColor('white');
$graph->title->Set('Temperature');
$graph->subtitle->Set(date("d F Y", strtotime("-7 days"))." - ".date("j F Y"));

// Fonts
$graph->SetUserFont('msttcorefonts/verdanab.ttf');
$graph->title->SetFont(FF_USERFONT,FS_NORMAL,12);
#$graph->SetUserFont2('msttcorefonts/verdana.ttf');
#$graph->subtitle->SetFont(FF_USERFONT2,FS_NORMAL,8);
#$graph->xaxis->SetFont(FF_USERFONT2,FS_NORMAL,8);
#$graph->yaxis->SetFont(FF_USERFONT2,FS_NORMAL,8);
#$graph->yaxis->title->SetFont(FF_USERFONT2,FS_NORMAL,8);
 
if(count($tempsMM)){
	// Create a simple line
	$l1 = new LinePlot($tempsMM, $timesMM);
	$l1->SetColor('blue');
	$l1->SetWeight(3);
	$l1->SetLegend('2010');
	$graph->Add($l1);
}
if(count($tempsEE)){
	$l2 = new LinePlot($tempsEE, $timesEE);
	$l2->SetColor('#448356');
	$l2->SetWeight(1);
	$l2->SetLegend('E&E');
	#$graph->Add($l2);
}
if(count($tempsLJ)){
	$lj = new LinePlot($tempsLJ, $timesLJ);
	$lj->SetColor('#8658C2');
	$lj->SetWeight(1);
	$lj->SetLegend('2009');
	$graph->Add($lj);
}
if(count($tempsLLJ)){
	$llj = new LinePlot($tempsLLJ, $timesLLJ);
	$llj->SetColor('#448356');
	$llj->SetWeight(1);
	$llj->SetLegend('2008');
	$graph->Add($llj);
}

// Maak die nagte grys
for ($i = -1; $i <= 7; $i++) {
	$band = new PlotBand(VERTICAL,BAND_SOLID,
		strtotime(date("j F Y ",strtotime("-".($i+1)." days")).date_sunset(strtotime("-".($i+1)." days"), SUNFUNCS_RET_STRING, -33.928, 18.866, 90+50/60, 2)),
		strtotime(date("j F Y ",strtotime("-".($i)." days")).date_sunrise(strtotime("-".($i)." days"), SUNFUNCS_RET_STRING, -33.928, 18.866, 90+50/60, 2)),
		'gray5@0.85');
	$band->ShowFrame(false); // No border around the plot band
	$graph->Add($band);
}

// Setup X-axis labels
#$graph->SetScale('datint',0,0,0,1440);
#$graph->xaxis->SetTickLabels($times);
#$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->SetLabelFormatCallback('TimeCallback');
#$graph->xaxis->scale->ticks->Set(86400,10800);
#$graph->SetTickDensity(TICKD_DENSE, TICKD_NORMAL);

$graph->xgrid->Show(true,false);
$graph->legend->SetPos(0.02,0.02,'right','top');

// Y-axis units
$graph->yaxis->title->Set("Degree Celsius (\xB0C)");

// Send back graph to client
$graph->Stroke();

?>
