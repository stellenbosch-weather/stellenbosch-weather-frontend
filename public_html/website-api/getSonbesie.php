<?php
#
# Author: JP Meijers
$version = "2013-05-26";
#
#

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

## Koppel aan databasis
#include $_SERVER[ 'DOCUMENT_ROOT' ].'/include/DatabaseConnector.php';
#$databaseConnector = new DatabaseConnector();
#$dbConnection = $databaseConnector->getConnection();
//var_dump($dbConnection);

$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/../settings.conf",true);

// $dbConnection = mysql_connect($settings['database']['host'], $settings['database']['username'],$settings['database']['password']);
// mysql_select_db($settings['database']['database'], $dbConnection);

$servername = $settings['database']['host'];
$dbname = $settings['database']['database'];
$dbConnection = new PDO("mysql:host=$servername;dbname=$dbname", $settings['database']['username'], $settings['database']['password']);

$start = 0;
$end = time();

if(isset($_REQUEST['start'])) {
  $start = $_REQUEST['start'];
}
if(isset($_REQUEST['end'])) {
  $end = $_REQUEST['end'];
}


#Latest entry from Sonbesie
$stmt = $dbConnection->prepare("SELECT * FROM `SB_TMin` WHERE `TimeStamp` >= FROM_UNIXTIME(:start) AND `TimeStamp` < FROM_UNIXTIME(:end) ORDER BY TimeStamp ASC LIMIT 20000");
$stmt->execute([':start' => $start, ':end' => $end]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode($rows);

?>
