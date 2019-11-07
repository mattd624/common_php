<?php
include __DIR__ . '/sf_org.php';

function checkOrgID($org_id) {
//                                                                                        log_writeln("\norg_id: ");
//                                                                                        log_writeln($org_id);
  global $org_ids; 
  
  $match = 0;
  foreach ($org_ids as $id) {
    if ($org_id === $id) {
      $match = 1;
    }
  }
  if ($match === 1) {
    return 1;
//                                                                                        log_writeln("\nORG ID MATCHED");
  } else {
    return 0;
  }
}

