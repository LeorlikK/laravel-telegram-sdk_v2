<?php

namespace App\Http\TelegramBot\Exceptions;

use App\Http\TelegramBot\DefaultClass;

class BlockedFolderPayException extends DefaultClass
{

    public function main(): array
    {
        $errorsArray = [
            '1' => 'У вас не куплен доступ к этой папке',
            '2' => 'Папка или одна из внутренних папок не могут быть удалены т.к добавлены в корзину продуктов',
        ];

        if (key_exists($this->argumentsService->er, $errorsArray)){
            $text = $errorsArray[$this->argumentsService->er];
        }else $text = 'Unknown error';

        $this->argumentsService->er = null;
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
