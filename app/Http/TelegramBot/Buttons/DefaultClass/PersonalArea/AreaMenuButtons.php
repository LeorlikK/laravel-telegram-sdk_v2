<?php

namespace App\Http\TelegramBot\Buttons\DefaultClass\PersonalArea;

use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\User;
use Illuminate\Support\Collection;

class AreaMenuButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService, User $user): Collection
    {
        $buttons->add([
            ['text' => '🛍 Purchased Products', 'callback_data' =>
                "cl:AreaPurchased".'_'.
                "fp:$argumentsService->fp".'_'
            ],
        ]);
        $buttons->add([
            ['text' => '📣 Feedback' . ($user->countAnswerReportState('answer')), 'callback_data' =>
                "cl:AreaFeedback".'_'.
                "fp:$argumentsService->fp".'_'
            ],
        ]);


        $buttons->add([
            ['text' => '◀️ Back ◀️', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }
}
