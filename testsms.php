<?php
// include "smsGateway.php";
// $smsGateway = new SmsGateway('apoorvumang@gmail.com', 'vultr123');
//
// $deviceID = 84213;
// $number = '7891947877';
// $message = 'Hello World!';
//
//
// //Please note options is no required and can be left out
// $result = $smsGateway->sendMessageToNumber($number, $message, $deviceID);

// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
// curl_setopt_array($curl, array(
//     CURLOPT_RETURNTRANSFER => 1,
//     CURLOPT_URL => 'http://testcURL.com/?item1=value&item2=value2',
//     CURLOPT_USERAGENT => 'Codular Sample cURL Request'
// ));



curl_setopt_array($curl, array(
 CURLOPT_URL => "http://fcm.googleapis.com/fcm/send",
 CURLOPT_RETURNTRANSFER => true,
 CURLOPT_ENCODING => "",
 CURLOPT_MAXREDIRS => 10,
 CURLOPT_TIMEOUT => 30,
 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
 CURLOPT_CUSTOMREQUEST => "POST",
 CURLOPT_POSTFIELDS => "\n{\n \"to\" : \"/topics/sms\",\n \"data\" : {\n\t\"phoneNumber\": \"7407650530\",\n\t\"message\": \"test mesag2e\"\n } \n}",
 CURLOPT_HTTPHEADER => array(
   "authorization: key=AAAAmiPEplA:APA91bHe_Lv8e0BUz39Jx-U_WnR1lOO3KftMSrGKsQRPBmMt4VmAahW0ISf-i4nJEjWLG0xqe4dhFXsgV-06fFBU4bAqgen62uFABzE5sxTbrVoyGoOeTTVno-auRCMyXLUyA6aatXKu",
   "cache-control: no-cache",
   "content-type: application/json"
 ),
));




// Send the request & save response to $resp
$resp = curl_exec($curl);
$err = curl_error($curl);


// Close request to clear up some resources
curl_close($curl);

var_dump($err);
//
// $curl = curl_init();
//
// curl_setopt_array($curl, array(
//  CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
//  CURLOPT_RETURNTRANSFER => true,
//  CURLOPT_ENCODING => "",
//  CURLOPT_MAXREDIRS => 10,
//  CURLOPT_TIMEOUT => 30,
//  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//  CURLOPT_CUSTOMREQUEST => "POST",
//  CURLOPT_POSTFIELDS => "\n{\n \"to\" : \"/topics/sms\",\n \"data\" : {\n\t\"phoneNumber\": \"7407650530\",\n\t\"message\": \"test mesag2e\"\n } \n}",
//  CURLOPT_HTTPHEADER => array(
//    "authorization: key=AAAAmiPEplA:APA91bHe_Lv8e0BUz39Jx-U_WnR1lOO3KftMSrGKsQRPBmMt4VmAahW0ISf-i4nJEjWLG0xqe4dhFXsgV-06fFBU4bAqgen62uFABzE5sxTbrVoyGoOeTTVno-auRCMyXLUyA6aatXKu",
//    "cache-control: no-cache",
//    "content-type: application/json"
//  ),
// ));
//
// $response = curl_exec($curl);
// $err = curl_error($curl);
//
// curl_close($curl);
//
// if ($err) {
//  echo "cURL Error #:" . $err;
// } else {
//  echo $response;
// }

// var_dump($result);
?>
