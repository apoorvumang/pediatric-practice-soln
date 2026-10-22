# WhatsApp Reminder Setup

This branch keeps SMS as the default. Set `MESSAGE_PROVIDER=whatsapp` only after the Twilio WhatsApp sender and templates are ready.

## Required environment variables

```dotenv
MESSAGE_PROVIDER=whatsapp
MESSAGE_FALLBACK_TO_SMS=true

TWILIO_ACCOUNT_SID=...
TWILIO_AUTH_TOKEN=...
TWILIO_WHATSAPP_MESSAGING_SERVICE_SID=MG...

TWILIO_WHATSAPP_TEMPLATE_BIRTHDAY=HX...
TWILIO_WHATSAPP_TEMPLATE_VACCINE_REMINDER=HX...
TWILIO_WHATSAPP_TEMPLATE_VACCINE_REMINDER_NODATE=HX...
TWILIO_WHATSAPP_TEMPLATE_APPOINTMENT_REMINDER=HX...
TWILIO_WHATSAPP_TEMPLATE_PAYMENT_DUE=HX...
TWILIO_WHATSAPP_TEMPLATE_CLINIC_INFO=HX...

CLINIC_WHATSAPP_NUMBER=+91 9717585207
WHATSAPP_INBOUND_REPLY="This number is used only for automated clinic reminders.\n\nFor appointments or questions, please message Dr. Mahima's clinic on WhatsApp: +91 9717585207"
```

If `MESSAGE_PROVIDER` is omitted or set to `sms`, the site keeps using the existing SEMYSYS SMS path.

If WhatsApp fails and `MESSAGE_FALLBACK_TO_SMS=true`, the system sends the same text via SMS and prints the WhatsApp error on the result page.

## Template data currently sent

- `birthday`: `name`
- `vaccine_reminder`: `name`, `vaccine`, `date`
- `vaccine_reminder_nodate`: `name`, `vaccine`
- `appointment_reminder`: `name`, `date`, optional `time` from the older appointment page
- `payment_due`: `name`, `amount`, `comment`
- `clinic_info`: no variables expected

The Twilio Content Template variable names must match these keys, or the gateway needs a small mapping change before production testing.

## Inbound replies

Configure the Twilio WhatsApp sender inbound webhook URL to:

```text
https://www.drmahima.com/twilio_whatsapp_webhook.php
```

The endpoint auto-replies that the reminder number is automated and asks patients to message the main clinic WhatsApp number.
