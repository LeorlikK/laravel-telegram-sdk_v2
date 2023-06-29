<?php

namespace App\Http\TelegramBot\States\Make\Admin;

use App\Http\TelegramBot\States\StateMake;
use App\Models\Role;

class MakeChangeRoleName
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        if (preg_match('/^[a-zA-Z0-9]+$/', $this->stateMake->text)){
            Role::where('id', $this->stateMake->parentId)->update([
                'name' => $this->stateMake->text
            ]);

            $this->stateMake->argumentsService->setArgument('sw', 'СhoiceChangeRole');
            return null;

        }else {
            return '10';
        }
    }
}
