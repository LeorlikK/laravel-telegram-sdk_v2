<?php

namespace App\Http\TelegramBot\Actions\Modules;

use App\Http\TelegramBot\Buttons\Action\Modules\CreateSpecialClassButtons;
use App\Http\TelegramBot\DefaultClass;
use App\Http\TelegramBot\States\StateCreate;

class CreateSpecialClass extends DefaultClass
{
    public function main(): array
    {
        $buttons = collect();

        switch (true){
            case str_starts_with($this->argumentsService->sw, 'SClass'):
                StateCreate::createState($this->update, $this->user, $this->argumentsService, $this->argumentsService->sw);
                $buttons = CreateSpecialClassButtons::createSpecialClass($buttons, $this->argumentsService);
                $caption = $this->caption('Введите название класса');
                break;
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $buttons = CreateSpecialClassButtons::defaultButtons($buttons, $this->argumentsService);
                $caption = $this->caption('Выберите класс для создания');
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
