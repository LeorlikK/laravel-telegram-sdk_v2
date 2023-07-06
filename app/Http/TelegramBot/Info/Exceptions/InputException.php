<?php

namespace App\Http\TelegramBot\Info\Exceptions;

use App\Http\TelegramBot\DefaultClass;

class InputException extends DefaultClass
{
    public function main(): array
    {
        $errorsArray = [
            '1' => 'Сохранение формата из документа находится в разработке',
            '2' => 'Неудовлетворимый формат изображения',
            '3' => 'Неправильный формат даты или времени',
            '4' => 'Число больше 100',
            '5' => 'Число меньше 0',
            '6' => 'Неправильный формат числа',
            '7' => 'Неправильный ID роли',
            '8' => 'Ошибка в указании id пользователя',
            '9' => 'Неправильный формат значения',
            '10' => 'Неправильный формат имени',
            '11' => 'Ошибка во время удаления роли',
            '12' => 'Нельзя назчанить 0',
            '13' => 'Неправильный формат даты или времени периода на покупку',
            '14' => 'Указано некорректное значение',
            '15' => 'Неправильный ID валюты',
            '16' => 'Неправильный ID папки',
            '17' => 'Папка или одна из внутренних папок не могут быть удалены т.к добавлены в корзину продуктов',
            '18' => 'Пользователь с таким tg-id не найден',
            '19' => 'Не указан id элеманта',
            '20' => 'Нет сообщения',
            '21' => 'Не указан sw',
        ];

        if (key_exists($this->argumentsService->er, $errorsArray)){
            $text = 'Error: ' . $errorsArray[$this->argumentsService->er];
        }else $text = 'Error: Unknown error';

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
