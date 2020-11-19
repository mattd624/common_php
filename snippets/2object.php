<?php
class Person {
    public $name;

    function __construct( $name ) {
        $this->name = $name;
    }
};

$jack = new Person('Jack');
echo $jack->name;
$jack->name = 'Jacko';
echo $jack->name;
