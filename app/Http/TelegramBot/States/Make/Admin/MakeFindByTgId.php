<?php

namespace App\Http\TelegramBot\States\Make\Admin;

use App\Http\TelegramBot\States\StateMake;
use App\Models\User;

class MakeFindByTgId
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        if ($this->stateMake->text){
            $user = User::where('tg_id', $this->stateMake->text)->first();
            if ($user){
                $this->stateMake->argumentsService->setArgument('sw', 'User');
                $this->stateMake->argumentsService->setArgument('fp', $user->id);
            }else{
                return '18';
            }
            return null;
        }

        return '8';
    }
}
