<?php

namespace App\Http\TelegramBot\Buttons\RecursionClass;

use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\User;
use Illuminate\Support\Collection;

class MenuRecursionButtons
{
    public static function adminFirstPage(Collection $buttons, ArgumentsService $argumentsService, User $user):Collection
    {
        return $buttons->add([
            ['text' => '🔑 Admin panel' . ($user->countAnswerReportState('report')), 'callback_data' =>
                'cl:AdminMenu'],
            ['text' => '🕹 Actions', 'callback_data' =>
                'cl:MenuA'.'_'."ac:$argumentsService->ac".'_'."fp:$argumentsService->fp".'_'."p:$argumentsService->p"],
        ]);
    }

    public static function adminNotFirstPage(Collection $buttons, ArgumentsService $argumentsService):Collection
    {
        return $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->cl".'_'."ac:$argumentsService->ac".'_'."fp:$argumentsService->fp".'_'."p:$argumentsService->p"],
            ['text' => '🕹 Actions', 'callback_data' =>
                'cl:MenuA'.'_'."ac:$argumentsService->ac".'_'."fp:$argumentsService->fp".'_'."p:$argumentsService->p"],
        ]);
    }

    public static function userFirstPage(Collection $buttons, ArgumentsService $argumentsService, User $user):Collection
    {
        return $buttons->add([
            ['text' => '🔑 Personal Area' . ($user->countAnswerReportState('answer')), 'callback_data' =>
                'cl:AreaMenu'],
        ]);
    }

    public static function backPage(Collection $buttons, ArgumentsService $argumentsService):Collection
    {
        return $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->cl".'_'."ac:$argumentsService->ac".'_'."fp:$argumentsService->fp".'_'."p:$argumentsService->p"],
        ]);
    }
}
