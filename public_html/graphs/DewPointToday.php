<?php
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_line.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_date.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_plotband.php');

header("Content-type: image/png");

$user="weatherclient";
$password="REDACTED";
$database="weather";
mysql_connect('localhost',$user,$password);
mysql_select_db($database);

$query = "SELECT time,dew FROM WeatherMM WHERE date = date(NOW())";
//$query = "SELECT time,tempout FROM WeatherMM WHERE 1 ORDER BY date DESC LIMIT 1440";
$result = mysql_query($query);
$dewMM = array();
while ($latest = (mysql_fetch_assoc($result))){
	#array_push($times, substr($latest["time"],0,5));
	array_push($dewMM, $latest["dew"]);
}
$query = "SELECT time,dew FROM WeatherEE WHERE date = date(NOW())";
//$query = "SELECT time,tempout FROM WeatherEE WHERE 1 ORDER BY date DESC LIMIT 1440";
$result = mysql_query($query);
$dewEE = array();
while ($latest = (mysql_fetch_assoc($result))){
	#array_push($times, substr($latest["time"],0,5));
	array_push($dewEE, $latest["dew"]);
}

$times = array();
$minute = 0;
for ($hour = 0; $hour<=23; ){
	if ($minute == 60){ 
		$minute = 0;
		$hour++;
	}
	$tyd = $hour.":".(($minute<10)?"0".$minute:$minute);
	array_push($times, $tyd);
	$minute++;
}

// Setup the graph
$graph = new Graph(500,300);
#$graph->SetScale('linlin');
$graph->SetMarginColor('white');
$graph->title->Set('Dew Point');
$graph->subtitle->Set(date("l").", ".date("j")." ".date("F")." ".date("Y"));
 
if(count($dewMM)){
	// Create a simple line
	$l1 = new LinePlot($dewMM);
	$l1->SetColor('blue@0.4');
	$l1->SetWeight(3);
	$l1->SetLegend('M&M');
	$graph->Add($l1);
}

if(count($dewEE)){
	$l2 = new LinePlot($dewEE);
	$l2->SetColor('red@0.4');
	$l2->SetWeight(3);
	$l2->SetLegend('E&E');
	$graph->Add($l2);
}

$band = new PlotBand(VERTICAL,BAND_SOLID,0,360,'gray5@0.85');
$band->ShowFrame(false); // No border around the plot band
$graph->Add($band);

$band = new PlotBand(VERTICAL,BAND_SOLID,1080,1440,'gray5@0.85');
$band->ShowFrame(false); // No border around the plot band
$graph->Add($band);

// Setup X-axis labels
$graph->SetScale('datint',0,0,0,1440);
$graph->xaxis->SetPos("min");
$graph->xaxis->SetTickLabels($times);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->scale->ticks->Set(60,30);
$graph->SetTickDensity(TICKD_DENSE, TICKD_NORMAL);

$graph->xgrid->Show(true,false);
$graph->legend->SetPos(0.02,0.02,'right','top');

// Y-axis units
$graph->yaxis->title->Set("Degree Celsius (\xB0C)");

// Send back graph to client
$graph->Stroke();

?>
