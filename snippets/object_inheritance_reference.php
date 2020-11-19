<?php

$bar = 'prop';


class Foo
{
    public $bar = 'property';
    
    public function bar() {
        return 'method';
    }
}

$FooObj = new Foo();
echo $FooObj->bar, PHP_EOL;   //property
echo $FooObj->bar(), PHP_EOL; //method



class A {

    public $value;

    function __construct() {
        $this->value = "A";
    }

}

$a = new A();
echo $a->value; // "A";







class B extends A {
}

$b = new B();
echo $b->value; // "A";

class C extends A {

    function __construct() {
        $this->value = "C";
    }

}

$c = new C();
echo $c->value; // "C";








class D extends A {

    function __construct() {
        $this->value = "D";
        parent::__construct();
    }

}

$d = new D();
echo $d->value, PHP_EOL; // output is "A" because of the parent::__construct()






class Managedobject {
  public $mo_name;
  public $id;
  public $ip;

  public function __construct ( $mo_name, $id, $ip ) {
    $this->mo_name = $mo_name;
    $this->id = $id;
    $this->ip = $ip;
  }
}


$mo_1 = new Managedobject( '6Mbps', '170', '192.168.66.66' );
echo $mo_1->mo_name, PHP_EOL;





