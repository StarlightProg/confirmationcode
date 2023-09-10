<?php

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/update', [App\Http\Controllers\UserSettingsController::class, 'updateUserSettings'])->name('settings.change');
Route::patch('/update/confirm', [App\Http\Controllers\UserSettingsController::class, 'confirmUserSettings'])->name('confirm.code');

Route::get('/telegram',function(){
    $client = new Client([
        'verify' => false // Отключение проверки SSL сертификата
    ]);

    //$username = Auth::user()->telegram_chat_id;

    // 'form_params' => [
    //     'chat_id' => $username,
    //     'text' => 'Код подтверждения: ' . "dqweq"
    // ]
    //dd('https://api.telegram.org/bot'.env("TELEGRAM_BOT_TOKEN").'/getUpdate');
    $ddd = json_decode($client->get('https://api.telegram.org/bot'.env("TELEGRAM_BOT_TOKEN").'/getUpdates')->getBody(), true)["result"];
    //dd($ddd[0]["message"]["from"]);
    foreach ($ddd as $value) {
        if($value["message"]["from"]["username"] == "StarlightPepega"){
            dd("Ваш id: " . $value["message"]["from"]["id"]);
        }
        //[0]["message"]["from"]["id"]
    }
    dd($ddd["result"][0]["message"]["from"]["id"]);

} );
