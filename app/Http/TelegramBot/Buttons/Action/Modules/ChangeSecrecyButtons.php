<?php

namespace App\Http\TelegramBot\Buttons\Action\Modules;

use App\Http\TelegramBot\Services\ArgumentsService;
use App\Http\TelegramBot\Services\RemainingTimeService;
use App\Models\Folder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ChangeSecrecyButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService): array
    {
        $folder = Folder::find($argumentsService->fp);



        $caption = 'Выберите время до которого необходимо скрыть папку или укажите его в формате Y-m-d H:i:s';

        if ($folder->displayViewBool()){
            $str = RemainingTimeService::getTimeFromHours($folder->display);
            $caption .=  "\n\r" .
            ($folder->display ? "Скрыта до: " . "✅ " . $folder->display . ("( осталось $str )") : "");
        }

        $buttons->add([
            ['text' => '1 день', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:d1'
            ],
            ['text' => '2 дня', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:d2'
            ],
            ['text' => '3 дня', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:d3'
            ],
            ['text' => '4 дня', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:d4'
            ],
        ]);
        $buttons->add([
            ['text' => '1 неделя', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:w1'
            ],
            ['text' => '2 недели', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:w2'
            ],
            ['text' => '3 недели', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:w3'
            ],
            ['text' => '4 недели', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:w4'
            ],
        ]);
        $buttons->add([
            ['text' => '❌ Удалить метку времени', 'callback_data' =>
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

        return [$buttons, $caption];
    }
}
