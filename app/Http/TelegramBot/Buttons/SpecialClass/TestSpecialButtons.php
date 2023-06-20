<?php

namespace App\Http\TelegramBot\Buttons\SpecialClass;

use App\Http\TelegramBot\Services\ArgumentsService;
use Illuminate\Support\Collection;

class TestSpecialButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService):Collection
    {
        $buttons->add([
            ['text' => 'Ура!!! Мы на месте!', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:TestClass".'_'.
                "bk:$argumentsService->bk".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"],
        ]);

        $buttons->add([
            ['text' => 'Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"],
        ]);

        return $buttons;
    }
}
