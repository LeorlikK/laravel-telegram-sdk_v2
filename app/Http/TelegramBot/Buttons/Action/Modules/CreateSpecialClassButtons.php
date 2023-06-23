<?php

namespace App\Http\TelegramBot\Buttons\Action\Modules;

use App\Http\TelegramBot\Services\ArgumentsService;
use Illuminate\Support\Collection;

class CreateSpecialClassButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService):Collection
    {
        $buttons->add([
            ['text' => 'Monetization', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:SClass1".'_'.
                "bk:$argumentsService->bk".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        $buttons->add([
            ['text' => 'Class Two', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:SClass2".'_'.
                "bk:$argumentsService->bk".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        $buttons->add([
            ['text' => 'Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"],
        ]);

        return $buttons;
    }

    public static function createSpecialClass(Collection $buttons, ArgumentsService $argumentsService):Collection
    {
        $buttons->add([
            ['text' => 'Cancel', 'callback_data' =>
                "cl:CreateClass".'_'.
                "bk:$argumentsService->bk".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"],
        ]);

        return $buttons;
    }
}
