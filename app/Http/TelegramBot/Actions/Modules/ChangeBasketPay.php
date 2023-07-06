<?php

namespace App\Http\TelegramBot\Actions\Modules;

use App\Http\TelegramBot\Buttons\Action\Modules\ChangeBasketPayButtons;
use App\Http\TelegramBot\DefaultClass;
use App\Http\TelegramBot\States\StateCreate;
use App\Http\TelegramBot\States\StateMake;

class ChangeBasketPay extends DefaultClass
{

    public function main(): array
    {
        $buttons = collect();

        switch (true){
            case $this->argumentsService->sw === 'Del':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('m' , 'F');
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'DeletePay' . $this->argumentsService->m);
                $buttons = ChangeBasketPayButtons::deleteButtons($buttons, $this->argumentsService);
                $caption = $this->caption('Удалите папку из корзины покупок');
                break;
            case $this->argumentsService->sw === 'Add':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('m' , 'F');
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'AddPay' . $this->argumentsService->m);
                $buttons = ChangeBasketPayButtons::addButtons($buttons, $this->argumentsService);
                $caption = $this->caption('Добавьте папку в корзину покупок');
                break;
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $buttons = ChangeBasketPayButtons::defaultButtons($buttons, $this->argumentsService);
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
            case 'ConfirmAdd':
                $this->argumentsService->setArgument('sw', null);
                (new StateMake($this->update, $this->user, $this->argumentsService, $this->user->state))->make();
                break;
            case 'ConfirmDel':
                $this->argumentsService->setArgument('sw', null);
                (new StateMake($this->update, $this->user, $this->argumentsService, $this->user->state))->make();
                break;
            default:
                $this->callbackUpdate();
                break;
        }
    }
}
