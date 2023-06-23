<?php

namespace App\Http\TelegramBot\Exceptions;

use App\Http\TelegramBot\DefaultClass;

class BasketFolderException extends DefaultClass
{

    public function main(): array
    {
        $text = 'Этот товар уже добавлен в корзину покупки';

        return [$text];
    }

    public function handleCallbackQuery(): void
    {
        switch ($this->argumentsService->sw){
            default:
                $this->sendMessage();
                break;
        }
    }
}
