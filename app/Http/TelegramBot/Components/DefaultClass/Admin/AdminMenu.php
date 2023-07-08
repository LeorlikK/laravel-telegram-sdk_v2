<?php

namespace App\Http\TelegramBot\Components\DefaultClass\Admin;

use App\Http\TelegramBot\Buttons\DefaultClass\Admin\AdminMenuButtons;
use App\Http\TelegramBot\DefaultClass;

class AdminMenu extends DefaultClass
{
    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw){
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , 'MenuR');
                $buttons = AdminMenuButtons::defaultButtons($buttons, $this->argumentsService, $this->user);
                $caption = $this->caption("Панель администратора");
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
