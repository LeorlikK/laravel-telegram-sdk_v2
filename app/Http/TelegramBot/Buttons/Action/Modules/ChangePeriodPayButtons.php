<?php

namespace App\Http\TelegramBot\Buttons\Action\Modules;

use App\Http\TelegramBot\Buttons;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\Folder;
use Illuminate\Support\Collection;

class ChangePeriodPayButtons extends Buttons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService): array
    {
        $folder = Folder::with('product')->find($argumentsService->fp);
        $product = $folder->product;

        $arrayDenominators = [24, 48, 72, 96, 168, 336, 504, 672, 1344, 2016, 2688, 3360];
        $selectedValue = self::findSelectedValue($product->subscription, $arrayDenominators, 1);

        $buttons->add([
            ['text' => ($selectedValue === '0' ?  "✅" : "") . '1 день', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:d1'
            ],
            ['text' => ($selectedValue === '1' ?  "✅" : "") . '2 дня', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:d2'
            ],
            ['text' => ($selectedValue === '2' ?  "✅" : "") . '3 дня', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:d3'
            ],
            ['text' => ($selectedValue === '3' ?  "✅" : "") . '4 дня', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:d4'
            ],
        ]);
        $buttons->add([
            ['text' => ($selectedValue === '4' ?  "✅" : "") . '1 неделя', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:w1'
            ],
            ['text' => ($selectedValue === '5' ?  "✅" : "") . '2 недели', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:w2'
            ],
            ['text' => ($selectedValue === '6' ?  "✅" : "") . '3 недели', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:w3'
            ],
            ['text' => ($selectedValue === '7' ?  "✅" : "") . '4 недели', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:w4'
            ],

        ]);
        $buttons->add([
            ['text' => ($selectedValue === '8' ?  "✅" : "") . '2 месяца', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:m2'
            ],
            ['text' => ($selectedValue === '9' ?  "✅" : "") . '3 месяца', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:m3'
            ],
            ['text' => ($selectedValue === '10' ?  "✅" : "") . '4 месяца', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:m4'
            ],
            ['text' => ($selectedValue === '11' ?  "✅" : "") . '5 месяцев', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:m5'
            ],

        ]);
        $buttons->add([
            ['text' => (!$product->subscription && !$selectedValue ? "✅" : "") . 'Бессрочная покупка', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:null'
            ],
        ]);

        $buttons->add([
            ['text' => '◀️ Cancel', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "fp:$argumentsService->fp".'_'.
                "s:1"
            ],
        ]);

        $caption = "Выберите срок на который пользователь приобретет продукт при покупке или укажите его в формате ('1h', '1d', '1w', '1m')" . "\n\r" .
            ($product->subscription && $selectedValue === null ? "Выбрано кастомное время: " . $product->subscription . ' h' . " ✅" : "");

        return [$buttons, $caption];
    }
}
