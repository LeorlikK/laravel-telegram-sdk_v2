<?php

namespace App\Http\TelegramBot\Components\RecursionClass;

use App\Http\TelegramBot\Buttons\RecursionClass\MenuRecursionButtons;
use App\Http\TelegramBot\Buttons\RecursionClass\MonetizationRecursionButtons;
use App\Http\TelegramBot\RecursionClass;
use App\Models\Folder;

class MenuR extends RecursionClass
{
    public function main(): array
    {
        [$photo, $caption, $buttons] = $this->getRecursionFoldersAndButtons();

        $this->argumentsService->setArgument('cl' , class_basename($this));
        $this->argumentsService->setArgument('bk' , class_basename($this));
        $folderAction = Folder::find($this->argumentsService->fp)?->action;
        if ($this->administrator){
            if (!$this->argumentsService->ac){
                $buttons = MenuRecursionButtons::adminFirstPage($buttons, $this->argumentsService, $this->user);
            }else{
                if ($folderAction){
                    switch (true){
                        case $folderAction === 'MenuM':
                            $buttons = MonetizationRecursionButtons::adminNotFirstPage($buttons, $this->argumentsService);
                            break;
                        case $folderAction === 'MenuR':
                            $buttons = MenuRecursionButtons::adminNotFirstPage($buttons, $this->argumentsService);
                            break;
                    }
                }
            }

        }else{
            if ($this->argumentsService->ac){
                $buttons = MenuRecursionButtons::backPage($buttons, $this->argumentsService);
            }else{
                $buttons = MenuRecursionButtons::userFirstPage($buttons, $this->argumentsService, $this->user);
            }
        }

        $reply_markup = $this->replyMarkup($buttons);

        return [$photo, $caption, $reply_markup];
    }

    public function handleCallbackQuery(): void
    {
        switch ($this->argumentsService->sw){
            default:
                $this->callbackUpdate();
                break;
        }
    }
}
