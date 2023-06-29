<?php

namespace App\Http\TelegramBot\Components\DefaultClass\PersonalArea;

use App\Http\TelegramBot\Buttons\DefaultClass\PersonalArea\AreaPurchasedButtons;
use App\Http\TelegramBot\DefaultClass;

class AreaPurchased extends DefaultClass
{
    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw){
            case 'Purchase':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                [$buttons, $caption] = AreaPurchasedButtons::purchaseButtons($buttons, $this->argumentsService);
                break;
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , 'AreaMenu');
                $buttons = AreaPurchasedButtons::defaultButtons($buttons, $this->argumentsService, $this->user);
                $caption = $this->caption("Список покупок");
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
