<?php

namespace App\Http\TelegramBot\Info\Alerts;

use App\Http\TelegramBot\DefaultClass;

class InputAlert extends DefaultClass
{
    public function main(): array
    {
        $errorsArray = [
            '1' => 'У вас не хватает уровня доступа к этому товару',
            '2' => 'Папка или одна из внутренних папок не могут быть удалены т.к добавлены в корзину продуктов',
            '3' => 'Ваш аккаунт был заблокирован!',
            '4' => 'Вы уже приобрели этот товар',
            '5' => 'Невозможно поменять папку с самой собой',
            '6' => 'Этот товар уже добавлен в корзину покупки',
            '7' => 'Этот товар уже куплен пользователем',
            '8' => 'У вас уже куплен этот товар',
            '9' => 'У вас не куплен допступ к этому товару',
            '10' => 'Вы были заблокированы',
            '11' => 'Эту роль нельзя изменить',
            '12' => 'Имя этой роли нельзя изменить',
            '13' => 'Товар добавлен в карзину',
            '14' => 'Товар удалён из карзины',
            '15' => 'Caption изменен',
            '16' => 'Emoji изменен',
            '17' => 'Image изменено',
            '18' => 'Name изменено',
            '19' => 'Время покупки изменено',
            '20' => 'Цена покупки изменена',
            '21' => 'Отображение папки изменено',
            '22' => 'Порядок сортировки папки изменен',
            '23' => 'Уровень доступа папки изменен',
            '24' => 'Папка создана',
            '25' => 'Создан класс монетизации',
            '26' => 'Класс монетизации удален',
            '27' => 'Продукт удален',
            '28' => 'MakePayProductClass',
            '29' => 'Покупка успешно добавлена пользователю',
            '30' => 'Пользователь заблокирован',
            '31' => 'Имя роли изменено',
            '32' => 'Роль пользователя изменена',
            '33' => 'Значение роли изменено',
            '34' => 'Создана новая роль',
            '35' => 'Покупка пользователя удалена',
            '36' => 'Роль успешно удалена',
            '37' => 'Пользователь разблокирован',
            '38' => 'Письмо пользователю в личный кабинет успешно отправлен',
            '39' => 'Письмо пользователю успешно отправле',
            '40' => 'Ответ пользователю в личный кабинет успешно отправлен',
            '41' => 'Ваше обращение отправлено',
            '42' => 'Обращение пользователя удалено',
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
