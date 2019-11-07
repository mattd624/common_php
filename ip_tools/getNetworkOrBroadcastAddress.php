#!/usr/bin/php
<?php


//Get subnet address or broadcast address from IP address / subnet mask



//INPUT EXAMPLE
//$ip = '70.71.72.73';
//$subnet_mask = '255.255.255.0';



//FUNCTIONS

function getNetworkAddress($ip,$subnet_mask) {
  $input = new stdClass();
  $input->ip = $ip;
  $input->netmask = $subnet_mask;
  $input->ip_int = ip2long($input->ip);
  $input->netmask_int = ip2long($input->netmask);
  $input->network_int = $input->ip_int & $input->netmask_int;
  $input->network = long2ip($input->network_int);

  return $input->network;
}


function getBroadcastAddress($ip,$subnet_mask) {
  $input = new stdClass();
  $input->ip = $ip;
  $input->netmask = $subnet_mask;
  $input->ip_int = ip2long($input->ip);
  $input->netmask_int = ip2long($input->netmask);
//  $input->broadcast_int = $input->ip_int & $input->netmask_int;
  $input->broadcast_int = $input->ip_int | (~ $input->netmask_int);
  $input->broadcast = long2ip($input->broadcast_int);

  return $input->broadcast;
}


//EXEC
/*
$net_addr = getnetworkaddress($ip,$subnet_mask);
$bcast_addr = getBroadcastAddress($ip,$subnet_mask);

print_r("\nNetwork Address of $ip with subnet mask $subnet_mask : ");
print_r($net_addr);
print_r("\nBroadcast Address of $ip with subnet mask $subnet_mask : ");
print_r($bcast_addr);
print_r("\n");
*/
