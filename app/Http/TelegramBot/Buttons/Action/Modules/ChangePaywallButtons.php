<?php

namespace App\Http\TelegramBot\Buttons\Action\Modules;

use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\Folder;
use Illuminate\Support\Collection;

class ChangePaywallButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $folder = Folder::find($argumentsService->fp);

        $buttons->add([
            ['text' => $folder->blockedPay ? "❌ Off" : "✅ On", 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "bk:$argumentsService->bk".'_'.
                "sw:Blocked".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp".'_'.
                "m:$argumentsService->m".'_'
            ],
        ]);

        $buttons->add([
            ['text' => 'Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }
}
