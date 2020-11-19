<?php

///for Enterprise WSDL, not Partner

function sf_case_comment($case_id,$msg) {
/*
case_id = Case id;
msg = message to put in comment
*/

  $sObject = new stdClass();
  $sObject->ParentId = $case_id;
  $sObject->CommentBody = $msg;
  return $sObject;
}

function sf_uncheck($id,$field){
/*
id = a salesforce object id
field = checkbox (field) to uncheck
*/

  $sObject_arr = [];
  $sObject_arr['Id'] = $id;
  $sObject_arr[$field] = 'false';
  $sObject = (object) $sObject_arr;
  return $sObject;
}

function sf_obj_update($obj_id, $field_name, $field_data = null) {
/*Required fields:
obj_id = a salesforce object id
field_name = name of field to be updated in Salesforce
field_data = data to update in Salesforce
 */
	$sObject = new stdClass();
	$sObject->Id = $obj_id;
	$sObject->{$field_name} = $field_data;
	  
  return $sObject;
}
/*

$test = sf_obj_update('IDlkjhasdfkjh','field_name','field_data');
print_r($test);
*/
