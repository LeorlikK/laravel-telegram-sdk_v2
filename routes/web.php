<?php

use App\Http\TelegramBot\States\CreateTelegramAction;
use App\Jobs\TelegramSendJob;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

Route::post('/', function () {
    $update = Telegram::commandsHandler(true);

    /**
     * @var $update Update
     */
    if ($update->isType('callback_query')) {
        (new CreateTelegramAction($update, $update->callbackQuery->get('data'), 'edit'))();
    }elseif ($update->isType('pre_checkout_query')) {
        (new CreateTelegramAction($update, $update->preCheckoutQuery->get('invoice_payload'), 'answerPreCheckoutQuery'))();
    }else {
        (new CreateTelegramAction($update, '', 'state'))();
    }
});
