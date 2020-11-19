<?php

//////////////////////////   ENIGMA FUNCTIONS   //////////////////////////////////


function CallAPI($url) {
//Used for Enigma API...only does GET calls, not POST,
//but still is used to edit things in Enigma, depending on $url
  $ch = curl_init();
  $options = array(
    CURLOPT_URL            => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER         => false,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING       => "",
    CURLOPT_AUTOREFERER    => true,
    CURLOPT_CONNECTTIMEOUT => 120,
    CURLOPT_TIMEOUT        => 120,
    CURLOPT_MAXREDIRS      => 10,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_HTTPAUTH       => CURLAUTH_ANY,
    CURLOPT_USERNAME       => ENIG_API_USER,
    CURLOPT_PASSWORD       => ENIG_API_PW,
    CURLOPT_RETURNTRANSFER => 1,
);
  curl_setopt_array( $ch, $options );
  $response = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code

  if ( $httpCode != 200 ){
    $output = $httpCode . curl_error($ch);
  } else {
    $output = $response;
  }
  curl_close($ch);


    return $output;
}


