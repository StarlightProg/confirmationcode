<?php

namespace App\Services\ConfirmationMethods;

use App\Models\UserSetting;

interface ConfirmationMethod
{
    public function sendConfirmationCode(UserSetting $userSettings): void;
    public function confirm(UserSetting $userSettings, string $code): void;
}