<?php

namespace App\Http\TelegramBot\Components\RecursionClass;

use App\Http\TelegramBot\Buttons\RecursionClass\MenuRecursionButtons;
use App\Http\TelegramBot\RecursionClass;

class MenuR extends RecursionClass
{
    public function main(): array
    {
        [$photo, $caption, $buttons] = $this->getRecursionFoldersAndButtons();

        $this->argumentsService->setArgument('cl' , class_basename($this));
        $this->argumentsService->setArgument('bk' , class_basename($this));
        if ($this->administrator){
            if (!$this->argumentsService->ac){
                $buttons = MenuRecursionButtons::adminFirstPage($buttons, $this->argumentsService);
            }else{
                $buttons = MenuRecursionButtons::adminNotFirstPage($buttons, $this->argumentsService);
            }

        }else{
            if ($this->argumentsService->ac){
                $buttons = MenuRecursionButtons::backPage($buttons, $this->argumentsService);
            }else{
                $buttons = MenuRecursionButtons::userFirstPage($buttons, $this->argumentsService);
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
