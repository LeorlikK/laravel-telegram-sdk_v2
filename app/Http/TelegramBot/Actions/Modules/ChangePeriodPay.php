<?php

namespace App\Http\TelegramBot\Actions\Modules;

use App\Http\TelegramBot\Buttons\Action\Modules\ChangePeriodPayButtons;
use App\Http\TelegramBot\DefaultClass;
use App\Http\TelegramBot\States\StateCreate;
use App\Http\TelegramBot\States\StateMake;

class ChangePeriodPay extends DefaultClass
{
    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw){
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'ChangePeriodPay' . $this->argumentsService->m);
                [$buttons, $caption] = ChangePeriodPayButtons::defaultButtons($buttons, $this->argumentsService);
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
