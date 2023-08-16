<?php

namespace App\Http\TelegramBot\States;

use App\Http\TelegramBot\Aliases;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\State;
use App\Models\User;
use Telegram\Bot\Objects\Update;

class StateCreate
{
    public static function createState(
        Update $update,
        User $user,
        ArgumentsService $argumentsService,
        string $action,
        ?string $v1=null,
        ?string $v2=null,
        ?string $v3=null,
    ):User
    {
        if ($user->state) $user->state()->delete();

        State::create([
            'user_id' => $user->id,
            'messageId' => $update->getMessage()->get('message_id'),
            'action' => $action,
            'TabClass' => Aliases::getFullNameSpace($argumentsService->bkS),
            'parentId' => $argumentsService->fp ?? 0,
            'v1' => $v1,
            'v2' => $v2,
            'v3' => $v3,
        ]);

        return $user;
    }
}
