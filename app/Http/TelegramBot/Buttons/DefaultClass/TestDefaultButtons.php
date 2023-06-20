<?php

namespace App\Http\TelegramBot\Buttons\DefaultClass;

use App\Http\TelegramBot\Services\ArgumentsService;
use Illuminate\Support\Collection;

class TestDefaultButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService):Collection
    {
        $buttons->add([
            ['text' => 'One', 'callback_data' =>  "class:$argumentsService->class".'_'."switch:One"],
            ['text' => 'Two', 'callback_data' =>  "class:$argumentsService->class".'_'."switch:Two"]
        ]);

        return $buttons;
    }

    public static function oneButtons(Collection $buttons, ArgumentsService $argumentsService):Collection
    {
        $buttons->add([
            ['text' => 'OneOneOne', 'callback_data' =>  "class:$argumentsService->class".'_'."switch:One"],
        ]);

        $buttons->add([
            ['text' => 'Back', 'callback_data' =>  "class:$argumentsService->back".
                '_'."switch:$argumentsService->backSwitch"],
        ]);

        return $buttons;
    }
}
