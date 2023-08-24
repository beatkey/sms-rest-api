<?php

use Twilio\Rest\Client;

/**
 * Sends sms to user using Twilio's programmable sms client
 * @param String $message Body of sms
 * @param Number $recipients string or array of phone number of recepient
 */
if (!function_exists("sendSms")) {
    function sendSms($phoneNumber, $message)
    {
        try {
            $sid = env("TWILIO_SID");
            $authToken = env("TWILIO_AUTH_TOKEN");
            $fromNumber = env("TWILIO_NUMBER");

            $client = new Client($sid, $authToken);
            $client->messages->create($phoneNumber, [
                'from' => $fromNumber,
                'body' => $message]);

            return [
                "status" => true
            ];
        } catch (Exception $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }
}
