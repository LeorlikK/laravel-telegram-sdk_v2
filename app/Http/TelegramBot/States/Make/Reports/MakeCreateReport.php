<?php

namespace App\Http\TelegramBot\States\Make\Reports;

use App\Http\TelegramBot\Info\Exceptions\InputException;
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
            Report::create([
                'from' => $this->stateMake->user->id,
                'to_whom' => null,
                'theme' => $this->stateMake->parentId,
                'message' => $this->stateMake->text,
                'state' => 0,
                'type' => 'report',
            ]);

            $this->stateMake->argumentsService->er = '41';
            (new InputException($this->stateMake->user, $this->stateMake->update,
                $this->stateMake->argumentsService))->handleCallbackQuery();
            return null;
        }

        return null;
    }
}
