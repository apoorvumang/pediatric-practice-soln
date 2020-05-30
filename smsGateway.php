<?php

class SmsGateway {
  // using smsgateway.me app
  // sending post request. can also use helper functions from website instead.
    function sendMessageToNumber($to, $message, $x) {
      $device_id = 117615; //117613;
      $authkey = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTU5MDc5ODc4MywiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjgwODE1LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.yL1fdoLaw8aDpzO5mnU2LoJZ5lcJVUBqxdETDZ964-E";
      $message = addslashes($message);
      $url = "https://smsgateway.me/api/v4/message/send";
      $data = array(
        'phone_number' => $to,
        'message' => $message,
        'device_id' => $device_id
       );
      $payload = json_encode(array($data));
      $curl = curl_init();
      curl_setopt_array($curl, array(
       CURLOPT_URL => $url,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_HTTPHEADER => array(
         "Authorization: {$authkey}",
         "Content-Type:application/json"
       ),
      ));
      curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
      $resp = curl_exec($curl);
      return;
    }
}

?>
