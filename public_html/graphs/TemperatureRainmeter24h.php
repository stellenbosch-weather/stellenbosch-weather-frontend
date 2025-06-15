<?php
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_line.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_date.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_plotband.php');

#header("Content-type: image/png");

// Toets of grafiek nie reeds in die afgelope 10min geteken is nie.
#$graph = new Graph(500, 300, 'Temperature24h', 10);
$graph = new Graph(500, 300);

$user="weatherclient";
$password="REDACTED";
$database="weather";
mysql_connect('localhost',$user,$password);
mysql_select_db($database);

#$query = "SELECT UNIX_TIMESTAMP(ADDTIME(`date`,`time`)) AS 'time',tempout FROM WeatherMM WHERE UNIX_TIMESTAMP(ADDTIME(`date`,`time`)) >= (UNIX_TIMESTAMP() - 86400)";
$query = "SELECT UNIX_TIMESTAMP(`Datetime`) 
AS 'time',Temperature FROM Weather_rainmeter
WHERE `Datetime` >= SUBTIME(NOW(),'24:01:00')
AND `Temperature` IS NOT NULL
ORDER BY `time` ASC";
$result = mysql_query($query);
$tempsMM = array();
$timesMM = array();
while ($latest = (mysql_fetch_assoc($result)))
{
  array_push($timesMM, $latest["time"]);
  array_push($tempsMM, $latest["Temperature"]);
}

// Setup the graph
function TimeCallback($aVal) {
    return date('H:i', $aVal);
}

$graph->SetScale('datint');
$graph->SetMarginColor('white');
$graph->title->Set('Temperature');
if(date("j")-1 != 0){
				#mens doen dit nie so nie, maar dis makliker. gebruik strtotime("-1 days").
	$graph->subtitle->Set((date("j")-1)." - ".date("j F Y"));
}
else{
	$graph->subtitle->Set(date("d F Y", strtotime("-1 days"))." - ".date("j F Y"));
}

// Fonts
$graph->SetUserFont('msttcorefonts/verdanab.ttf');
$graph->title->SetFont(FF_USERFONT,FS_NORMAL,12);
#$graph->SetUserFont2('msttcorefonts/verdana.ttf');
#$graph->subtitle->SetFont(FF_USERFONT2,FS_NORMAL,8);
#$graph->xaxis->SetFont(FF_USERFONT2,FS_NORMAL,8);
#$graph->yaxis->SetFont(FF_USERFONT2,FS_NORMAL,8);
#$graph->yaxis->title->SetFont(FF_USERFONT2,FS_NORMAL,8);
 
// Create a simple line
if(count($timesMM) > 0){
	$l3 = new LinePlot($tempsMM, $timesMM);
	$l3->SetColor('#CCCC66');
	$l3->SetWeight(3);
	$l3->SetLegend('S-B');
	$graph->Add($l3);
}

//eergisteraand - is dit nodig?
$band = new PlotBand(VERTICAL,BAND_SOLID,
	strtotime(date("j F Y ",strtotime("-2 days")).date_sunset(strtotime("-2 days"), SUNFUNCS_RET_STRING, -33.928, 18.866, 90+50/60, 2)),
	strtotime(date("j F Y ",strtotime("-1 days")).date_sunrise(strtotime("-1 days"), SUNFUNCS_RET_STRING, -33.928, 18.866, 90+50/60, 2)),
	'gray5@0.85');
$band->ShowFrame(false); // No border around the plot band
$graph->Add($band);

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
$band->ShowFrame(false); // No border around the plot band
$graph->Add($band);

// Setup X-axis labels
#$graph->SetScale('datint',0,0,0,1440);
#$graph->xaxis->SetTickLabels($times);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->SetLabelFormatCallback('TimeCallback');
$graph->xaxis->scale->ticks->Set(3600,3600);
$graph->xaxis->SetPos('min');
$graph->SetTickDensity(TICKD_DENSE, TICKD_NORMAL);

$graph->xgrid->Show(true,false);
$graph->legend->SetPos(0.02,0.02,'right','top');

// Y-axis units
$graph->yaxis->title->Set("Degree Celsius (\xB0C)");

if(count($timesEE) > 0 or count($timesMM) > 0 or count($timesSB) > 0){
	// Send back graph to client
	$graph->Stroke();
}
else{
	echo "Sorry, but there is currently no data available for the graph.";
}

?>
