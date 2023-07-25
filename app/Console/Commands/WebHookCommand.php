<?php

namespace App\Console\Commands;

use App\Jobs\TelegramSendJob;
use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class WebHookCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tg:hook_v2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start WebHook';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        while (true) {
            $updates = Telegram::commandsHandler(false);

            /**
             * @var $update Update
             */
            foreach ($updates as $update) {
                if ($update->isType('callback_query')) {
                    dump($update->callbackQuery->get('data'));
                    TelegramSendJob::dispatch($update, $update->callbackQuery->get('data'), 'edit');
                } elseif ($update->isType('pre_checkout_query')) {
                    TelegramSendJob::dispatch($update, $update->preCheckoutQuery->get('invoice_payload'), 'answerPreCheckoutQuery');
                }else{
                    TelegramSendJob::dispatch($update, '', 'state');
                }
            }
        }
    }
}

/**

 * Оплата
 * Возможные ошибки
 * обработка фотографий в сжатом виде
 * alert на текстовые сообщения
 * возможность делать caption бесконечно длинными(сделать всем)
 * пагинация внутри пагинации внутри пагинации(3 уровня) и пагинация в рекурсии
 * подумать над временем выдачи продукта пользователю
 * ✔️ написать оформление для писем - отправка ответа на репорт, обычное сообщение
 * ✔️ удалить AC_N там, где это нужно
 * ✔️ возвращаемые функции каллбэка
 * ✔️ добавить галочку выбора во все нужные места
 * ✔️ добавить оповещения пользователя
 * ✔️ заменить глазик на логический оперотор
 * ✔️ предыдущаяя и следующая папка в рекурсии
 * ✔️ передать везде где нужно sw для next в пагинации
 * ✔️ проверить работу репортов
 * ✔️ просматривать через user список товаров пользователя
 * ✔️ написать нормальные пояснения к каждой настройке
 * ✔️ написать перевод часов в дни-месяцы
 * ✔️ вывести информацию о продукте
 * ✔️ изменить back на cancel и добавить удаление стейта
 * ✔️ Cache:
 * >>> ✔️ покупка
 * >>> ✔️ блокировка пользователя
 * >>> ✔️ разблокировка пользователя
 * >>> ✔️ удаление папки
 * >>> ✔️ удаление продукта
 * >>> ✔️ удаление продукта через админку у пользователя
 * >>> ✔️ добавление продукта через админку у пользователя
 * >>> ✔️ удаление роли
 * >>> ✔️ обновление видимости роли
 * >>> ✔️ добавление в карзины
 * >>> ✔️ удаление из карзины
 * >>> кэширование роли?
 * ✔️ добавить возможность писать пользователю из users
 * ✔️ добавить возможность поиска пользователя по id
 * ✔️ придумать как удалять стэйт по кнопке назад
 */
