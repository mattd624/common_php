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
use UBI\Ssh;
use UBI\VLookup;
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

$records = array();
$opp1 = new stdClass();
$opp1->Id = 'kjhaldkfjg002480';
$opp1->Name = 'A-S0992634';
$opp1->IP = '192.168.89.34';

$opp2 = new stdClass();
$opp2->Id = 'kjhaldkfjg002499';
$opp2->Name = 'A-S0992667';
$opp2->IP = '192.168.45.78';

array_push($records, $opp1, $opp2);
print_r($records);

function indexed_response($sf_api_response, $key = 'Id') {
  $output_arr = array();
  foreach ($sf_api_response as $record) {
    $k = $record->$key;
    $props = array_keys((array)$record);
    $output_arr[$k] = new stdClass();
    foreach ($props as $prop) {
      $output_arr[$k]->$prop = $record->$prop;
    }
  }
  return $output_arr;
}


$index_ip = indexed_response($records, 'IP');
$index_id = indexed_response($index_ip, 'Id');

print_r($index_ip);
print_r($index_id);

/*
$lookup = new VLookup;
print_r($lookup->arrVLookup($opps,'A-S0992667','Name','Id'));

/*
$ips = ['10.10.136.8','10.10.136.6'];
$port = '220';
$user = 'root';
$pws = ['ubi@Su1!!','ubi@su1!!'];
$cmd = 'ls -la';
*/

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








