<?php

namespace App\Http\TelegramBot\Buttons\RecursionClass;

use App\Http\TelegramBot\Services\ArgumentsService;
use Illuminate\Support\Collection;

class MonetizationRecursionButtons
{


    public static function adminNotFirstPage(Collection $buttons, ArgumentsService $argumentsService):Collection
    {
        return $buttons->add([
            ['text' => '◀️ Назад ◀️', 'callback_data' =>
                "cl:$argumentsService->cl".'_'."ac:$argumentsService->ac".'_'."fp:$argumentsService->fp"],
            ['text' => '🕹 Настройки продукта', 'callback_data' =>
                'cl:MenuM'.'_'."ac:$argumentsService->ac".'_'."fp:$argumentsService->fp"],
        ]);
    }
}
