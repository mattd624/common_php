<?php

//Requires commonDirLocation.php to be one directory level up from the directory this file is in. Then it can find commmon files including composer stuff. 

$predefinedClasses = get_declared_classes(); 
require __DIR__ . '/commonDirLocation.php';
require COMMON_PHP_DIR . '/vendor/autoload.php';

$loader = new \Composer\Autoload\ClassLoader();
$loader->addPsr4('phpseclib\\', COMMON_PHP_DIR . '/vendor/phpseclib/phpseclib/phpseclib');
//$loader->addPsr4('UBI\\', COMMON_PHP_DIR . '/src');
$loader->register();

use phpseclib\Crypt\RSA;
use phpseclib\Net\SSH2;
use Amp\Parallel\Worker;
use Amp\Promise;
//use UBI\Ssh;

//function my_autoloader($class) {
//  include 'src/' . $class . '.php';
//}
//spl_autoload_register('my_autoloader');


function ssh_get_data($ip) {
// return traffic shaping data
// requires phpseclib\SSH
global $user;
global $pws;
global $cmd;
  $data = '';
  if(!empty($ip)){
    $ssh = new SSH2($ip);
    $ssh->timeout = 0.5;
    foreach ($pws as $pw) {
      if ($ssh->login($user,$pw)) {
        $ssh->write($cmd."\r");
        $data = $ssh->read();
        $ssh->disconnect();
        unset($ssh);
        return $data;
      }
      return 0;
    }
  } else {
    return 0;
  }
}

spl_autoload_register('ssh_get_data');



print_r(get_defined_functions()['user']);

/*
$ip = '64.203.113.66';
$port = '220';
$u = 'root';

$ssh = new SSH2($ip,$port);
$key = file_get_contents('/home/matt/.ssh/id_rsa.pkcs1');
$keyloaded = new RSA();
$keyloaded->loadKey($key);
//$ssh->timeout = 2;
if (!$ssh->login($u,$keyloaded)) {
  exit('Login failed');
}

echo $ssh->exec("ls -la");
//$response = $ssh->read();
//print_r($response);
*/
//print_r(get_defined_functions());
print_r(array_diff(get_declared_classes(),$predefinedClasses));






$ips = ['10.10.136.8','10.10.136.6'];
$port = '220';
$user = 'root';
$pws = ['ubi@Su1!!','ubi@su1!!'];
$cmd = 'ls -la';


/*
$promises = [];
foreach ($ips as $ip) {
    //$promises[$url] = Worker\enqueueCallable('file_get_contents', $url);i

    print_r('Is Callable?: ' . is_callable('ssh_get_data') . "\n");
    $promises[$ip] = Worker\enqueueCallable('call_user_func','ssh_get_data');
}

$responses = Promise\wait(Promise\all($promises));

foreach ($responses as $ip => $response) {
    \printf("Read %d bytes from %s\n", \strlen($response), $ip);
}

*/








