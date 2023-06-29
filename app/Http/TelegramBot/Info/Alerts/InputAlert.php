<?php

namespace App\Http\TelegramBot\Info\Alerts;

use App\Http\TelegramBot\DefaultClass;

class InputAlert extends DefaultClass
{
    public function main(): array
    {
        $errorsArray = [
            '1' => 'У вас не куплен доступ к этой папке',
            '2' => 'Папка или одна из внутренних папок не могут быть удалены т.к добавлены в корзину продуктов',
            '3' => 'Ваш аккаунт был заблокирован!',
            '4' => 'Вы уже приобрели этот товар',
            '5' => 'Невозможно поменять папку с самой собой',
            '6' => 'Этот товар уже добавлен в корзину покупки',
            '7' => 'Этот товар уже добавлен в корзину покупки v2',
            '8' => 'У вас уже куплен этот товар',
            '9' => '9',
            '10' => 'Вы были заблокированы',
            '11' => 'Эту роль нельзя изменить',
            '12' => 'Имя этой роли нельзя изменить',
        ];

        if (key_exists($this->argumentsService->er, $errorsArray)){
            $text = 'Alert: ' . $errorsArray[$this->argumentsService->er];
        }else $text = 'Unknown alert';

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
