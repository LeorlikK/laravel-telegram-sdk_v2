<?php

namespace App\Http\TelegramBot\Components\SpecialClass;

use App\Http\TelegramBot\Buttons\SpecialClass\PayProductSpecialButtons;
use App\Http\TelegramBot\DefaultClass;
use App\Http\TelegramBot\States\StateCreate;
use App\Http\TelegramBot\States\StateMake;

class PayS extends DefaultClass
{

    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw) {
            default:
                $this->argumentsService->setArgument('cl', class_basename($this));
                $this->argumentsService->setArgument('bk' , 'MenuR');
                $this->argumentsService->setArgument('bkS' , 'MenuR');
                $this->argumentsService->setArgument('m' , 'C');
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'PayProduct' . $this->argumentsService->m);
                $buttons = PayProductSpecialButtons::defaultButtons($buttons, $this->argumentsService, $this->user);
                $caption = $this->caption('Подтвердите покупку');
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
            case 'ConfirmPayC':
                $this->argumentsService->setArgument('bkS' , 'MenuR');
                $this->argumentsService->setArgument('m' , 'F');
                (new StateMake($this->update, $this->user, $this->argumentsService, $this->user->state))->make();
                break;
            default:
                $this->callbackUpdate();
                break;
        }
    }
}
