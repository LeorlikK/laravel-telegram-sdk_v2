<?php

namespace App\Http\TelegramBot\States\Make\Reports;

use App\Http\TelegramBot\Exceptions\UserAlertException;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\Report;

class MakeCreateReport
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        if ($this->stateMake->text){
            if ($this->stateMake->parentId != 0){
                Report::create([
                    'user_id' => $this->stateMake->user->id,
                    'theme' => $this->stateMake->parentId,
                    'message' => $this->stateMake->text,
                    'state' => 0,
                    'type' => 'report',
                ]);

                return null;
            }
        }

        return null;
    }
}
