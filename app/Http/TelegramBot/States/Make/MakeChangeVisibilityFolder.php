<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\Tab;
use Carbon\Carbon;

class MakeChangeVisibilityFolder
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        if ($this->stateMake->argumentsService->v){
            $folder = Folder::find($this->stateMake->parentId);
            $folder->visibility = $this->stateMake->argumentsService->v;
            $folder->save();
            return null;
        }elseif (is_numeric($this->stateMake->text)){
            if ($this->stateMake->text > 100){
                return '4';
            }elseif ($this->stateMake->text < 0){
                return '5';
            }else{
                $folder = Folder::find($this->stateMake->parentId);
                $folder->visibility = (int)$this->stateMake->argumentsService->v;
                $folder->save();
                return null;
            }
        }else{
            return '6';
        }
    }
}
