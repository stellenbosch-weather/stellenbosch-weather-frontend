<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

// http://stackoverflow.com/questions/1560075/zip-files-get-corrupted-by-ie
// apache_setenv('no-gzip', '1'); //anders word die zip se headers corrupt deur IE7

# Start timer
$time_start = microtime_float();


## Static Definitions ##########################################################


// Available Files
$available = array(
            'noaa_month' => '../weatherlink/noaa_reports/NOAAMO.TXT',
            'noaa_prev_month' => '../weatherlink/noaa_reports/NOAAPRMO.TXT',
            'noaa_year' => '../weatherlink/noaa_reports/NOAAYR.TXT',
            'noaa_prev_year' => '../weatherlink/noaa_reports/NOAAPRYR.TXT',
            'detail_2_day' => '../weatherlink/noaa_reports/downld02.txt',
            'detail_7_day' => '../weatherlink/noaa_reports/downld08.txt',
            'mm_2008' => '../weatherlink/noaa_reports/all 2008.txt',
            'mm_2009' => '../weatherlink/noaa_reports/all 2009.txt',
            'mm_2010' => '../weatherlink/noaa_reports/all 2010.txt',
            'mm_2011' => '../weatherlink/noaa_reports/all 2011.txt',
            'ee_2010' => '../weatherlinkEE/all 2010 ee.txt',
            'ee_2011' => '../weatherlinkEE/all 2011 ee.txt',
            );

// Available Tables
$tables = array(
            'mm' => 'WeatherMM',
            'mr' => 'Weather_mmroof',
            'ee' => 'WeatherEE',
            'sb_tdaily' => 'SB_TDaily',
            'sb_thour' => 'SB_THour',
            'sb_tmin' => 'SB_TMin',
            'sb_thermo' => 'SB_Thermocouple'
            );
            
// Columns that may be downloaded
$columns = array(
            'WeatherMM' => array('ID','station','date','time','tempout','hitemp',
                        'lowtemp','outhum','dew','windspeed','direction',
                        'windrun','hispeed','hidir','windchill','heatindex',
                        'THW','THSWindex','bar','rain','rainrate','solarrad',
                        'solarenergy','hisolarrad','UV','UVdose','hiUV',
                        'heatDD','coolDD','intemp','inhum','indew','inheat',
                        'ET','windsamp','windtx','ISSrecept','arcint'),
            'Weather_mmroof' => array('ID','station','date','time','tempout','hitemp',
                        'lowtemp','outhum','dew','windspeed','direction',
                        'windrun','hispeed','hidir','windchill','heatindex',
                        'THWindex','bar','rain','rainrate','heatDD','coolDD',
                        'intemp','inhum','indew','inheat','inemc',
                        'inairdensity','windsamp','windtx','ISSrecept','arcint'),
            'WeatherEE' => array('ID','station','date','time','tempout','hitemp',
                        'lowtemp','outhum','dew','windspeed','direction',
                        'windrun','hispeed','hidir','windchill','heatindex',
                        'THWindex','bar','rain','rainrate','heatDD','coolDD',
                        'intemp','inhum','indew','inheat','inemc',
                        'inairdensity','windsamp','windtx','ISSrecept','arcint'),
            'SB_TDaily' => array('Date','Time','TimeStamp','Record',
                        'TrackerKJ_Tot',/*'Tracker2KJ_Tot',*/'ShadowKJ_Tot',
                        'SunKJ_Tot','TrackerWM_Max','TrackerWM_TMx',
                        /*'Tracker2WM_Max','Tracker2WM_TMx',*/'ShadowWM_Max',
                        'ShadowWM_TMx','SunWM_Max','SunWM_TMx'/*,'BattV_Min'*/,
                        'AirTC_Max','AirTC_Min','RH_Max','RH_Min','WS_ms_S_WVT',
                        'WindDir_D1_WVT','WindDir_SD1_WVT','BP_mB_Max','BP_mB_Min'),
            'SB_THour' => array('Date','Time','TimeStamp','Record'/*,'BattV_Min'*/,
                        'TrackerWM_Avg',/*'Tracker2WM_Avg',*/'ShadowWM_Avg',
                        'SunWM_Avg','ShadowbandWM_Avg','AirTC_Avg','AirTC_Max','AirTC_Min','RH',
                        'WS_ms_S_WVT','WindDir_D1_WVT','WindDir_SD1_WVT','BP_mB_Avg',
                        'UVA_Avg','UVB_Avg'),
            'SB_TMin' => array('Date','Time','TimeStamp','Record',
                        'TrackerWM_Avg',/*'Tracker2WM_Avg',*/'ShadowWM_Avg','SunWM_Avg',
                        'ShadowbandWM_Avg','AirTC_Avg','RH','WS_ms_S_WVT',
                        'WindDir_D1_WVT','WindDir_SD1_WVT','BP_mB_Avg','UVA_Avg',
                        'UVB_Avg'),
            'SB_Thermocouple' => array('Date','Time','TimeStamp','Record','Temp_TypeT_Avg')
            );



## Functions ###################################################################

# Timer
function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

/* print the contents of a url */
function print_r_xml($arr,$wrapper = 'data',$cycle = 1)
{
  //useful vars
  $new_line = "\n";

  //start building content
  if($cycle == 1) { $output = '<?xml version="1.0" encoding="UTF-8" ?>'.$new_line; }
  $output.= tabify($cycle - 1).'<'.$wrapper.'>'.$new_line;
  foreach($arr as $key => $val)
  {
    if(!is_array($val))
    {
      $output.= tabify($cycle).'<'.htmlspecialchars($key).'>'.$val.'</'.htmlspecialchars($key).'>'.$new_line;
    }
    else
    {
      $output.= print_r_xml($val,$key,$cycle + 1).$new_line;
    }
  }
  $output.= tabify($cycle - 1).'</'.$wrapper.'>';

  //return the value
  return $output;
}

/* tabify */
function tabify($num_tabs)
{
  for($x = 1; $x <= $num_tabs; $x++) { $return.= "\t"; }
  return $return;
}

//http://www.linuxjournal.com/article/9585
/**
Validate an email address.
Provide email address (raw input)
Returns true if the email address has the email 
address format and the domain exists.
*/
function validEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if
(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}


/* Create a csv string from a table name */
 function tableToCSV($dbConnection, $tableName, $startDate, $endDate)
 {
    global $columns;

    $out = '';

    # Headings
    $query = "SHOW COLUMNS FROM `".$tableName."`";
    $result = mysql_query($query, $dbConnection);
    //echo ($tableName);
    //var_dump($columns[$tableName]);
    #$l = mysql_fetch_array($result);
	while ($l = mysql_fetch_array($result)) {
	    //if(in_array($l[0],$columns[$tableName]))
	    //{
	        $out .=''.$l[0].',';
	    //}
	}
	$out = rtrim($out,',');
    $out .="\n";

    # Entries
	$query="SELECT ".$out." FROM `".$tableName."` WHERE `date` >= FROM_UNIXTIME('".$startDate."') AND `date` <= FROM_UNIXTIME('".$endDate."') ORDER BY `date`,`time`";
	//echo $query;
	$result = mysql_query($query, $dbConnection);
	$columns = mysql_num_fields($result);
 
	// Add all values in the table
	while ($l = mysql_fetch_array($result)) {
	    for ($i = 0; $i < $columns; $i++) {
	        $out .=''.$l["$i"].',';
	    }
	    $out = rtrim($out,',');
        $out .="\n";
	}
    return $out;
 }


## Main Program ################################################################


if(!isset($_POST["agree"]) or $_POST["agree"] != 'yes')
{
    echo("You need to accept the agreement to be able to download the data.");
    die;
}

if(!isset($_POST['email']) or ($_POST['email']) == '' or !validEmail($_POST['email']))
{
    echo "You need to specify a valid email address.";
    die;
}

//Read filelist
if(isset($_POST['files']))
{
    $files = $_POST['files'];
}

if(isset($_POST['detail']))
{
    $details = $_POST['detail'];

    if(!isset($_POST['startdate']) or $_POST['startdate'] == '' 
        or !( $startdate = strtotime($_POST['startdate']) ) )
    {
        echo "You need to specify a valid start date.";
        die;
    }
    if(!isset($_POST['enddate']) or $_POST['enddate'] == '' 
        or !( $enddate = strtotime($_POST['enddate']) ) )
    {
        echo "You need to specify a valid end date.";
        die;
    }
}

if(!isset($details) and !isset($files))
{
    echo "Nothing selected to download.";
    die;
}


include "../include/DatabaseConnector.php";
$dbConnector = new DatabaseConnector();
$dbConnection = $dbConnector::getConnection();


// Prepare File
$file = tempnam("tmp", "zip");
$zip = new ZipArchive();
#$zip->open($file, ZipArchive::OVERWRITE);

$opened = $zip->open( $file, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE );
if( $opened !== true ){
    die("cannot open {$file} for writing.");
}
    
// Staff with content
// $zip->addFromString('file_name_within_archive.ext', $your_string_data);
// $zip->addFile('file_on_server.ext', 'second_file_name_within_archive.ext');

$zip->addFile('../ODC_PDDL.pdf', 'LICENCE.pdf'); //file 1 that you want compressed

if(isset($files))
{
    foreach ($files as $thisOne)
    {
        if(isset($available[$thisOne]))
        {
            $zip->addFile($available[$thisOne], $thisOne.'.txt');
        }
    }
}

if(isset($details))
{
    foreach ($details as $thisOne)
    {
        if(isset($tables[$thisOne]))
        {
            $data = tableToCSV($dbConnection, $tables[$thisOne], $startdate, $enddate);
            $zip->addFromString($tables[$thisOne].'.csv', $data);
        }

    }
}

// Close and send to users
$result = $zip->close();

if ($result !== true)
{
    echo "An error occured. Please try again later or contact web@jpmeijers.com";
    die;
}

header('Content-Type: application/zip');
header('Content-Length: ' . filesize($file));
header('Content-Disposition: attachment; filename="WeatherData.zip"');
readfile($file);
unlink($file); 

$postData = print_r_xml($_POST);

$time_end = microtime_float();
$time = $time_end - $time_start;
//do something with time
include ("log_download.php");
logDownload($dbConnection, $time, $postData, $_POST['email']);

?>
