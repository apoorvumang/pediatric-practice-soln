<?php

class SmsGateway {
    function sendMessageToNumber($to, $message) {
      $message = addslashes($message);
      $curl = curl_init();
      curl_setopt_array($curl, array(
       CURLOPT_URL => "http://fcm.googleapis.com/fcm/send",
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => "",
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 30,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => "POST",
       CURLOPT_POSTFIELDS => "\n{\n \"to\" : \"/topics/sms\",\n \"data\" : {\n\t\"phoneNumber\": \"{$to}\",\n\t\"message\": \"{$message}\"\n } \n}",
       CURLOPT_HTTPHEADER => array(
         "authorization: key=AAAAmiPEplA:APA91bHe_Lv8e0BUz39Jx-U_WnR1lOO3KftMSrGKsQRPBmMt4VmAahW0ISf-i4nJEjWLG0xqe4dhFXsgV-06fFBU4bAqgen62uFABzE5sxTbrVoyGoOeTTVno-auRCMyXLUyA6aatXKu",
         "cache-control: no-cache",
         "content-type: application/json"
       ),
      ));
      $resp = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);
      return $err;
    }
}

?>
