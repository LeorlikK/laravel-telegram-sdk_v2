<?php

namespace App\Http\TelegramBot\States\Make\Reports;

use App\Http\TelegramBot\Info\Alerts\InputAlert;
use App\Http\TelegramBot\States\StateMake;
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

                $this->stateMake->argumentsService->er = '42';
                (new InputAlert($this->stateMake->user, $this->stateMake->update,
                    $this->stateMake->argumentsService))->handleCallbackQuery();
                return null;
            }
        }

        return null;
    }
}
