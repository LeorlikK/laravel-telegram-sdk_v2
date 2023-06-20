<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\State;
use App\Models\Tab;
use App\Models\User;

class MakeDeleteFolder
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
                $backId = Folder::find($this->stateMake->parentId)->parentId;
                $this->stateMake->deleteFolderChildren($this->stateMake->parentId);
                $this->stateMake->parentId = $backId;
            }
        }

        return null;
    }
}
