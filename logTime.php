<?php 
//Logs the date and time
date_default_timezone_set('America/Los_Angeles');

function log_time() {
/*
..........depends on log_writelog() in writelog.php
*/
  $tmstmp = "\n" . date('D, \d\a\y d \o\f F, G:i:s');
  writelog("\n" . $tmstmp . "\n\n");
}

