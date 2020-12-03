<?php 


                              /////////////////////////////////////        REQUIRES         /////////////////////////////////////////////////

require_once __DIR__ . '/../commonDirLocation.php';
require_once COMMON_PHP_DIR . '/vendor/autoload.php';

$loader = new \Composer\Autoload\ClassLoader();
$loader->addPsr4('phpseclib\\', COMMON_PHP_DIR . '/vendor/phpseclib/phpseclib/phpseclib');
$loader->register();

use phpseclib\Crypt\RSA;
use phpseclib\Net\SSH2;


                              /////////////////////////////////////        FUNCTIONS        /////////////////////////////////////////////////

function ssh_get_data($ip, $user, array $pws, $cmd) {
// return data from ssh connection; cmd is the command you run on the ssh host
// requires phpseclib\SSH
  $data = '';
  if(!empty($ip)){
    $ssh = new SSH2($ip);
    $ssh->timeout = 1;
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



                              /////////////////////////////////////        END FUNCTIONS        /////////////////////////////////////////////////


$ips = array();
array_push($ips, '10.10.136.6','10.10.136.9','10.10.136.12');
$cmd = 'grep -oE "tshap.*" /tmp/running.cfg';
$user = 'admin';
$pws = ['ubi@Su1!!','ubi@su1!!'];








for ($i = 1; $i <= 5; ++$i) { 
  $pid = pcntl_fork(); 
//print_r("\n$pid");
  if (!$pid) { 
    sleep(1); 
    print "In child $i\n";
    exit($i); 
  } 
} 
while (pcntl_waitpid(0, $status) != -1) { 
//print "\nStatus: $status\n";
  $status = pcntl_wexitstatus($status); 
  echo "Child $status completed\n"; 
}
 







$descriptors = array(
  0 => array("pipe", "r"),
  1 => array("pipe", "w")
);

///usr/local/bin/commonPHP/src/Ssh.php    
//$proc_iter_num = 0;
$data = [];
foreach ($ips as $ip) {
//  $proc_iter_num++;
//$process = 'process' . $proc_iter_num; //process name
  $process = proc_open("php", $descriptors, $pipes);
  if (is_resource($process)) {
    fwrite($pipes[0], "<?php\n");
    fwrite($pipes[0], "require_once __DIR__ . '/../commonDirLocation.php' ;\n");
    fwrite($pipes[0], "require_once COMMON_PHP_DIR . '/src/Ssh.php';\n");
    fwrite($pipes[0], "require_once COMMON_PHP_DIR . '/vendor/autoload.php';\n");
    fwrite($pipes[0], "\$loader = new \\Composer\\Autoload\\ClassLoader();\n");
    fwrite($pipes[0], "\$loader->addPsr4('phpseclib\\\', COMMON_PHP_DIR . '/vendor/phpseclib/phpseclib/phpseclib');\n");
    fwrite($pipes[0], "\$loader->register();\n");
    fwrite($pipes[0], "use phpseclib\Net\SSH2;\n");
    fwrite($pipes[0], "\$user = '$user';\n");
    fwrite($pipes[0], "\$pws = array(0 => 'ubi@Su1!!', 1 => 'ubi@su1!!');\n");
    fwrite($pipes[0], "\$cmd1 = '';\n");
    fwrite($pipes[0], "\$cmd2 = 'grep -oE tshaper.* /tmp/running.cfg';\n");
    fwrite($pipes[0], "\$ip = '$ip';\n");
    fwrite($pipes[0], "print_r(\$ip);\n");
    fwrite($pipes[0], "\$ssh = new Ssh;\n");
    fwrite($pipes[0], "\$ssh->GetData(\$ip,\$user,\$pws,\$cmd1);\n");
    fwrite($pipes[0], "print_r(\$ssh->GetData(\$ip,\$user,\$pws,\$cmd2));\n");
    fwrite($pipes[0], "\n");
    fwrite($pipes[0], "?>");
    fclose($pipes[0]);
    while (!feof($pipes[1])) {
      $data[$ip][] = fgets($pipes[1]);
    }
  fclose($pipes[1]);    
  $return_value = proc_close($process);
  }
}
print_r($data);



