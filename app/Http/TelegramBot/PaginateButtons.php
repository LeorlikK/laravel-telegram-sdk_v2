<?php

namespace App\Http\TelegramBot;

use App\Http\TelegramBot\Services\ArgumentsService;
use Illuminate\Support\Collection;

abstract class PaginateButtons
{
    protected static function paginateNavigation(Collection $buttons, $totalFolder, $perPage, ArgumentsService $argumentsService, $buttonMinus, $buttonPlus): Collection
    {
        if ($totalFolder > $perPage){
            if ($totalFolder > ($perPage*$argumentsService->p) && $argumentsService->p > 1){
                $buttons->add([
                    ['text' => '◀️ Back', 'callback_data' =>
                        "cl:$argumentsService->cl".'_'.
                        "bk:$argumentsService->bk".'_'.
                        "sw:Add".'_'.
                        "bkS:$argumentsService->bkS".'_'.
                        "ac:N".'_'.
                        "fp:$argumentsService->fp".'_'.
                        "p:$buttonMinus"
                    ],
                    ['text' => 'Next ▶️ ', 'callback_data' =>
                        "cl:$argumentsService->cl".'_'.
                        "bk:$argumentsService->bk".'_'.
                        "sw:Add".'_'.
                        "bkS:$argumentsService->bkS".'_'.
                        "ac:N".'_'.
                        "fp:$argumentsService->fp".'_'.
                        "p:$buttonPlus"
                    ]
                ]);
            }elseif ($totalFolder <= ($perPage*$argumentsService->p) && $argumentsService->p > 1){
                $buttons->add([
                    ['text' => '◀️ Back', 'callback_data' =>
                        "cl:$argumentsService->cl".'_'.
                        "bk:$argumentsService->bk".'_'.
                        "sw:Add".'_'.
                        "bkS:$argumentsService->bkS".'_'.
                        "ac:N".'_'.
                        "fp:$argumentsService->fp".'_'.
                        "p:$buttonMinus"
                    ],
                ]);
            }elseif ($totalFolder > ($perPage*$argumentsService->p)){
                $buttons->add([
                    ['text' => 'Next ▶️', 'callback_data' =>
                        "cl:$argumentsService->cl".'_'.
                        "bk:$argumentsService->bk".'_'.
                        "sw:Add".'_'.
                        "bkS:$argumentsService->bkS".'_'.
                        "ac:N".'_'.
                        "fp:$argumentsService->fp".'_'.
                        "p:$buttonPlus"
                    ]
                ]);
            }
        }

        return $buttons;
    }
}
