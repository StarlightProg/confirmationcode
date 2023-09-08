<?php

namespace App\Services\ConfirmationMethods;

use App\Models\UserSetting;
use App\Services\SendMessageMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailConfirmation implements ConfirmationMethod
{
    public function sendConfirmationCode(UserSetting $userSettings): void
    {
        $details = [
            'code' => $userSettings->code,
        ];

        Mail::to(Auth::user()->email)->queue(new SendMessageMail($details));
    }
    
    public function confirm(UserSetting $userSettings, string $code): void
    {
        if ((int) $userSettings->code !== (int) $code) {
            throw new \InvalidArgumentException('Invalid confirmation code');
        }
    }
}