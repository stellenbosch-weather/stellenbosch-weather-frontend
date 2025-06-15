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

$query = "SELECT time,bar FROM WeatherMM WHERE date = date(NOW())";
//$query = "SELECT time,tempout FROM WeatherMM WHERE 1 ORDER BY date DESC LIMIT 1440";
$result = mysql_query($query);
$barMM = array();
while ($latest = (mysql_fetch_assoc($result))){
	#array_push($times, substr($latest["time"],0,5));
	array_push($barMM, $latest["bar"]);
}
$query = "SELECT time,bar FROM WeatherEE WHERE date = date(NOW())";
//$query = "SELECT time,tempout FROM WeatherEE WHERE 1 ORDER BY date DESC LIMIT 1440";
$result = mysql_query($query);
$barEE = array();
while ($latest = (mysql_fetch_assoc($result))){
	#array_push($times, substr($latest["time"],0,5));
	array_push($barEE, $latest["bar"]);
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
$graph->title->Set('Barometric Pressure');
$graph->subtitle->Set(date("l").", ".date("j")." ".date("F")." ".date("Y"));
 
#$graph->xaxis->SetColor('darkgreen');
 
// Setup the Y-grid
#$graph->ygrid->Show(true,true);
#$graph->ygrid->SetColor('brown@0.7','gray');
#$graph->ygrid->SetWeight(2,1);
#$graph->ygrid->SetStyle('solid','dotted');
 
// Setup the X-grid
#$graph->xgrid->Show(true,true);
#$graph->xgrid->SetColor('darkgreen@0.8','gray');
#$graph->xgrid->SetWeight(2,1);
#$graph->xgrid->SetStyle('solid','dashed');
 
// Draw the grid on top to the line plot
#$graph->SetGridDepth(DEPTH_FRONT);
 
// Create a simple line
if(count($barMM) > 0){
	$l1 = new LinePlot($barMM);
	$l1->SetColor('blue@0.4');
	$l1->SetWeight(3);
	$l1->SetLegend('M&M');
	$graph->Add($l1);
}

if(count($barEE) > 0){
	$l2 = new LinePlot($barEE);
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
$graph->xaxis->SetTickLabels($times);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->scale->ticks->Set(60,30);
$graph->SetTickDensity(TICKD_DENSE, TICKD_NORMAL);

$graph->xgrid->Show(true,false);
$graph->legend->SetPos(0.02,0.02,'right','top');

// Y-axis units
$graph->yaxis->title->Set("Degree Celsius (\xB0C)");

//if(count($barEE) > 0 or count($barMM) > 0){
	// Send back graph to client
	$graph->Stroke();
//}
//else{
//	echo "Sorry, but there is currently no data available for the graph.";
//}

?>
