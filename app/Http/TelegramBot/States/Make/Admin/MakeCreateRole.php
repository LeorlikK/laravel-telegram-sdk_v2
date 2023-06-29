<?php

namespace App\Http\TelegramBot\States\Make\Admin;

use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class MakeCreateRole
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        if (preg_match('/^[a-zA-Z0-9]+=\\d+$/', $this->stateMake->text)){
            $explode = explode('=', $this->stateMake->text);
            $name = $explode[0];
            $visibility = $explode[1];

            if ($visibility > 100){
                return '4';
            }elseif ($visibility < 0){
                return '5';
            }else{
                Role::create([
                    'name' => $name,
                    'visibility' => $visibility,
                ]);

                return null;
            }
        }else {
            return '9';
        }
    }
}
