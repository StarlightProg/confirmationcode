<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;
use App\Services\ConfirmationMethods\SMSConfirmation;
use App\Services\ConfirmationMethods\EmailConfirmation;
use App\Services\ConfirmationMethods\TelegramConfirmation;
use Carbon\Carbon;

class UserSettingsService
{
    private $userSettingsModel;
    
    public function __construct(UserSetting $userSettingsModel)
    {
        $this->userSettingsModel = $userSettingsModel;
    }
    
    public function updateUserSettings(int $userId, $newSettings, string $method = 'email', array $methodData = null): void
    {
        // Обновление настроек пользователя
        
        $userSettings = $this->userSettingsModel->where('user_id', $userId)->firstOrFail();
        
        if(!is_null($methodData)){
            $user = User::where('id', $userId)->firstOrFail();
            $user->telegram_username = str_replace("@", "", $methodData["telegram"]) ?? null;
            $user->phone = $methodData["phone"] ?? null;
            $user->save();
        }

        session()->put('settings', $newSettings);

        $userSettings->code = $this->generateConfirmationCode();
        $userSettings->create_code_time = Carbon::now();
        $userSettings->save();
        
        $this->sendConfirmationCode($userSettings, $method); 
    }
    
    public function confirmUserSettings(int $userId, int $code, string $method)
    {
        $userSettings = $this->userSettingsModel->where('user_id', $userId)->firstOrFail();

        if(Carbon::now()->diffInMinutes($userSettings->create_code_time) > 10){
            throw new \Exception("Истекло время подтверждения");
        }
        
        $confirmationMethodClass = $this->getConfirmationMethodClass($method);

        try{
            $confirmationMethodClass->confirm($userSettings, $code); // Проверка кода подтверждения
        } catch (\Exception $e) {
            throw new \Exception("Неверный код потверждения");
        }
        
        
        $userSettings->update(session()->get('settings'));
        $userSettings->code = null; // Удаление кода подтверждения после подтверждения смены настроек
        $userSettings->create_code_time = null;
        $userSettings->save();

        session()->forget('settings');
    }
    
    private function generateConfirmationCode(): string
    {
        // Генерация кода подтверждения
        return strval(mt_rand(100000, 999999));
    }
    
    private function sendConfirmationCode(UserSetting $userSettings, string $confirmation_method): void
    {
        // Отправка кода подтверждения
        $confirmationMethodClass = $this->getConfirmationMethodClass($confirmation_method);
        $confirmationMethodClass->sendConfirmationCode($userSettings); // Отправка кода подтверждения
    }
    
    private function getConfirmationMethodClass(string $method): SMSConfirmation|EmailConfirmation|TelegramConfirmation
    {
        // Получение класса метода подтверждения
        
        switch ($method) {
            case 'telegram':
                return new TelegramConfirmation();
            case 'phone':
                return new SMSConfirmation();
            case 'email':
            default:
                return new EmailConfirmation();
        }
    }
}