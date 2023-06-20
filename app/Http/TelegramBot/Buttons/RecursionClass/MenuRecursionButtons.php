<?php

namespace App\Http\TelegramBot\Buttons\RecursionClass;

use App\Http\TelegramBot\Services\ArgumentsService;
use Illuminate\Support\Collection;

class MenuRecursionButtons
{
    public static function adminFirstPage(Collection $buttons, ArgumentsService $argumentsService):Collection
    {
        return $buttons->add([
            ['text' => '🔑 Админ панель', 'callback_data' =>
                'cl:Admin'.'_'."ac:$argumentsService->ac".'_'."fp:$argumentsService->fp"],
            ['text' => '🕹 Действия', 'callback_data' =>
                'cl:MenuA'.'_'."ac:$argumentsService->ac".'_'."fp:$argumentsService->fp"],
        ]);
    }

    public static function adminNotFirstPage(Collection $buttons, ArgumentsService $argumentsService):Collection
    {
        return $buttons->add([
            ['text' => '◀️ Назад ◀️', 'callback_data' =>
                "cl:$argumentsService->cl".'_'."ac:$argumentsService->ac".'_'."fp:$argumentsService->fp"],
            ['text' => '🕹 Действия', 'callback_data' =>
                'cl:MenuA'.'_'."ac:$argumentsService->ac".'_'."fp:$argumentsService->fp"],
        ]);
    }

    public static function userFirstPage(Collection $buttons, ArgumentsService $argumentsService):Collection
    {
        return $buttons->add([
//            ['text' => 'Обратная связь', 'callback_data' =>  $className . '_' . 'feedback'],
        ]);
    }

    public static function backPage(Collection $buttons, ArgumentsService $argumentsService):Collection
    {
        return $buttons->add([
//            ['text' => '◀️ Назад ◀️', 'callback_data' => $className . '_' . $action],
        ]);
    }
}
