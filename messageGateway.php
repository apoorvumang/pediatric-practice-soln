<?php

require_once 'smsGateway.php';

class MessageGateway {
    private $smsGateway;
    private $twilioClient;
    private $provider;
    private $fallbackToSms;

    public function __construct() {
        $this->loadEnvironment();
        $this->smsGateway = new SmsGateway();
        $this->provider = strtolower($this->env('MESSAGE_PROVIDER', 'sms'));
        $this->fallbackToSms = $this->envBool('MESSAGE_FALLBACK_TO_SMS', true);
    }

    public function send($to, $messageType, $templateData, $smsBody) {
        if (!$to) {
            return [
                'ok' => false,
                'provider' => 'none',
                'message' => 'No phone number present',
            ];
        }

        if ($this->provider === 'whatsapp') {
            try {
                return $this->sendWhatsApp($to, $messageType, $templateData);
            } catch (Exception $e) {
                if (!$this->fallbackToSms) {
                    return [
                        'ok' => false,
                        'provider' => 'whatsapp',
                        'message' => $e->getMessage(),
                    ];
                }

                $smsResult = $this->sendSms($to, $smsBody);
                $smsResult['message'] = 'WhatsApp failed; sent SMS fallback. Error: ' . $e->getMessage();
                return $smsResult;
            }
        }

        return $this->sendSms($to, $smsBody);
    }

    private function sendSms($to, $message) {
        $this->smsGateway->sendMessageToNumber($to, $message, 84200);

        return [
            'ok' => true,
            'provider' => 'sms',
            'message' => 'SMS sent',
        ];
    }

    private function sendWhatsApp($to, $messageType, $templateData) {
        if ($messageType === 'custom') {
            throw new RuntimeException('Custom WhatsApp messages require a 24-hour patient-initiated window; using SMS fallback');
        }

        $contentSid = $this->templateSid($messageType);
        if (!$contentSid) {
            throw new RuntimeException("WhatsApp template SID not configured for {$messageType}");
        }

        $client = $this->twilioClient();
        $payload = [
            'contentSid' => $contentSid,
        ];
        if (!empty($templateData)) {
            $payload['contentVariables'] = json_encode($templateData);
        }

        $messagingServiceSid = $this->env('TWILIO_WHATSAPP_MESSAGING_SERVICE_SID', '');
        if ($messagingServiceSid) {
            $payload['messagingServiceSid'] = $messagingServiceSid;
        } else {
            $from = $this->env('TWILIO_WHATSAPP_FROM', '');
            if (!$from) {
                throw new RuntimeException('TWILIO_WHATSAPP_MESSAGING_SERVICE_SID or TWILIO_WHATSAPP_FROM is required');
            }
            $payload['from'] = $this->formatWhatsAppNumber($from);
        }

        $message = $client->messages->create($this->formatWhatsAppNumber($to), $payload);

        return [
            'ok' => true,
            'provider' => 'whatsapp',
            'sid' => $message->sid,
            'message' => 'WhatsApp sent',
        ];
    }

    private function twilioClient() {
        if ($this->twilioClient) {
            return $this->twilioClient;
        }

        $accountSid = $this->env('TWILIO_ACCOUNT_SID', '');
        $authToken = $this->env('TWILIO_AUTH_TOKEN', '');
        if (!$accountSid || !$authToken) {
            throw new RuntimeException('TWILIO_ACCOUNT_SID and TWILIO_AUTH_TOKEN are required');
        }

        $this->twilioClient = new \Twilio\Rest\Client($accountSid, $authToken);
        return $this->twilioClient;
    }

    private function templateSid($messageType) {
        $templateEnv = [
            'birthday' => 'TWILIO_WHATSAPP_TEMPLATE_BIRTHDAY',
            'vaccine_reminder' => 'TWILIO_WHATSAPP_TEMPLATE_VACCINE_REMINDER',
            'vaccine_reminder_nodate' => 'TWILIO_WHATSAPP_TEMPLATE_VACCINE_REMINDER_NODATE',
            'appointment_reminder' => 'TWILIO_WHATSAPP_TEMPLATE_APPOINTMENT_REMINDER',
            'payment_due' => 'TWILIO_WHATSAPP_TEMPLATE_PAYMENT_DUE',
            'clinic_info' => 'TWILIO_WHATSAPP_TEMPLATE_CLINIC_INFO',
        ];

        if (!isset($templateEnv[$messageType])) {
            return null;
        }

        return $this->env($templateEnv[$messageType], '');
    }

    private function formatWhatsAppNumber($phoneNumber) {
        if (strpos($phoneNumber, 'whatsapp:') === 0) {
            return $phoneNumber;
        }

        $e164 = $this->convertToE164($phoneNumber);
        if (!$e164) {
            throw new RuntimeException("Invalid phone number for WhatsApp: {$phoneNumber}");
        }

        return 'whatsapp:' . $e164;
    }

    private function convertToE164($phoneNumber) {
        $cleaned = preg_replace('/[^0-9+]/', '', $phoneNumber);

        if (substr($cleaned, 0, 1) === '+' && strlen($cleaned) >= 10) {
            return $cleaned;
        }

        if (substr($cleaned, 0, 2) === '91' && strlen($cleaned) >= 12) {
            return '+' . substr($cleaned, 0, 12);
        }

        if (substr($cleaned, 0, 1) === '0') {
            return '+91' . substr($cleaned, 1);
        }

        if (strlen($cleaned) === 10) {
            return '+91' . $cleaned;
        }

        return false;
    }

    private function loadEnvironment() {
        if (!class_exists('Dotenv\Dotenv')) {
            return;
        }

        foreach ([__DIR__, __DIR__ . '/../'] as $path) {
            if (file_exists($path . '/.env')) {
                $dotenv = Dotenv\Dotenv::createImmutable($path);
                $dotenv->safeLoad();
                return;
            }
        }
    }

    private function env($key, $default = null) {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        $value = getenv($key);
        if ($value !== false) {
            return $value;
        }

        return $default;
    }

    private function envBool($key, $default) {
        $value = $this->env($key, null);
        if ($value === null) {
            return $default;
        }

        return in_array(strtolower($value), ['1', 'true', 'yes', 'on'], true);
    }
}

function sendClinicMessage($messageGateway, $phone, $messageType, $templateData, $smsBody, $recipientName) {
    $result = $messageGateway->send($phone, $messageType, $templateData, $smsBody);
    $provider = strtoupper($result['provider']);
    $detail = isset($result['sid']) ? " ({$result['sid']})" : '';
    echo "{$provider}: {$result['message']} to {$recipientName}{$detail}<br>";
    return $result;
}

?>
