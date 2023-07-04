<?php

namespace App\Http\TelegramBot\Buttons\Action\Modules;

use App\Http\TelegramBot\Buttons;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\Product;
use Illuminate\Support\Collection;

class ChangePricePayButtons extends Buttons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $product = Product::where('folder_id', $argumentsService->fp)->first();

        $buttons->add([
            ['text' => ($product->currency === 'rub' ? "✅ " : "") . "Rubles", 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "bk:$argumentsService->bk".'_'.
                "sw:Val".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                "m:$argumentsService->m"."1"
            ],
        ]);
        $buttons->add([
            ['text' => ($product->currency === 'usd' ? "✅ " : "") . "Dollars", 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "bk:$argumentsService->bk".'_'.
                "sw:Val".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                "m:$argumentsService->m"."2"
            ],
        ]);
        $buttons->add([
            ['text' => ($product->currency === 'eur' ? "✅ " : "") . "Euro", 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "bk:$argumentsService->bk".'_'.
                "sw:Val".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                "m:$argumentsService->m"."3"
            ],
        ]);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }

    public static function indicatePrice(Collection $buttons, ArgumentsService $argumentsService): Collection
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
