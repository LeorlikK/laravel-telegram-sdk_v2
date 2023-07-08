<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\Info\Alerts\InputAlert;
use App\Http\TelegramBot\Services\MediaConverterService;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\Tab;

class MakeChangeImageFolder
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        $media = MediaConverterService::messageMediaConverter($this->stateMake->update->getMessage());
        if (($media && $media['type'] === 'photo') || $this->stateMake->text === 'null'){
            if ($this->stateMake->parentId == 0){
                $tab = Tab::where('name', class_basename($this->stateMake->state->TabClass))->first();
                if (strtolower($this->stateMake->text) === 'null'){
                    $tab->media = null;
                }else{
                    $tab->media = $media['file_id'];
                }
                $tab->save();
            }else{
                $folder = Folder::find($this->stateMake->parentId);
                if (strtolower($this->stateMake->text) === 'null') {
                    $folder->media = null;
                }else{
                    $folder->media = $media['file_id'];
                }
                $folder->save();
            }

            $this->stateMake->argumentsService->er = '17';
            (new InputAlert($this->stateMake->user, $this->stateMake->update,
                $this->stateMake->argumentsService))->handleCallbackQuery();
            return null;

        }elseif ($media && $media['type'] === 'document'){
            return '1';
        }else{
            return '2';
        }
    }
}
