<?php

namespace App\Http\TelegramBot\States\Make\Admin;

use App\Http\TelegramBot\Info\Alerts\InputAlert;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class MakeDeleteRole
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
            $usersTgId = User::where('role_id', $this->stateMake->parentId)->pluck('tg_id');
            User::where('role_id', $this->stateMake->parentId)->update(['role_id' => 2]);
            Role::where('id', $this->stateMake->parentId)->delete();
            Cache::deleteMultiple($usersTgId);

            $this->stateMake->argumentsService->er = '36';
            (new InputAlert($this->stateMake->user, $this->stateMake->update,
                $this->stateMake->argumentsService))->handleCallbackQuery();
            return null;

        }else {
            return '11';
        }
    }
}
