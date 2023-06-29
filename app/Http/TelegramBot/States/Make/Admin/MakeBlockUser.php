<?php

namespace App\Http\TelegramBot\States\Make\Admin;

use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class MakeBlockUser
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
            $user->update(['is_blocked' => true]);
            Cache::forget($user->tg_id);
            return null;
        }

        return '8';
    }
}
