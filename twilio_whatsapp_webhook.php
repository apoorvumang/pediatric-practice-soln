<?php

require_once 'vendor/autoload.php';

if (class_exists('Dotenv\Dotenv')) {
    foreach ([__DIR__, __DIR__ . '/../'] as $path) {
        if (file_exists($path . '/.env')) {
            $dotenv = Dotenv\Dotenv::createImmutable($path);
            $dotenv->safeLoad();
            break;
        }
    }
}

function webhookEnv($key, $default = '') {
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }

    $value = getenv($key);
    return $value === false ? $default : $value;
}

$clinicNumber = webhookEnv('CLINIC_WHATSAPP_NUMBER', '+91 9717585207');
$defaultReply = "This number is used only for automated clinic reminders.\n\nFor appointments or questions, please message Dr. Mahima's clinic on WhatsApp: {$clinicNumber}";
$reply = webhookEnv('WHATSAPP_INBOUND_REPLY', $defaultReply);

if (!empty($_POST['From']) || !empty($_POST['Body'])) {
    error_log('Inbound WhatsApp reminder reply from ' . ($_POST['From'] ?? '') . ': ' . ($_POST['Body'] ?? ''));
}

header('Content-Type: text/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<Response>
    <Message><?php echo htmlspecialchars($reply, ENT_XML1 | ENT_COMPAT, 'UTF-8'); ?></Message>
</Response>
