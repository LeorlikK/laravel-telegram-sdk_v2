<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\Tab;

class MakeChangeCaptionFolder
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        if ($this->stateMake->parentId == 0){
            $tab = Tab::where('name', class_basename($this->stateMake->state->TabClass))->first();
            if (strtolower($this->stateMake->text) === 'null'){
                $tab->caption = null;
            }else{
                $tab->caption = $this->stateMake->text;
            }
            $tab->save();
        }else{
            $folder = Folder::find($this->stateMake->state->parentId);
            if (strtolower($this->stateMake->text) === 'null'){
                $folder->caption = null;
            }else{
                $folder->caption = $this->stateMake->text;
            }
            $folder->save();
        }

        return null;
    }
}
