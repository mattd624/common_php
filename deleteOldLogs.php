<?php

function deleteOldLogs($dir, $days_old) {
  $files = glob($dir . "*");
//  print_r($files);

  $now   = time();

  foreach ($files as $file) {
    if (is_file($file)) {
      if ($now - filemtime($file) > 60 * 60 * 24 * $days_old) { // 20  days
        unlink($file);
      }
    }
  }
}
