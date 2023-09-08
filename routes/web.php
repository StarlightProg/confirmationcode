<?php

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
