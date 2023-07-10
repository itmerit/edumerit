<?php

namespace App\Services;
use Eskiz;

class EskizSmsService
{
    protected $apiToken;

    public function __construct()
    {
        $this->apiToken = config('eskizsms.api_token');
    }

    public function sendSms($recipient, $message)
    {
        // Implement your logic to send the SMS using Eskiz.uz API
        // Use $this->apiToken to access the configured API token

        $recipient = '+123456789';
        $message = 'Hello, Eskiz.uz SMS service!';

        EskizSms::sendSms($recipient, $message);
    }
}
