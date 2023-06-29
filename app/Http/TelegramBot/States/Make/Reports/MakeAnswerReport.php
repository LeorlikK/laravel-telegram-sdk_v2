<?php

namespace App\Http\TelegramBot\States\Make\Reports;

use App\Http\TelegramBot\Exceptions\UserAlertException;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
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
                    'user_id' => $report->user_id,
                    'theme' => $report->theme,
                    'message' => $this->stateMake->text,
                    'state' => 0,
                    'type' => 'answer',
                ]);

                return null;
            }
        }

        return null;
    }
}
