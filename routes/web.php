<?php

use App\Http\TelegramBot\States\CreateTelegramAction;
use App\Jobs\TelegramSendJob;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

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
