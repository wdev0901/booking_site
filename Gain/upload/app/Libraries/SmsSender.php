<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Config;

class SmsSender
{

    public static function sendSms($phone, $smsText, $config)
    {
        $basic  = new \Nexmo\Client\Credentials\Basic($config->get('key'), $config->get('secret_key'));
        $client = new \Nexmo\Client($basic);

        $message = $client->message()->send([
            'to' => preg_replace('/(\W*)/', '', $phone),
            'from' => 'Vonage APIs',
            'text' => $smsText
        ]);

        return $message;
    }
}