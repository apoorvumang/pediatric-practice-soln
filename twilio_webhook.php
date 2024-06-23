<?php

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

// Get the raw POST data
$input = file_get_contents('php://input');
$data = parse_str($input, $postData);

// Extract relevant information
$conversationSid = $postData['ConversationSid'];
$incomingMessage = $postData['Body'];
$author = $postData['Author'];

// Your bot's identifier (e.g., your Twilio phone number or WhatsApp number)
$botIdentifier = 'system'; // Replace with your bot's identifier

// Check if the message is from the bot, or doesn't start with 'whatsapp:'
if ($author == $botIdentifier || strpos($author, 'whatsapp:') !== 0){
    // This is a message from our bot, so we'll ignore it
    http_response_code(200);
    echo "Ignored bot's own message.";
    exit;
}

// Log the incoming webhook data
$logFile = __DIR__ . '/webhook_log.txt';
$logEntry = date('Y-m-d H:i:s') . " - Received message from $author: $incomingMessage in conversation: $conversationSid\n";
file_put_contents($logFile, $logEntry, FILE_APPEND);

// Retrieve conversation history
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

// Generate bot response
$botResponse = generateBotResponse($conversationHistory);

// Send the bot's response
$message = $twilio->conversations->v1->conversations($conversationSid)
                                     ->messages
                                     ->create([
                                         'body' => $botResponse,
                                     ]);

// Log the bot's response
$logEntry = date('Y-m-d H:i:s') . " - Bot responded: $botResponse\n";
file_put_contents($logFile, $logEntry, FILE_APPEND);

// Send a 200 OK response to Twilio
http_response_code(200);
echo "Webhook processed successfully.";

function generateBotResponse($conversationHistory) {
    // Implement your bot logic here
    // This is a placeholder implementation
    $lastMessage = end($conversationHistory);

    $response = 'Thank you for your message. This number does not support incoming messages - please contact **Dr. Mahima\'s clinic at +91 9717585207**.';
    // return "You said: '" . $lastMessage['body'] . "'. How can I help you with that?";
}