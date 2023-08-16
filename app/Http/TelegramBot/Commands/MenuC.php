<?php

namespace App\Http\TelegramBot\Commands;

use App\Http\TelegramBot\States\CreateTelegramAction;
use Telegram\Bot\Commands\Command;

class MenuC extends Command
{
    /**
     * @var string Command Name
     */
    protected string $name = 'start';

    /**
     * @var array Command Aliases
     */
    protected array $aliases = [];

    /**
     * @var string Command Description
     */
    protected string $description = 'get main menu';

    /**
     * {@inheritdoc}
     */
    public function handle():void
    {
        $commands = $this->telegram->getCommandBus()->getCommands();

        $text = 'MenuCommand';

        foreach ($commands as $name => $handler) {
            $text .= sprintf('/%s - %s'.PHP_EOL, $name, $handler->getDescription());
        }

//        TelegramSendJob::dispatch($this->update, 'cl:MenuR', 'send');
        (new CreateTelegramAction($this->update, 'cl:MenuR', 'send'))();
    }
}
