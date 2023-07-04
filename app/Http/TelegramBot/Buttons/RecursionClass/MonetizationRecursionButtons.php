<?php

namespace App\Http\TelegramBot\Buttons\RecursionClass;

use App\Http\TelegramBot\Services\ArgumentsService;
use Illuminate\Support\Collection;

class MonetizationRecursionButtons
{


    public static function adminNotFirstPage(Collection $buttons, ArgumentsService $argumentsService):Collection
    {
        return $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->cl".'_'."ac:$argumentsService->ac".'_'."fp:$argumentsService->fp"],
            ['text' => '🕹 Product settings', 'callback_data' =>
                'cl:MenuM'.'_'."ac:$argumentsService->ac".'_'."fp:$argumentsService->fp"],
        ]);
    }
}
