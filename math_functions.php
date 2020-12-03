<?php

function quarter_rnd(float $float) {
//quarter rounding, like 5.68323 -> 5.75
  if (($float !== 0) and is_float($float)) {
    $a = $float * 4;
    $b = ceil($a);
    $c = $b / 4;
    return number_format($c,2); // number_format is for putting decimal places to static number
  } else {
    return null;
  }
}

