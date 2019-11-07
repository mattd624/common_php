<?php

function get_snmp_data($ip, $oid, $ver = 2) {
  /*
  Input: ip = IP address ; oids = array of snmp, type = Expected output type (INTEGER, STRING, etc.) in SNMP response
  Expected global: SNMP community string for SNMP authentication
  */
  global $snmp_community_str;
  $types = '(integer|string|gauge|ipaddress|counter|octetstring)(32|64)?'; //part of the preg_match pattern
  if ($ver == 1) $cmd = 'snmpget';
  if ($ver == 2) $cmd = 'snmp2_get';
writelog("\n\nINPUTS TO SNMP FUNCTION:");
writelog("\nip: $ip");
writelog("\noid: $oid");
    $snmp_resp = call_user_func($cmd, $ip, $snmp_community_str, $oid, 50000, 2);
//writelog("\nsnmp_response: ");
writelog($snmp_resp);
    if (preg_match("/$types: (.*)/i", $snmp_resp, $rtn_val)) { // /i means ignore case
//writelog($rtn_val[3]); // Why 3? Answer: First 2 values are in $types
    }
  if (isset($rtn_val[3])) return $rtn_val[3];
  return 0;
}


function set_snmp_val($ip, string $oid, $value, $type = 'i', $ver = 2) { // i means integer
  /*
  Input: ip = IP address ; oids = snmp oid; value = value to set;  type = type of value (i,g,s,etc. for integer, gauge, string)
  Expected global: SNMP community string for SNMP authentication
  */
  global $attempt_ct;
  global $snmp_community_str;
  if ($ver == 2) $cmd = 'snmp2_set';
  if ($ver == 1) $cmd = 'snmpset';
writelog("\n\nINPUTS TO SNMP SET:");
writelog("\nip: $ip");
writelog("\noid: $oid");
writelog("\nvalue: $value");
  try {
    $attempt_ct++;
    $snmp_resp = call_user_func($cmd, $ip , $snmp_community_str , $oid , $type , $value , $timeout = 100000 , 2);
  } catch (exception $e) {
    print_r("Attempts: " . $attempt_ct);
  }
  return $snmp_resp;
}

