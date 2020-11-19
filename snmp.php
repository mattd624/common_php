<?php





///////NOTE   if snmp is not responding, make sure the SNMP credentials file is loaded






function snmp_search($ip, $keyword, $start_oid = '', $ver = 2) {
  error_reporting(E_ALL & ~(E_NOTICE|E_WARNING));
  $fn_name = 'snmp_search';
  /*
  Input: ip = IP address ; start_oid = OID of subtree to walk, type = Expected output type (INTEGER, STRING, etc.) in SNMP response
  Expected global: SNMP community string for SNMP authentication
  */
  global $snmp_community_str;
  $types = '(integer|string|gauge|ipaddress|counter|octetstring|hex-string|oid)(32|64)?'; //part of the preg_match pattern
  if ($ver == 1) $cmd = 'snmprealwalk';
  if ($ver == 2) $cmd = 'snmp2_real_walk';
                                                                                        heavylog("\n\n$fn_name: INPUTS TO SNMP FUNCTION:");
                                                                                        heavylog("\n$fn_name: ip: $ip");
                                                                                        heavylog("\n$fn_name: start_oid: $start_oid");
    if ($snmp_resp_arr = call_user_func($cmd, $ip, $snmp_community_str, $start_oid, 200000, 2)) {
                                                                                        heavylog("\n$fn_name: SNMP_RESP:");
                                                                                        heavylog($snmp_resp_arr);
      $oid = array_search($keyword, $snmp_resp_arr);
      preg_match("/$types: (.*)/i", current($snmp_resp_arr), $rtn_val);  // /i means ignore case
                                                                                        heavylog("\n$fn_name: rtn_val[1]: ");
                                                                                        heavylog($rtn_val[1]); // Why 3? Answer: First 2 values are in $types
    }
    error_reporting(E_ALL & ~(E_NOTICE|E_WARNING));
  if (isset($rtn_val[1])) return $oid;
  return 0;
}


function get_snmp_data($ip, $oid, $ver = 2) {
  $fn_name = 'get_snmp_data';
  /*
  Input: ip = IP address ; oid = snmp OID for data being requested, type = Expected output type (INTEGER, STRING, etc.) in SNMP response
  Expected global: SNMP community string for SNMP authentication
  */
  global $snmp_community_str;
  $types = '(integer|string|gauge|ipaddress|counter|octetstring|hex-string|oid)(32|64)?'; //part of the preg_match pattern
  if ($ver == 1) $cmd = 'snmpget';
  if ($ver == 2) $cmd = 'snmp2_get';
                                                                                        heavylog("\n\n$fn_name: INPUTS TO SNMP FUNCTION:");
                                                                                        heavylog("\n$fn_name: ip: $ip");
                                                                                        heavylog("\n$fn_name: oid: $oid");
  snmp_set_valueretrieval ( $method = SNMP_VALUE_LIBRARY );
  snmp_set_oid_output_format ( $oid_format = SNMP_OID_OUTPUT_MODULE );
  snmp_set_quick_print (0);
 
 
  
  $snmp_resp = call_user_func($cmd, $ip, $snmp_community_str, $oid, 1000000, 2);
                                                                                        heavylog("\n$fn_name: snmp_response: ");
                                                                                        heavylog($snmp_resp);
      if (preg_match("/$types: (.*)/i", $snmp_resp, $rtn_val)) { // /i means ignore case
                                                                                        heavylog($rtn_val[3]); // Why 3? Answer: First 2 values are in $types
      }    
  if (isset($rtn_val[3])) return $rtn_val[3];
  return 0;
}


function set_snmp_val($ip, string $oid, $value, $type = 'i', $ver = 2) { // i means integer
  $fn_name = 'set_snmp_val';
  /*
  Input: ip = IP address ; oids = snmp oid for value to be set; value = value to set;  type = type of value (i,g,s,etc. for integer, gauge, string)
  Expected global: SNMP community string for SNMP authentication
  */
  global $attempt_ct;
  global $snmp_community_str;
  if ($ver == 2) $cmd = 'snmp2_set';
  if ($ver == 1) $cmd = 'snmpset';
                                                                                        heavylog("\n\n$fn_name: INPUTS TO SNMP SET:");
                                                                                        heavylog("\n$fn_name: ip: $ip");
                                                                                        heavylog("\n$fn_name: oid: $oid");
                                                                                        heavylog("\n$fn_name: value: $value");
  try {
    $attempt_ct++;
    $snmp_resp = call_user_func($cmd, $ip , $snmp_community_str , $oid , $type , $value , $timeout = 100000 , 2);
  } catch (exception $e) {
                                                                                        writelog("\nException: $e");
                                                                                        writelog("\nAttempts: " . $attempt_ct);
  }
  return $snmp_resp;
}

function fix_mac_addr_missing_zeroes ($mac_str) {
  $correct_mac_pattern = '/[0-9A-Fa-f]{2}:[0-9A-Fa-f]{2}:[0-9A-Fa-f]{2}:[0-9A-Fa-f]{2}:[0-9A-Fa-f]{2}:[0-9A-Fa-f]{2}/';
  $missing_0_pattern = '/[0-9A-Fa-f]{1,2}:[0-9A-Fa-f]{1,2}:[0-9A-Fa-f]{1,2}:[0-9A-Fa-f]{1,2}:[0-9A-Fa-f]{1,2}:[0-9A-Fa-f]{1,2}/';
  $fixed_mac = '';
  if (!preg_match($correct_mac_pattern, $mac_str)) {
    if (preg_match($missing_0_pattern, $mac_str)) {
      $mac_arr = explode(':', $mac_str);
      foreach ($mac_arr as $idx => $e) {
        $mac_arr[$idx] = str_pad($e, 2, '0', STR_PAD_LEFT);
      }
      return implode(':',$mac_arr);
    }
  }
  return $mac_str;
} 
