<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\Info\Alerts\InputAlert;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;

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

            $this->stateMake->argumentsService->er = '23';
            (new InputAlert($this->stateMake->user, $this->stateMake->update,
                $this->stateMake->argumentsService))->handleCallbackQuery();
            return null;
        }elseif (is_numeric($this->stateMake->text)){
            if ($this->stateMake->text > 100){
                return '4';
            }elseif ($this->stateMake->text < 0){
                return '5';
            }else{
                $folder = Folder::find($this->stateMake->parentId);
                $folder->visibility = (int)$this->stateMake->text;
                $folder->save();

                $this->stateMake->argumentsService->er = '23';
                (new InputAlert($this->stateMake->user, $this->stateMake->update,
                    $this->stateMake->argumentsService))->handleCallbackQuery();
                return null;
            }
        }else{
            return '6';
        }
    }
}
