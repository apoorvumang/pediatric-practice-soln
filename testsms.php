<?php
include "smsGateway.php";
$smsGateway = new SmsGateway('apoorvumang@gmail.com', 'vultr123');

$deviceID = 84213;
$number = '7891947877';
$message = 'Hello World!';


//Please note options is no required and can be left out
$result = $smsGateway->sendMessageToNumber($number, $message, $deviceID);


var_dump($result);
?>
