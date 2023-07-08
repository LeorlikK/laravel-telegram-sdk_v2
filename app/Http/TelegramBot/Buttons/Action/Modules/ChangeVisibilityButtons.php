<?php

namespace App\Http\TelegramBot\Buttons\Action\Modules;

use App\Http\TelegramBot\Buttons;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\Folder;
use Illuminate\Support\Collection;

class ChangeVisibilityButtons extends Buttons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService): array
    {
        $folder = Folder::find($argumentsService->fp);
        if ($folder->blocked){
            $caption = '👁 пользователи у которых уровень допуска(role) ниже уровня, установленного папке, будут видеть её, но не смогут в неё попасть';
        }else{
            $caption = '👁‍🗨 пользователи у которых уровень допуска(role) ниже уровня, установленного папке, не будут видеть её';
        }

        $buttons->add([
            ['text' => '🔒 Scope', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "bk:$argumentsService->bk".'_'.
                "sw:Scope".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                "m:$argumentsService->m".'_'
            ],
        ]);
        $buttons->add([
            ['text' => $folder->blocked ? "👁 Unlock" : "👁‍🗨 Lock", 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "bk:$argumentsService->bk".'_'.
                "sw:Blocked".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                "m:$argumentsService->m".'_'
            ],
        ]);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return [$buttons, $caption];
    }

    public static function scope(Collection $buttons, ArgumentsService $argumentsService): array
    {
        $folder = Folder::find($argumentsService->fp);

        $arrayDenominators = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100];
        $selectedValue = self::findSelectedValue($folder->visibility, $arrayDenominators, 1);

        $buttons->add([
            ['text' => ($selectedValue === '0' ?  "✅" : "") . '10%', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:10'
            ],
            ['text' => ($selectedValue === '1' ?  "✅" : "") . '20%', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:20'
            ],
            ['text' => ($selectedValue === '2' ?  "✅" : "") . '30%', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:30'
            ],
            ['text' => ($selectedValue === '3' ?  "✅" : "") . '40%', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:40'
            ],
            ['text' => ($selectedValue === '4' ?  "✅" : "") . '50%', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:50'
            ],
        ]);
        $buttons->add([
            ['text' => ($selectedValue === '5' ?  "✅" : "") . '60%', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:60'
            ],
            ['text' => ($selectedValue === '6' ?  "✅" : "") . '70%', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:70'
            ],
            ['text' => ($selectedValue === '7' ?  "✅" : "") . '80%', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:80'
            ],
            ['text' => ($selectedValue === '8' ?  "✅" : "") . '90%', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:90'
            ],
            ['text' => ($selectedValue === '9' ?  "✅" : "") . '100%', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Confirm".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                'v:100'
            ],
        ]);

        $buttons->add([
            ['text' => '◀️ Cancel', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "bk:$argumentsService->bk".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                "m:$argumentsService->m".'_'.
                "s:1"
            ],
        ]);

        $caption = 'Выберите уровень доступа к папке или введите число от 0 до 100' . "\n\r" .
            ($selectedValue === null ? "Выбрано своё значение: " . $folder->visibility . '%' . " ✅" : "");

        return [$buttons, $caption];
    }
}
