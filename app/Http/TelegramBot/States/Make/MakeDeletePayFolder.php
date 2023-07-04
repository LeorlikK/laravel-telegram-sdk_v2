<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\Info\Exceptions\InputException;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;

class MakeDeletePayFolder
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
                $link = false;
                $this->stateMake->deleteFolderChildren($this->stateMake->parentId, $link);
                $this->stateMake->parentId = $backId;

                $this->stateMake->argumentsService->er = '26';
                (new InputException($this->stateMake->user, $this->stateMake->update,
                    $this->stateMake->argumentsService))->handleCallbackQuery();
                return null;
            }
        }

        return null;
    }
}
