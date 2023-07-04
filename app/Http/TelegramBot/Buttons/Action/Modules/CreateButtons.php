<?php

namespace App\Http\TelegramBot\Buttons\Action\Modules;

use App\Http\TelegramBot\Services\ArgumentsService;
use Illuminate\Support\Collection;

class CreateButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService):Collection
    {
        $buttons->add([
            ['text' => '◀️ Cancel', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "fp:$argumentsService->fp".'_'.
                "s:1"
            ],
        ]);

        return $buttons;
    }
}
