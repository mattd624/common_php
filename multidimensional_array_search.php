<?php




function array_values_recursive($ary)
/**
   from php.net/manual/en/function.array-values.php
   flatten an arbitrarily deep multidimensional array
   into a list of its scalar values
   (may be inefficient for large structures)
   (will infinite recurse on self-referential structures)
   (could be extended to handle objects)
   --written by Anonymous
*/
{
   $lst = array();
   foreach( array_keys($ary) as $k ){
      $v = $ary[$k];
      if (is_scalar($v)) {
         $lst[] = $v;
      } elseif (is_array($v)) {
         $lst = array_merge( $lst,
            array_values_recursive($v)
         );
      }
   }
   return $lst;
}




// PHP program to carry out multidimensional array search 
// Function to recursively search for all values matching a pattern and return the array path 
// Modified from a version on geeksforgeeks.org
$return_vals = [];
function array_search_id($search_value, $array, $id_path = null) { 
global $return_vals;      
    if(is_array($array) && count($array) > 0) { 
          
        foreach($array as $key => $value) { 
  
            $temp_path = $id_path;
print_r($temp_path); 
              
            // Adding current key to search path 
            if (is_int($key)) {
              array_push($temp_path,$key);
            } else { 
              array_push($temp_path, "'$key'"); 
            }
  
            // Check if this value is an array 
            // with atleast one element 
            if(is_array($value) && count($value) > 0) { 
                $res_path = array_search_id($search_value, $value, $temp_path); 
  
                //if ($res_path != null) { 
                //    return $res_path; 
                //} 
            } 
            else if(preg_match($search_value, $value)) { 
                //$return_vals[] = "[" . join("][", $temp_path) . '] ==> ' . $value; 
                //$return_vals[] =  "[" . join("][", $temp_path) . "]"; 
                $return_vals[] =  $temp_path; 
            } 
        }
        if (!empty($return_vals)) {
          return $return_vals;
        }
    } 
      
    return null; 
} 


$recur_array_search_values = [];
function recursive_array_search($needle, $haystack) {
  global $recur_array_search_values;
  foreach($haystack as $key=>$value) {
    $current_key=$key;
    if (is_array($value)) {
      recursive_array_search($needle, $value);
    } else {
      if(preg_match($needle,$value,$matches)) {
        $recur_array_search_values[] = $matches[0];
      }
    }
  }
  if (!empty($recur_array_search_values)) return $recur_array_search_values;
  return false;
}


function recursive_array_search_tf($needle, $haystack, $currentKey = '') {
    foreach($haystack as $key=>$value) {
        if (is_array($value)) {
            $nextKey = recursive_array_search_tf($needle,$value, $currentKey . '[' . $key . ']');
            if ($nextKey) {
                return $nextKey;
            }
        }
        else if(preg_match("+$needle$+", "$value")) {
//            return is_numeric($key) ? $currentKey . '[' .$key . ']' : $currentKey . '["' .$key . '"]';
              return true;
        }
    }
    return false;
}




$ip = '64.203.126.58';
$pattern = '/.*[0-9]* permit ipv4 any host ' . "$ip" . '$/';
$arr = Array
(
    '64.203.126.58' => Array
        (
            '1Mbps' => Array
                (
                    '0' =>  '210 permit ipv4 any host 64.203.126.4',
                    '1' =>  '211 permit ipv4 any host 64.203.126.58',
                    '2' =>  '212 permit ipv4 any host 64.203.126.58',
                    '3' =>  '213 permit ipv4 any host 64.203.126.58',
                    '4' =>  '214 permit ipv4 any host 64.203.126.58'
                )

        ),
    '56.34.57.235' => Array
        (
          '3Mbps' => Array (
             
                    '0' =>  '215 permit ipv4 any host 64.203.126.58',
                    '1' =>  '216 permit ipv4 any host 64.203.126.58',
                    '2' =>  '217 permit ipv4 any host 64.203.126.58'
          ),
          '5Mbps' => Array (
                    '0' =>  '218 permit ipv4 any host 64.203.126.58',
                    '1' =>  '219 permit ipv4 any host 64.203.126.58',
                    '2' =>  '220 permit ipv4 any host 64.203.126.58'
          )
        )

);


$array_values_recursive = array_values_recursive($arr);
print_r("\narray_values_recursive:");
print_r($array_values_recursive);
print_r("\n\n");

//$array_search_id = array_search_id($pattern, $arr, array('')); 
$array_search_id = array_search_id($pattern, $arr, array()); 
print_r("\narray_search_id:");
print_r($array_search_id);
print_r("\n\n");
foreach ($array_search_id as $a) {
  print_r($arr[$a[0]][$a[1]]);
}
$recursive_array_search = recursive_array_search($pattern, $arr);
print_r("\nrecursive_array_search:");
print_r($recursive_array_search);
print_r("\n\n");

$recursive_array_search_tf = recursive_array_search_tf($pattern, $arr);
print_r("\nrecursive_array_search_tf:");
print_r($recursive_array_search_tf);

?>

