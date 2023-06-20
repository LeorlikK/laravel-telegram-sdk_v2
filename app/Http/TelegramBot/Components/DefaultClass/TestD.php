<?php

namespace App\Http\TelegramBot\Components\DefaultClass;

use App\Http\TelegramBot\Buttons\DefaultClass\TestDefaultButtons;
use App\Http\TelegramBot\DefaultClass;

class TestD extends DefaultClass
{

    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw){
            case 'One':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
//                $argumentsService->setArgument('backSwitch' , class_basename($this));
                $buttons = TestDefaultButtons::oneButtons($buttons, $this->argumentsService);
                $caption = $this->caption('OneOne');
                $photo = $this->photo(null);
                $reply_markup = $this->replyMarkup($buttons);
                break;
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $buttons = TestDefaultButtons::defaultButtons($buttons, $this->argumentsService);
                $caption = $this->caption('Menu');
                $photo = $this->photo(null);
                $reply_markup = $this->replyMarkup($buttons);
                break;
        }

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
