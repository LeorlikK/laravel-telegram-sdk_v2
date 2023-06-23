<?php

namespace App\Http\TelegramBot\Actions\Modules;

use App\Http\TelegramBot\Buttons\Action\Modules\ChangePricePayButtons;
use App\Http\TelegramBot\DefaultClass;
use App\Http\TelegramBot\States\StateCreate;

class ChangePricePay extends DefaultClass
{
    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw){
            case 'Val':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'ChangePricePay' . $this->argumentsService->m);
                $buttons = ChangePricePayButtons::indicatePrice($buttons, $this->argumentsService);
                $caption = $this->caption('Введите цену');
                break;
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $buttons = ChangePricePayButtons::defaultButtons($buttons, $this->argumentsService);
                $caption = $this->caption("Выберите в какой валюте указать цену
                Нынешняя цена:
                ");
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
//            case 'Confirm':
//                (new StateMake($this->update, $this->user, $this->argumentsService, $this->user->state))->make();
//                break;
            default:
                $this->callbackUpdate();
                break;
        }
    }
}
