<?php

function write_exec_log($file_path) {
  $tmstmp = date('D, F d, G:i:s');
  file_put_contents('/var/log/execution.log', print_r("$tmstmp  >>  " . $file_path . "\n", true), FILE_APPEND);
} 


