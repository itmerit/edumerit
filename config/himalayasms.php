<?php

return [
    // Your Himalaya SMS api key here.
    'key' => '560F935496062E',
    // Sender ID Provided by Himalaya SMS
    'senderid' => 'KalashAlert',
    // campaign ID Provided by Himalaya SMS
    'campaign' => '5144',
    // router ID Provided by Himalaya SMS
    'routeid' => '125',
    // type keep it text
    'type' => 'text',

    // Do not edit the values below here unless necessary.
    'api_endpoint' => 'https://sms.techhimalaya.com/base/smsapi/index.php',
    'methods' => [
        'send' => 'https://sms.techhimalaya.com/base/smsapi/index.php',
    ]
];