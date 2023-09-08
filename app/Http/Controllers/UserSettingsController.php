<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\UserSettingsService;

class UserSettingsController extends Controller
{
    private $userSettingsService;
    
    public function __construct(UserSettingsService $userSettingsService)
    {
        $this->userSettingsService = $userSettingsService;
    }
    
    public function updateUserSettings(Request $request)
    {
        // Обработка запроса на изменение настроек пользователя 
        $userId = $request->user()->id; // ID текущего пользователя
        $newSettings = $request->settings; // Новые настройки
        $method = $request->method; // Метод подтверждения
        $methodData = $request->methodData;
        
        $this->userSettingsService->updateUserSettings($userId, $newSettings, $method, $methodData); // Вызов сервиса

        return view("confirm", compact("method"));
    }
    
    public function confirmUserSettings(Request $request)
    {
        // Обработка запроса на подтверждение смены настроек
        
        $userId = $request->user()->id; // ID текущего пользователя
        $code = $request->get('code'); // Код подтверждения
        $method = $request->get('method'); // Код подтверждения
        
        try{
            $this->userSettingsService->confirmUserSettings($userId, $code, $method); // Вызов сервиса
        } catch (\Exception $e) {
            // Возвращаем объект RedirectResponse на страницу с ошибкой и передаём сообщение об ошибке с помощью метода withErrors()
            return redirect()->route('home')->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('home');
    }
}
