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
                    TelegramSendJob::dispatch($update, $update->callbackQuery->get('data'), 'edit');
                } else {
                    TelegramSendJob::dispatch($update, '', 'state');
                }
            }
        }
    }
}

/**
 * ✔️ удалить AC_N там, где это нужно
 * ✔️ возвращаемые функции каллбэка
 * ✔️ добавить галочку выбора во все нужные места
 * ✔️ добавить оповещения пользователя
 * ✔️ заменить глазик на логический оперотор
 *
 * если админ удалил что-то и у пользователя нет нужного id, то нужно перенаправить на стартовую страницу или куда-то еще
 * возможность делать caption бесконечно длинными(сделать всем)
 * написать оформление для писем - отправка ответа на репорт, обычное сообщение
 * стоит ли каждый раз отправлять фотографию, если она не меняется? Проверить возможность не отправлять
 * передать везде где нужно sw для next в пагинации
 * кнопка обновить страницу
 * ✔️ проверить работу репортов
 * ✔️ просматривать через user список товаров пользователя
 * подумать над временем выдачи продукта пользователю
 * написать нормальные пояснения к каждой настройке
 * написать перевод часов в дни-месяцы
 * обработка фотографий в сжатом виде
 * ✔️ вывести информацию о продукте
 * ✔️ изменить back на cancel и добавить удаление стейта
 * после покупки у пользователя сохраняется стейт в кэше - нужно сохранять без него
 * при удалении из продукта нужно что-то придумать с ключами продуктов в кэше
 * при удалении из корзины нужно что-то придумать с ключами продуктов в кэше
 * кэширование роли?
 * ✔️ добавить возможность писать пользователю из users
 * ✔️ добавить возможность поиска пользователя по id
 * ✔️ придумать как удалять стэйт по кнопке назад
 */
