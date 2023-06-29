<?php

namespace App\Http\TelegramBot\States\Make\Reports;

use App\Http\TelegramBot\Exceptions\UserAlertException;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\Report;

class MakeDeleterReport
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        if ($this->stateMake->argumentsService->v === 'del'){
            if ($this->stateMake->parentId != 0){
                $this->stateMake->argumentsService->setArgument('sw', null);
                Report::find($this->stateMake->parentId)->delete();

                return null;
            }
        }

        return null;
    }
}
