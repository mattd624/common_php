<?php


function check_for_peer_name_in_ssl_cert($host, $peer_name) {
    error_reporting(0);
    $g = stream_context_create (
        array(
            "http" => array(
                "timeout" => 2.0
            ),
            "ssl" => array(
                "capture_peer_cert" => true, 
                "verify_peer" => False, 
                "peer_name" => "$peer_name"
            ),
//            "socket" => array(
//                "bindto" => "192.168.2.42:0"
//            )
        )
    );
    $r = fopen("https://$host", "rb", false, $g);
    $t = stream_set_timeout($r, 1);
    $cont = stream_context_get_params($r);
    ob_end_clean();
    unset ($g,$r);
    if (is_resource($cont["options"]["ssl"]["peer_certificate"])) {
      return True;
    } else {
      return False;
    }
}


/*
$str = 'seems insecure or could not be contacted. Skipping.205.157.152.69 seems insecure or could not be contacted. Skipping.207.177.162.169 seems insecure or could not be contacted. Skipping.
207.177.152.106 - Login Failed64.203.120.18 seems insecure or could not be contacted. Skipping.205.157.147.137 seems insecure or could not be contacted. Skipping.67.204.49.8 seems insecure or could not be contacted. Skipping.67.204.59.107 seems insecure or could not be contacted. Skipping.207.177.177.7 seems insecure or could not be contacted. Skipping.
207.177.164.228 - Login Failed67.204.37.112 seems insecure or could not be contacted. Skipping.207.177.169.48 seems insecure or could not be contacted. Skipping.64.203.124.231 seems insecure or could not be contacted. Skipping.207.177.174.3 seems insecure or could not be contacted. Skipping.67.204.46.70 seems insecure or could not be contacted. Skipping.67.204.59.5 seems insecure or could not be contacted. Skipping.206.162.238.213 seems insecure or could not be contacted. Skipping.207.177.178.132 seems insecure or could not be contacted. Skipping.64.203.116.117 seems insecure or could not be contacted. Skipping.206.162.224.139 seems insecure or could not be contacted. Skipping.207.177.146.149 seems insecure or could not be contacted. Skipping.207.177.178.77 seems insecure or could not be contacted. Skipping.205.157.149.197 seems insecure or could not be contacted. Skipping.207.177.170.14 seems insecure or could not be contacted. Skipping.67.204.39.132 seems insecure or could not be contacted. Skipping.207.177.180.194 seems insecure or could not be contacted. Skipping.207.177.157.104 seems insecure or could not be contacted. Skipping.206.162.238.93 seems insecure or could not be contacted. Skipping.64.193.94.53 seems insecure or could not be contacted. Skipping.206.162.234.122 seems insecure or could not be contacted. Skipping.67.204.39.195';

preg_match_all('/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/m', $str, $matches);
print_r($matches[0]);
foreach ($matches[0] as $match) {
  $host = $match . ':8443';
  echo "\n$host";
  $secure1 = check_for_peer_name_in_ssl_cert($host, 'router.asus.com');
  $secure2 = check_for_peer_name_in_ssl_cert($host, '192.168.50.1');
  echo "\nsecure1:\n";
  print_r($secure1);
  echo "\nsecure2:\n";
  print_r($secure2);
}
*/

