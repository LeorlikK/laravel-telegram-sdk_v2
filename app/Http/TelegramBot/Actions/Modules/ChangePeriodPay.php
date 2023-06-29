<?php

namespace App\Http\TelegramBot\Actions\Modules;

use App\Http\TelegramBot\Buttons\Action\Modules\ChangePeriodPayButtons;
use App\Http\TelegramBot\Buttons\Action\Modules\ChangePricePayButtons;
use App\Http\TelegramBot\DefaultClass;
use App\Http\TelegramBot\States\StateCreate;
use App\Http\TelegramBot\States\StateMake;

class ChangePeriodPay extends DefaultClass
{
    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw){
//            case 'Val':
//                $this->argumentsService->setArgument('cl' , class_basename($this));
//                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'ChangePricePay' . $this->argumentsService->m);
//                $buttons = ChangePeriodPayButtons::indicatePrice($buttons, $this->argumentsService);
//                $caption = $this->caption('Введите цену');
//                break;
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'ChangePeriodPay' . $this->argumentsService->m);
                $buttons = ChangePeriodPayButtons::defaultButtons($buttons, $this->argumentsService);
                $caption = $this->caption("Выберите срок на который пользователь приобретет продукт при покупке или укажите его в формате ('1h', '1d', '1w', '1m')");
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
