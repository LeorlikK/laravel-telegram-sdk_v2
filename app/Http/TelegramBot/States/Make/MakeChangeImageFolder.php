<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\Services\MediaConverter;
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
        $media = MediaConverter::messageMediaConverter($this->stateMake->update->getMessage());
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

            return null;

        }elseif ($media && $media['type'] === 'document'){
            return 'Сохранение формата из документа находится в разработке';
        }else{
            return 'Неудовлетворимый формат изображения';
        }
    }
}
