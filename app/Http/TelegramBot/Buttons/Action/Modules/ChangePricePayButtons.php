<?php

namespace App\Http\TelegramBot\Buttons\Action\Modules;

use App\Http\TelegramBot\PaginateButtons;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\Folder;
use App\Models\Product;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Self_;

class ChangePricePayButtons extends PaginateButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $product = Product::where('folder_id', $argumentsService->fp)->first();

        $buttons->add([
            ['text' => ($product->currency === 'rub' ? "✅ " : "") . "Рубли", 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "bk:$argumentsService->bk".'_'.
                "sw:Val".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp".'_'.
                "m:$argumentsService->m"."1"
            ],
        ]);
        $buttons->add([
            ['text' => ($product->currency === 'usd' ? "✅ " : "") . "Доллары", 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "bk:$argumentsService->bk".'_'.
                "sw:Val".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp".'_'.
                "m:$argumentsService->m"."2"
            ],
        ]);
        $buttons->add([
            ['text' => ($product->currency === 'eur' ? "✅ " : "") . "Евро", 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "bk:$argumentsService->bk".'_'.
                "sw:Val".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp".'_'.
                "m:$argumentsService->m"."3"
            ],
        ]);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }

    public static function indicatePrice(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }
}
