<?php

require 'vendor/autoload.php';
use Twilio\Rest\Client;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$use_twilio = true;
// $use_twilio = false; // disabled until account is verified

// Global initialization of Twilio client
$globalTwilioClient = new Client($_ENV['TWILIO_ACCOUNT_SID'], $_ENV['TWILIO_AUTH_TOKEN']);

function getTemplateSid($templateName) {
    $template_to_sid = [
        'birthday' => 'HX058f4bed3d29a3a0f6ca5df06b66f99b', // name
        'vaccine_reminder' => 'HXfbf047a988e521918f46ba596ab7df98', // name, vaccine, date
        'vaccine_reminder_nodate' => 'HX53e03b39ae21a8fa05797b969aa2d363', // name, vaccine
    ];

    return $template_to_sid[$templateName] ?? null;
}

function convertToE164($phoneNumber) {
    // Remove any non-digit characters
    $cleaned = preg_replace('/[^0-9+]/', '', $phoneNumber);

    // Check if it's already in E.164 format (starts with + and has at least 9 digits)
    if (substr($cleaned, 0, 1) === '+' && strlen($cleaned) >= 10) {
        return $cleaned;
    }

    // Handle Indian numbers
    if (substr($cleaned, 0, 2) === '91' && strlen($cleaned) === 12) {
        return '+' . $cleaned;
    }

    if (substr($cleaned, 0, 2) === '91' && strlen($cleaned) > 12) {
        return '+' . substr($cleaned, 0, 12);
    }

    if (substr($cleaned, 0, 1) === '0') {
        return '+91' . substr($cleaned, 1);
    }

    if (strlen($cleaned) === 10) {
        return '+91' . $cleaned;
    }

    // If none of the above conditions are met, return false or handle as needed
    return false;
}

function sendMessageTwilio($to, $templateName, $data) {
    global $globalTwilioClient; // Use the globally initialized Twilio client

    $to = "whatsapp:".convertToE164($to);
    $contentSid = getTemplateSid($templateName);

    $message = $globalTwilioClient->messages->create(
        $to,
        [
            "contentSid" => $contentSid,
            "from" => "MG2a51df49f3aed67870f272d6667ec78f",
            "contentVariables" => json_encode($data),
        ]
    );

    return $message;
}

// Example usage
// $birthdayMessage = sendMessageTwilio("whatsapp:+918473894782", "birthday", ["name" => "Juthika"]);
// $appointmentReminder = sendMessageTwilio("whatsapp:+917891947877", "appointment", $appointmentData);

// var_dump($birthdayMessage);
// var_dump($appointmentReminder);

?>