<?php


date_default_timezone_set('America/Los_Angeles');




function writelog($log) {
  global $function;
  global $log_dir;
  if(empty($log_dir)) {
    $log_dir = '/log/';
  }

/*
these lines must be in the file that uses this function:
$f_name = pathinfo(__FILE__)['basename']; // global for logging function
$f_dir = pathinfo(__FILE__)['dirname'];   // global for logging function
if (!file_exists($f_dir . $log_dir)) mkdir($f_dir . $log_dir, 0700);
*/

  global $f_name;
  global $f_dir;
  //$strip_chars_log = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F\x0D]/','',$log);
  //$log = (preg_replace('/    */','', $strip_chars_log));
  file_put_contents($f_dir . $log_dir . @date('Y-m-d') . '.log', print_r("\n" . $function . ":   ", true) , FILE_APPEND);

  if ((is_scalar($log)) and (strpos($log, "\n" ) === 0 )) {
    //preg_replace("/\n/", '', $log, 1);
    $pos = strpos($haystack = $log, $needle = "\n");
    $replace = '';
    if ($pos !== false) {
      $log = substr_replace($haystack, $replace, $pos, strlen($needle));
    }
//    file_put_contents($f_dir . $log_dir . @date('Y-m-d') . '.log', print_r($log, true), FILE_APPEND);
//  } else {
//    file_put_contents($f_dir . $log_dir . @date('Y-m-d') . '.log', print_r("\n", true), FILE_APPEND);
//    file_put_contents($f_dir . $log_dir . @date('Y-m-d') . '.log', print_r($log, true), FILE_APPEND);
  }
  file_put_contents($f_dir . $log_dir . @date('Y-m-d') . '.log', print_r($log, true), FILE_APPEND);
}


function heavylog($log) {
  global $heavy_logging;
  if ($heavy_logging) {
    writelog($log);
  echo '';
  }
}

