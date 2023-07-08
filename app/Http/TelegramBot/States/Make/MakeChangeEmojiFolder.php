<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\Info\Alerts\InputAlert;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;

class MakeChangeEmojiFolder
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        $folder = Folder::find($this->stateMake->state->parentId);
        $pattern = '/[^\p{So}]+/u';
        preg_match($pattern, $folder->name, $matches);

        $oldText = $matches[0] ?? '';

        if (strtolower($this->stateMake->text) === 'null'){
            $folder->name = $oldText;
        }else{
            $folder->name = $oldText ? $this->stateMake->text . ' ' . $oldText : $this->stateMake->text;
        }
        $folder->save();

        $this->stateMake->argumentsService->er = '16';
        (new InputAlert($this->stateMake->user, $this->stateMake->update,
            $this->stateMake->argumentsService))->handleCallbackQuery();
        return null;
    }
}
