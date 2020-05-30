<?php
include "smsGateway.php";
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
// $curl = curl_init();
// Set some options - we are passing in a useragent too here
// curl_setopt_array($curl, array(
//     CURLOPT_RETURNTRANSFER => 1,

$message= "Dear mahima\n hello hello\n fsadfsf";

$smsGateway = new SmsGateway();
$smsGateway->sendMessageToNumber("7891947877", $message);

?>
