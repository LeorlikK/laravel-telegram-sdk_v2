<?php

namespace App\Http\TelegramBot\Buttons\DefaultClass\Admin;

use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\User;
use Illuminate\Support\Collection;

class AdminMenuButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService, User $user): Collection
    {
        $buttons->add([
            ['text' => '👥 Users', 'callback_data' =>
                "cl:AdminUsers".'_'.
                "fp:$argumentsService->fp".'_'
            ],
        ]);
        $buttons->add([
            ['text' => '📝 Roles', 'callback_data' =>
                "cl:AdminRoles".'_'.
                "fp:$argumentsService->fp".'_'
            ],
        ]);
        $buttons->add([
            ['text' => '📣 Reports' . ($user->countAnswerReportState('report')), 'callback_data' =>
                "cl:AdminReports".'_'.
                "fp:$argumentsService->fp".'_'
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
}
