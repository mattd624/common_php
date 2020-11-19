<?php
    $ip = '10.7.1.47';
    $ip_is_valid = filter_var($ip, FILTER_VALIDATE_IP);
    if (!$ip_is_valid) {
      echo "IP NOT VALID";
    } else {
      echo "IP VALID"; 
    } 
    
