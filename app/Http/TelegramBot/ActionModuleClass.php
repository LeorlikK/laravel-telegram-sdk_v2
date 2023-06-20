<?php

namespace App\Http\TelegramBot;

use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\User;
use Telegram\Bot\Objects\Update;

abstract class ActionModuleClass extends DefaultClass
{
    public string $module;

    public function __construct(?User $user, Update $update, ArgumentsService $argumentsService, string $module)
    {
        parent::__construct($user, $update, $argumentsService);
        $this->module = $module;
    }
}
