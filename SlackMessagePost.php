<?php
/**
 * Send a Message to a Slack Channel.
 * @return boolean
 */
include __DIR__ . '/hooks.php';

function slack($message, $channel) {
global $channels;
$webhook = $channels[$channel];

if (!defined('SLACK_WEBHOOK')) {
 define('SLACK_WEBHOOK', $webhook);
}     
    $data = array('payload' => json_encode(array('text' => "$message")));
    $ch = curl_init(SLACK_WEBHOOK);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//    curl_setopt($ch, CURLOPT_VERBOSE, true);  //debugging
    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result;
}

// Example message will post "Hello world" to the channel encoded in the webhook. 
// slack('Hello world Test from SOAP box', 'mattd');
