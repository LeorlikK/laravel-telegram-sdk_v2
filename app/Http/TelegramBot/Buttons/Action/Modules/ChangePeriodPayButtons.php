<?php

namespace App\Http\TelegramBot\Buttons\Action\Modules;

use App\Http\TelegramBot\PaginateButtons;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\Folder;
use App\Models\Product;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Self_;

class ChangePeriodPayButtons extends PaginateButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $folder = Folder::find($argumentsService->fp);
        $product = $folder->product;

        $buttons->add([
            ['text' => ($product->subscription/24 == 1 ?  "✅" : "") . '1 день', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:d1'
            ],
            ['text' => ($product->subscription/48 == 1 ?  "✅" : "") . '2 дня', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:d2'
            ],
            ['text' => ($product->subscription/72 == 1 ?  "✅" : "") . '3 дня', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:d3'
            ],
            ['text' => ($product->subscription/96 == 1 ?  "✅" : "") . '4 дня', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:d4'
            ],
        ]);
        $buttons->add([
            ['text' => ($product->subscription/168 == 1 ?  "✅" : "") . '1 неделя', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:w1'
            ],
            ['text' => ($product->subscription/336 == 1 ?  "✅" : "") . '2 недели', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:w2'
            ],
            ['text' => ($product->subscription/504 == 1 ?  "✅" : "") . '3 недели', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:w3'
            ],
            ['text' => ($product->subscription/672 == 1 ?  "✅" : "") . '4 недели', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:w4'
            ],

        ]);
        $buttons->add([
            ['text' => ($product->subscription/1344 == 1 ?  "✅" : "") . '2 месяца', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:m2'
            ],
            ['text' => ($product->subscription/2016 == 1 ?  "✅" : "") . '3 месяца', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:m3'
            ],
            ['text' => ($product->subscription/2688 == 1 ?  "✅" : "") . '4 месяца', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:m4'
            ],
            ['text' => ($product->subscription/3360 == 1 ?  "✅" : "") . '5 месяцев', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:m5'
            ],

        ]);
        $buttons->add([
            ['text' => ($product->subscription ? "" : "✅") . 'Бессрочная покупка', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:null'
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
