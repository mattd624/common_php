<?php
/**
 * Send a Message to a Slack Channel.
 * @return boolean
 */
require_once __DIR__ . '/hooks.php';
require_once __DIR__ . '/SlackMessagePost.php';

//$messages = parse_str(implode('&', array_slice($argv, 1)), $_GET);
//$messages = $argv;
$messages = file_get_contents("php://stdin", 'r');
print_r("\nmessages:");
print_r($messages);
//foreach ($messages as $i => $m) if (!empty($m)) slack($m, 'mattd');
//if (!empty($messages)) slack("$messages", 'mattd');
slack("$messages" . " 1", 'mattd');

$test = 'wrote file here';
file_put_contents('/tmp/testfile', $test);
?>

