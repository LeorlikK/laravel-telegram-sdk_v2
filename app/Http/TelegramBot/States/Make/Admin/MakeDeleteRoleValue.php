<?php

namespace App\Http\TelegramBot\States\Make\Admin;

use App\Http\TelegramBot\States\StateMake;
use App\Models\Role;
use App\Models\User;

class MakeDeleteRoleValue
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        if ($this->stateMake->argumentsService->v === 'del'){
            /**
             * удалить всё ключи у пользователей, если их роль совпадает с удялемой или заменить на новую
             */
            User::where('role_id', $this->stateMake->parentId)->update(['role_id' => 2]);
            Role::where('id', $this->stateMake->parentId)->delete();

            return null;

        }else {
            return '11';
        }
    }
}
