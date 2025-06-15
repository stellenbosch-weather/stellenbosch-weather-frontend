<?php
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_line.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_date.php');
require_once('/home/weather/public_html/sources/jpgraph/src/jpgraph_plotband.php');

$user="weatherclient";
$password="REDACTED";
$database="weather";
mysql_connect('localhost',$user,$password);
mysql_select_db($database);

$query = "SELECT time,heatindex FROM WeatherMM WHERE date = date(NOW())";
$result = mysql_query($query);
$heatindexMM = array();
while ($latest = (mysql_fetch_assoc($result))){
	array_push($heatindexMM, $latest["heatindex"]);
}
$query = "SELECT time,heatindex FROM WeatherEE WHERE date = date(NOW())";
$result = mysql_query($query);
$heatindexEE = array();
while ($latest = (mysql_fetch_assoc($result))){
	array_push($heatindexEE, $latest["heatindex"]);
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
$graph->SetMarginColor('white');
$graph->title->Set('Heat Index');
$graph->subtitle->Set(date("l").", ".date("j")." ".date("F")." ".date("Y"));
 
if(count($heatindexMM)){
	// Create a simple line for MM
	$l1 = new LinePlot($heatindexMM);
	$l1->SetColor('blue@0.4');
	$l1->SetWeight(3);
	$l1->SetLegend('M&M');
	$graph->Add($l1);
}

if(count($heatindexEE)){
	// Create a simple line for EE
	$l2 = new LinePlot($heatindexEE);
	$l2->SetColor('red@0.4');
	$l2->SetWeight(3);
	$l2->SetLegend('E&E');
	$graph->Add($l2);
}

// Make the background for nighttime darker
$band = new PlotBand(VERTICAL,BAND_SOLID,0,360,'gray5@0.85');
$band->ShowFrame(false); // No border around the plot band
$graph->Add($band);

$band = new PlotBand(VERTICAL,BAND_SOLID,1080,1440,'gray5@0.85');
$band->ShowFrame(false); // No border around the plot band
$graph->Add($band);

// Setup X-axis labels
$graph->SetScale('datint',0,0,0,1440);
$graph->xaxis->SetTickLabels($times);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->scale->ticks->Set(60,30);
$graph->SetTickDensity(TICKD_DENSE, TICKD_NORMAL);

// Add vertical gridlines
$graph->xgrid->Show(true,false);

//Add a legend
$graph->legend->SetPos(0.02,0.02,'right','top');

// Y-axis units
$graph->yaxis->title->Set("Degree Celsius (\xB0C)");

// Send graph back to client
$graph->Stroke();

?>
