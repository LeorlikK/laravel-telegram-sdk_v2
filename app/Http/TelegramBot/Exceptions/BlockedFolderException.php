<?php

namespace App\Http\TelegramBot\Exceptions;

use App\Http\TelegramBot\DefaultClass;

class BlockedFolderException extends DefaultClass
{

    public function main(): array
    {
        $text = 'У вас нет доступа к этой папке';

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
