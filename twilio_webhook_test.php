<?php

// Log file path
$logFile = __DIR__ . '/webhook_log.txt';

// Get the raw POST data
$rawData = file_get_contents('php://input');

// Get the current timestamp
$timestamp = date('Y-m-d H:i:s');

// Prepare the log entry
$logEntry = "--- Webhook received at $timestamp ---\n";
$logEntry .= "POST data:\n$rawData\n";
$logEntry .= "--- End of webhook data ---\n\n";

// Append the log entry to the file
file_put_contents($logFile, $logEntry, FILE_APPEND);

// Send a 200 OK response to Twilio
http_response_code(200);
echo "Webhook received and logged.";
