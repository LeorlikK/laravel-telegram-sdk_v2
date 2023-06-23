<?php

namespace App\Http\TelegramBot\Actions\Modules;

use App\Http\TelegramBot\ActionModuleClass;
use App\Http\TelegramBot\Buttons\Action\Modules\ChangeSecrecyButtons;
use App\Http\TelegramBot\DefaultClass;
use App\Http\TelegramBot\States\StateCreate;
use App\Http\TelegramBot\States\StateMake;

class ChangeSecrecy extends DefaultClass
{
    public function main(): array
    {
        $buttons = collect();

        switch (true){
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'ChangeSecrecy' . $this->argumentsService->m);
                $buttons = ChangeSecrecyButtons::defaultButtons($buttons, $this->argumentsService);
                $caption = $this->caption('Выберите время до которого необходимо скрыть или укажите его в формате Y-m-d H:i:s');
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
            case 'Confirm':
                (new StateMake($this->update, $this->user, $this->argumentsService, $this->user->state))->make();
                break;
            default:
                $this->callbackUpdate();
                break;
        }
    }
}
