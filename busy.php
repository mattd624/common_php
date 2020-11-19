<?php
function check_busy() {
  $busy = file_get_contents('busy');
  return $busy;
}

function check_times() {
  $times = file_get_contents('times');
  return $times;
}

function set_busy($setting) {
  file_put_contents('busy', $setting);
}

function set_times($setting) {
  file_put_contents('times', $setting);
}
//set_baton(1);
//print_r(check_baton());
//set_baton(0);
//print_r(check_baton());

