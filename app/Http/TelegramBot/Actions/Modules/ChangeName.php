<?php

namespace App\Http\TelegramBot\Actions\Modules;

use App\Http\TelegramBot\Buttons\Action\Modules\ChangeNameButtons;
use App\Http\TelegramBot\DefaultClass;
use App\Http\TelegramBot\States\StateCreate;

class ChangeName extends DefaultClass
{
    public function main(): array
    {
        $buttons = collect();

        switch (true){
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'ChangeName' . $this->argumentsService->m);
                $buttons = ChangeNameButtons::defaultButtons($buttons, $this->argumentsService);
                $caption = $this->caption('Введите новое имя');
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
