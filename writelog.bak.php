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
  file_put_contents($f_dir . $log_dir . @date('Y-m-d') . '.log', print_r($log, true), FILE_APPEND);

}


function heavylog($log) {
  global $function;
  global $heavy_logging;
  if ($heavy_logging) {
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
    file_put_contents($f_dir . $log_dir . @date('Y-m-d') . '.log', print_r($log, true), FILE_APPEND);
  }
}

