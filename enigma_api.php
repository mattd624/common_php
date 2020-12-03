<?php

//////////////////////////   ENIGMA FUNCTIONS   //////////////////////////////////


function CallAPI($url) {
//Used for Enigma API...only does GET calls, not POST,
//but still is used to edit things in Enigma, depending on $url
  $ch = curl_init();
  $options = array(
    CURLOPT_URL            => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER         => false,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING       => "",
    CURLOPT_AUTOREFERER    => true,
    CURLOPT_CONNECTTIMEOUT => 120,
    CURLOPT_TIMEOUT        => 120,
    CURLOPT_MAXREDIRS      => 10,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_HTTPAUTH       => CURLAUTH_ANY,
    CURLOPT_USERNAME       => ENIG_API_USER,
    CURLOPT_PASSWORD       => ENIG_API_PW,
    CURLOPT_RETURNTRANSFER => 1,
);
  curl_setopt_array( $ch, $options );
  $response = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code

  if ( $httpCode != 200 ){
    $output = $httpCode . curl_error($ch);
  } else {
    $output = $response;
  }
  curl_close($ch);


    return $output;
}


function get_data($table, array $select_arr = [], array $where_arr = [], $limit = null) {

/*
call to Enigma API to get data. Works for simple statements but if you need a complex where clause or something, you will have to build your own query.
input	$table = table available for querying in Enigma API  Example: 'hst'
	$select_arr = array of strings corresponding to columns in the table.  Exmaple: ['hst_namea','hst_ip','hst_conn_comment']
	$where_arr = array like this, where the first one lacks a conjunction:	[
					  0 => ['site_code', 'LIKE', 'aosn'],
					  1 => ['AND', 'hst_ip','LIKE','10.10.'],
                      2 => ['OR', 'hst_dsc','LIKE','-ap-']
					];
*/
  global $api_url;
  global $function;
  $function = __FUNCTION__;
                                                                                        heavylog("FUNCTION $function CALLED!");
  $action   = '?action=get_data';
  $select = '&select=' . $table . '_id,' . implode(',',$select_arr) . '&from=' . $table;
  $where = '';
  if (!empty($where_arr)) {
    foreach ($where_arr as $idx => $wh) {
      if ($idx == 0) {
        $where = '&where=';
        $conj_str = '';
        array_unshift($wh,'');
      } else {
        $conj = !empty($wh[0]) ? $wh[0] : 'AND';
        $conj_str = '+' . $conj . '+';
      }
//print_r($wh);
      switch ($wh) {
        case (preg_match('/.*LIKE.*/', strtoupper($wh[2]))):
          $where = $where . $conj_str . $wh[1].'+' . $wh[2] . '+%27%25' . $wh[3] . '%25%27';
          break;
        case (preg_match('/(<=|>=|[\<\>])/', $wh[3]) ? true : false):
          $where = $where . $conj_str . $wh[1].'+' . $wh[2] . $wh[3]; 
          break;
        default:
          $where = $where . $conj_str . $wh[1].'+' . $wh[2] . '+%27' . $wh[3] . '%27';
      }
    }
    $where = str_replace(' ','+',$where);
  }
  
  $limit_str = !empty($limit) ? '&limit=' . $limit : null;
  $url = "$api_url$action$select$where$limit_str";
					                                				print_r("\n$url\n");
					                                				writelog("$url");
  $result = CallAPI($api_url.$action.$select.$where.$limit_str);
  $result_arr = json_decode(str_replace("},\n]", "}\n]", $result));
print_r("\nresult_arr[0]->{table.'_id'}: ");
print_r($result_arr[0]->{$table . '_id'});
  if (!isset($result_arr[0]->{$table . '_id'}))						writelog("\nFailed to get data from table: $table\n");
  if (!isset($result_arr[0]->{$table . '_id'}))						writelog("$result_arr");
  $function = '';
  return isset($result_arr) ? $result_arr : 0;
}


function object_action($action, $sf_obj) {
  /*
inputs: $action = string that corresponds to either modify_site, add_site, add_node, modify_node, or delete_node, etc. api actions in Enigma (site and node being examples of "objects" in Enigma). 
        $sf_obj = standard class object with properties that conform to object property names in Enigma and are perhaps translated from Salesforce values
This function assumes the object was found using get_data()
*/

                                                                                        heavylog("FUNCTION " . __FUNCTION__ . " CALLED WITH $action ACTION!");
  global $api_url;
  global $function;
  global $protected_patterns;
  $protected_patterns = ['/template/','/^10\.99\.99\./'];
  $new_stuff = ['new_node_ip','new_node_name'];
  foreach ($protected_patterns as $p) {
    foreach ($new_stuff as $n) {
      if (($action !== 'add_site') AND (isset($sf_obj->$n)) AND (preg_match($p, $sf_obj->$n))) {
        print_r("\nSkipping protected node");
        return 0;
      }
    }
  }  
  $function = __FUNCTION__;

  $url_arr = [];
  $url_arr[] = $api_url . '?action=' . $action;
  foreach ($sf_obj as $param_name => $param_val) {
    $url_arr[] = '&' . $param_name . '=' . str_replace(" ", "+", $param_val);
  }
                                                                                        writelog("url_arr:");
                                                                                        writelog($url_arr);
  $params_missing_arr = [];
  foreach ($sf_obj as $param_name => $param_val) {
    if (empty($param_val)) $params_missing_arr[] = $param_name;
  }
                                                                                        writelog("params_missing_arr:");
                                                                                        writelog($params_missing_arr);
  if (empty($params_missing_arr)) {
    $url = implode('', $url_arr);
                                                                                        writelog("\nurl:");
                                                                                        writelog("\n$url");
    $result = CallAPI($url);
  } else {
    $msg = "ERROR - One or more required parameters was not found: ";
    $msg2 = implode(", ", $params_missing_arr);
                                                                                        writelog("\n\n$msg\n$msg2");
  }
  $function = '';
  return isset($result) ? $result : 0;
}




/*
require_once (__DIR__ . '/creds.php'); 
require_once (__DIR__ . '/writelog.php'); 
$api_url = 'https://enig-01.unwiredbb.net/cgi-bin/protected/manage_api.cgi';
$log_dir = '/log/';

$table		= 'site';
$select_arr	= ['site_name','site_code'];
$where_arr	= [['site_id','<',40],['AND','hst_ip','LIKE','10.10.194.'],['OR','hst_ip','LIKE','10.10.195.']];
$limit 		= 10;

$data = get_data($table, $select_arr);
print_r($data);
*/





