<?php

include 'send_message_twilio.php';

$data = [
    'name' => 'Apoorv',
];

$phone = '7891947877';
$templateName = 'birthday';

$message = sendMessageTwilio($phone, $templateName, $data);

echo "Message sent: {$message->sid}";