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
