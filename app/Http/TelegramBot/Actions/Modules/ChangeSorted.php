<?php

namespace App\Http\TelegramBot\Actions\Modules;

use App\Http\TelegramBot\ActionModuleClass;
use App\Http\TelegramBot\Buttons\Action\Modules\ChangeCaptionButtons;
use App\Http\TelegramBot\Buttons\Action\Modules\ChangeNameButtons;
use App\Http\TelegramBot\Buttons\Action\Modules\ChangeSortedButtons;
use App\Http\TelegramBot\DefaultClass;
use App\Http\TelegramBot\States\StateCreate;
use App\Http\TelegramBot\States\StateMake;

class ChangeSorted extends DefaultClass
{
    public function main(): array
    {
        $buttons = collect();

        switch (true){
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'ChangeSorted' . $this->argumentsService->m);
                $buttons = ChangeSortedButtons::defaultButtons($buttons, $this->argumentsService);
                $caption = $this->caption('');
                $photo = $this->photo(null);
                $reply_markup = $this->replyMarkup($buttons);
                break;
        }

        return [$photo, $caption, $reply_markup];
    }

    public function handleCallbackQuery(): void
    {
        switch ($this->argumentsService->sw){
            case 'Confirm':
                var_dump('SORTED CONFIRM');
                (new StateMake($this->update, $this->user, $this->argumentsService, $this->user->state))->make();
                break;
            default:
                $this->callbackUpdate();
                break;
        }
    }
}
