<?php

namespace App\Services\ConfirmationMethods;

use GuzzleHttp\Client;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;

class TelegramConfirmation implements ConfirmationMethod
{
    public function sendConfirmationCode(UserSetting $userSettings): void
    {
        $client = new Client([
            'verify' => false // Отключение проверки SSL сертификата
        ]);

        $username = Auth::user()->telegram_username;
        $username_chat_id = 0;

        $botUpdates = json_decode($client->get('https://api.telegram.org/bot'.env("TELEGRAM_BOT_TOKEN").'/getUpdates')->getBody(), true)["result"];
        foreach ($botUpdates as $value) {
            if($value["message"]["from"]["username"] == $username){
                $username_chat_id = $value["message"]["from"]["id"];
            }
        }

        $client->post('https://api.telegram.org/bot'.env("TELEGRAM_BOT_TOKEN").'/sendMessage', [
            'form_params' => [
                'chat_id' => $username_chat_id,
                'text' => 'Код подтверждения: ' . $userSettings->code
            ]
        ]);

    }
    
    public function confirm(UserSetting $userSettings, string $code): void
    {
        if ((int) $userSettings->code !== (int) $code) {
            throw new \InvalidArgumentException('Invalid confirmation code');
        }
    }
}