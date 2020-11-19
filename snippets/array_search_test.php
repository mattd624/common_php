<?php

$userdb=Array
(
    (0) => Array
        (
            'uid' => '100',
            'name' => 'Sandra Shush',
            'url' => 'urlof100'
        ),

    (1) => Array
        (
            'uid' => '5465',
            'name' => 'Stefanie Mcmohn',
            'pic_square' => 'urlof100'
        ),

    (2) => Array
        (
            'uid' => '40489',
            'name' => 'Michael',
            'pic_square' => 'urlof40489'
        )
);

print_r($userdb);

$key = array_search(40489, array_column($userdb, 'uid'));

print_r($key);
