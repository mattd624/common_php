<?php

function array_recursive_search_key_map($needle, $haystack) {
    foreach($haystack as $first_level_key=>$value) {
        if ($needle === $value) {
            return array($first_level_key);
        } elseif (is_array($value)) {
            $callback = array_recursive_search_key_map($needle, $value);
            if ($callback) {
                return array_merge(array($first_level_key), $callback);
            }
        }
    }
    return false;
}



function array_get_nested_value($keymap, $array)
{
    $nest_depth = sizeof($keymap);
    $value = $array;
    for ($i = 0; $i < $nest_depth; $i++) {
        $value = $value[$keymap[$i]];
    }

    return $value;
}

//usage example:
//-------------------


$ip = '64.203.126.58';
$pattern = '/.*[0-9]* permit ipv4 any host ' . "$ip" . '$/';
$arr = Array
(
    '64.203.126.58' => Array
        (
            '1Mbps' => Array
                (
                    '0' =>  '210 permit ipv4 any host 64.203.126.4',
                    '1' =>  '64.203.126.58',
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



$array_keymap = array_recursive_search_key_map($ip, $arr);
print_r("\narray_keymap:");
print_r($array_keymap);
echo array_get_nested_value($array_keymap, $arr);

