<?php

namespace App\Http\TelegramBot\States\Make\Admin;

use App\Http\TelegramBot\States\StateMake;
use App\Models\Role;

class MakeChangeRoleValue
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        if (preg_match('/^\d+$/', $this->stateMake->text, $matches)){

            if ($matches[0] > 100){
                return '4';
            }elseif ($matches[0] < 0){
                return '5';
            }else{
                Role::where('id', $this->stateMake->parentId)->update([
                    'visibility' => $this->stateMake->text
                ]);

                $this->stateMake->argumentsService->setArgument('sw', 'СhoiceChangeRole');
                return null;
            }

        }else {
            return '9';
        }
    }
}
