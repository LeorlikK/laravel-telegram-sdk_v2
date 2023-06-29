<?php

namespace App\Http\TelegramBot\Commands;

use App\Jobs\TelegramSendJob;
use App\Models\User;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Objects\Update;

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

        TelegramSendJob::dispatch($this->update, 'cl:MenuR', 'send');
    }
}
//$gif = '❓❗️🔑🕹✏️📚✅☑️✔️📌🖼◀️▶️⬇️↕️🔄↩️↔️🔃↖️⤴️↘️↪️⤵️↙️🛒📅📣👁❌💳💰💵💷💴💶👥🆘➕🗑✉️⚪️🔵💎🔹♻️⏳🔒📊🛍📝📋📨🚀💬';
