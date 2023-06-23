<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\Exceptions\BlockedFolderPayException;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\Product;
use App\Models\State;
use App\Models\Tab;
use App\Models\User;

class MakeDeletePayProduct
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
                if ($link){
                    $this->stateMake->argumentsService->er = $link;
                    (new BlockedFolderPayException($this->stateMake->user, $this->stateMake->update,
                        $this->stateMake->argumentsService))->handleCallbackQuery();
                }

                $this->stateMake->parentId = $backId;
            }
        }

        return null;
    }
}
