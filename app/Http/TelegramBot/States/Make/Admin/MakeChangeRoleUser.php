<?php

namespace App\Http\TelegramBot\States\Make\Admin;

use App\Http\TelegramBot\States\StateMake;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class MakeChangeRoleUser
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        if ($this->stateMake->argumentsService->v){
            $user = User::where('id', $this->stateMake->parentId)->first();
            $user->update(['role_id' => $this->stateMake->argumentsService->v]);
            Cache::forget($user->tg_id);
            return null;
        }

        return '7';
    }
}
