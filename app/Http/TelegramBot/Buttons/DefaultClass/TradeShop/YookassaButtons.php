<?php

namespace App\Http\TelegramBot\Buttons\DefaultClass\TradeShop;

use App\Http\TelegramBot\Services\ArgumentsService;
use Illuminate\Support\Collection;
use Telegram\Bot\Api;
use Telegram\Bot\Laravel\Facades\Telegram;

class YookassaButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        return $buttons;
    }

    public static function payButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => 'End Pay Back', 'callback_data' =>
                "cl:IA".'_'.
                "sw:ConfirmPayC".'_'.
                "bk:$argumentsService->bk".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                "er:8"],
        ]);

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
