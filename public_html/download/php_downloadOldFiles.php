<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
# Start timer
$time_start = microtime_float();

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

################################################################################

//Available Files
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

//Read filelist
if(!isset($_POST['files']))
{
    echo("You didn't select any files to download.");
    die;
}
else
{
    $files = $_POST['files'];
}

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




// Prepare File
$file = tempnam("tmp", "zip");
$zip = new ZipArchive();
$zip->open($file, ZipArchive::OVERWRITE);

// Staff with content
//$zip->addFromString('file_name_within_archive.ext', $your_string_data);
//$zip->addFile('file_on_server.ext', 'second_file_name_within_archive.ext');

$zip->addFile('../ODC_PDDL.pdf', 'LICENCE.pdf'); //file 1 that you want compressed

foreach ($files as $thisOne)
{
    if(isset($available[$thisOne]))
    {
        $zip->addFile($available[$thisOne], $thisOne.'.txt');
    }
}

// Close and send to users
$zip->close();
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
logDownload($time, $postData, $_POST['email']);

?>
