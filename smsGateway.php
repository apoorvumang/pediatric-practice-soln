<?php

class SmsGateway {
  // using smsgateway.me app
  // sending post request. can also use helper functions from website instead.
    function sendMessageToNumber_smsgatewayme($to, $message, $x) {
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

    # using https://semysms.net/ paid app
    function sendMessageToNumber($to, $message, $x) {
      $url = "https://semysms.net/api/3/sms.php"; //Url address for sending SMS
      if($to[0] == '0') {
        $to = ltrim($to, '0');
      }
      $phone = '+91'.$to; // Phone number
      $msg = addslashes($message);  // Message
      $device = '228702';  //  Device code
      $token = 'c3f505c5da704f489b44aec2aa7e6352';  //  Your token (secret)

      $data = array(
              "phone" => $phone,
              "msg" => $msg,
              "device" => $device,
              "token" => $token
          );

          $curl = curl_init($url);
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);     
          $output = curl_exec($curl);
          curl_close($curl);
          echo($output);

    }
}

?>
