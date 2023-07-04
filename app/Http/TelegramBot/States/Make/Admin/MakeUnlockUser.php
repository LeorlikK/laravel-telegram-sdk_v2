<?php

namespace App\Http\TelegramBot\States\Make\Admin;

use App\Http\TelegramBot\Info\Exceptions\InputException;
use App\Http\TelegramBot\States\StateMake;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class MakeUnlockUser
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        if ($this->stateMake->argumentsService->fp){
            $user = User::where('id', $this->stateMake->parentId)->first();
            $user->update(['is_blocked' => false]);
            Cache::forget($user->tg_id);

            $this->stateMake->argumentsService->er = '37';
            (new InputException($this->stateMake->user, $this->stateMake->update,
                $this->stateMake->argumentsService))->handleCallbackQuery();
            return null;
        }

        return '8';
    }
}
