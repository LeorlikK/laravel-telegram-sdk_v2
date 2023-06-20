<?php

namespace App\Http\TelegramBot\Components\SpecialClass;

use App\Http\TelegramBot\Buttons\SpecialClass\TestSpecialButtons;
use App\Http\TelegramBot\DefaultClass;

class TestS extends DefaultClass
{

    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw) {
            default:
                $this->argumentsService->setArgument('cl', class_basename($this));
                $this->argumentsService->setArgument('bk' , 'MenuR');
                $buttons = TestSpecialButtons::defaultButtons($buttons, $this->argumentsService);
                $caption = $this->caption('УРАА ТОВАРИЩИ');
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
