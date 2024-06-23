<?php

// include 'send_message_twilio.php';

// $data = [
//     'name' => 'Apoorv',
// ];

// $phone = '7891947877';
// $templateName = 'birthday';

// $message = sendMessageTwilio($phone, $templateName, $data);

// echo "Message sent: {$message->sid}";



require 'vendor/autoload.php';
use Twilio\Rest\Client;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Your Twilio credentials
$accountSid = $_ENV['TWILIO_ACCOUNT_SID'];
$authToken = $_ENV['TWILIO_AUTH_TOKEN'];

// Initialize Twilio client
$twilio = new Client($accountSid, $authToken);


$conversationSid = "CHb743ebefdb314edabc8d2ed8ff014564";

// $message = $twilio->conversations->v1->conversations($conversationSid)
//                                      ->messages
//                                      ->create(['body' => "Hello from Twilio!"]);

// // dump message
// var_dump($message);


$messages = $twilio->conversations->v1->conversations($conversationSid)
                                      ->messages
                                      ->read([], 20); // Retrieve last 20 messages

$conversationHistory = [];
foreach ($messages as $record) {
    $conversationHistory[] = [
        'author' => $record->author,
        'body' => $record->body,
        'date' => $record->dateCreated->format('Y-m-d H:i:s')
    ];
}

// Reverse the array to get chronological order
$conversationHistory = array_reverse($conversationHistory);

// print the conversation history in a formatted form

echo "<pre>";
print_r($conversationHistory);
echo "</pre>";