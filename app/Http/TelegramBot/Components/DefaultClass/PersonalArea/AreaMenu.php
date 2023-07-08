<?php

namespace App\Http\TelegramBot\Components\DefaultClass\PersonalArea;

use App\Http\TelegramBot\Buttons\DefaultClass\PersonalArea\AreaMenuButtons;
use App\Http\TelegramBot\DefaultClass;

class AreaMenu extends DefaultClass
{
    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw){
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , 'MenuR');
                $buttons = AreaMenuButtons::defaultButtons($buttons, $this->argumentsService, $this->user);
                $caption = $this->caption("Личный кабинет");
                break;
        }

        $caption = $caption ?? $this->caption('');
        $photo = $photo ?? $this->photo(null);
        $reply_markup = $reply_markup ?? $this->replyMarkup($buttons);

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
