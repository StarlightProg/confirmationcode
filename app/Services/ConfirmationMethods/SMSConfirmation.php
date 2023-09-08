<?php

namespace App\Services\ConfirmationMethods;

use Twilio\Rest\Client;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;

class SMSConfirmation implements ConfirmationMethod
{
    public function sendConfirmationCode(UserSetting $userSettings): void
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $senderNumber = env('TWILIO_PHONE');
        
        $client = new Client($sid, $token);
        
        $client->messages->create(
            Auth::user()->phone,
            [
                'from' => $senderNumber,
                'body' => "Your confirmation code: {$userSettings->code}"
            ]
        );
    }
    
    public function confirm(UserSetting $userSettings, string $code): void
    {
        if ((int) $userSettings->code !== (int) $code) {
            throw new \InvalidArgumentException('Invalid confirmation code');
        }
    }
}