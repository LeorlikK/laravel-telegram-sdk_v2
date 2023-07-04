<?php

namespace App\Http\TelegramBot\States\Make\Reports;

use App\Http\TelegramBot\Info\Exceptions\InputException;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Report;

class MakeAnswerReport
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
                $report = Report::find($this->stateMake->parentId);
                Report::create([
                    'from' => $this->stateMake->user->id,
                    'to_whom' => $report->from,
                    'theme' => $report->theme,
                    'message' => $this->stateMake->text,
                    'state' => 0,
                    'type' => 'answer',
                ]);

                $this->stateMake->argumentsService->er = '40';
                (new InputException($this->stateMake->user, $this->stateMake->update,
                    $this->stateMake->argumentsService))->handleCallbackQuery();
                return null;
            }
        }

        return null;
    }
}
