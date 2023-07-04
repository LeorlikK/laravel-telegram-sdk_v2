<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\Info\Exceptions\InputException;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;

class MakeChangeNameFolder
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        $folder = Folder::find($this->stateMake->state->parentId);

        $pattern = '/\p{So}+/u';
        preg_match($pattern, $folder->name, $matches);

        $image = $matches[0] ?? null;
        $folder->name = $image ? $image . ' ' . $this->stateMake->text : $this->stateMake->text;
        $folder->save();

        $this->stateMake->argumentsService->er = '18';
        (new InputException($this->stateMake->user, $this->stateMake->update,
            $this->stateMake->argumentsService))->handleCallbackQuery();
        return null;
    }
}
