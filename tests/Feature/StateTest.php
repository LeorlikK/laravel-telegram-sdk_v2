<?php

use App\Http\TelegramBot\Buttons\Action\Modules\ChangeCaptionButtons;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Http\TelegramBot\States\Make\MakeChangeCaptionFolder;
use App\Http\TelegramBot\States\Make\MakeChangeEmojiFolder;
use App\Http\TelegramBot\States\Make\MakeChangeImageFolder;
use App\Http\TelegramBot\States\Make\MakeChangeNameFolder;
use App\Http\TelegramBot\States\Make\MakeChangeSecrecyFolder;
use App\Http\TelegramBot\States\Make\MakeChangeVisibilityFolder;
use App\Http\TelegramBot\States\Make\MakeCreateFolder;
use App\Http\TelegramBot\States\Make\MakeDeleteFolder;
use App\Http\TelegramBot\States\StateCreate;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use Database\Seeders\DatabaseSeeder;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;
use Tests\TestCase;

class StateTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);

        $this->botFirstname = 'lelik';
        $this->botUserName = 'Leorlik_bot';
        $this->user = \App\Models\User::first();
        $this->argumentsService = new ArgumentsService('bkS:MenuR_fp:1_v:d1_m:F');

        $this->update = new Update([
            "update_id" => 644024188,
            'message' => new Message([
                'message_id' => 6424,
                "from" => new \Telegram\Bot\Objects\User([
                    "id" => $this->user->tg_id,
                    'is_bot' => false,
                    "first_name" => $this->user->first_name,
                    "last_name" => $this->user->last_name,
                    "username" => $this->user->username,
                    "language_code" => $this->user->language
                ]),
                'chat' => new Chat([
                    "id" => $this->user->tg_id,
                    "first_name" => $this->user->first_name,
                    "last_name" => $this->user->last_name,
                    "username" => $this->user->username,
                    "type" => "private",
                ]),
                "date" => 1692067987,
                "photo" => [
                    [
                        "file_id" => "AgACAgIAAxkBAAIa82TcX84kl6wc4adGrwlFsdHmlLnTAAL6zjEbu27gSsLcFabeRtGqAQADAgADeAADMAQ",
                        "file_unique_id" => "AQAD-s4xG7tu4Ep9",
                        "file_size" => 91000,
                        "width" => 633,
                        "height" => 707
                    ]
                ],
                "text" => "start",
                "entities" => [
                    [
                        "offset" => 0,
                        "length" => 6,
                        "type" => "bot_command"
                    ]
                ]
            ])
        ]);
    }

    public function test_state_create()
    {
        StateCreate::createState($this->update, $this->user, $this->argumentsService,
            'Create' . $this->argumentsService->m);
        $this->assertDatabaseCount('states', 1);
        $state = \App\Models\State::first();
        $this->assertEquals('CreateF', $state->action);
    }

    public function test_state_make_create_folder()
    {
        StateCreate::createState($this->update, $this->user, $this->argumentsService,
            'Create' . $this->argumentsService->m);

        $this->user->refresh();

        $stateMake = new StateMake($this->update, $this->user,
            $this->argumentsService, $this->user->state);
        $stateMake->text = $stateMake->update->getMessage()->get('text');
        $stateMake->messageId = $stateMake->update->getMessage()->get('message_id');
        $stateMake->chatId = $stateMake->update->getChat()->get('id');
        $stateMake->parentId = $stateMake->state->parentId ?? 0;
        $stateMake->sortedId = 1;

        $makeFolder = new MakeCreateFolder($stateMake);
        $makeFolder->make();

        $this->assertDatabaseCount('folders', 2);
        $folder = Folder::all()->last();
        $this->assertEquals('📚 ' . $this->update->getMessage()->get('text'), $folder->name);
    }

    public function test_state_make_change_name()
    {
        $oldNameFolder = Folder::first();

        StateCreate::createState($this->update, $this->user, $this->argumentsService,
            'ChangeName' . $this->argumentsService->m);

        $this->user->refresh();

        $stateMake = new StateMake($this->update, $this->user,
            $this->argumentsService, $this->user->state);
        $stateMake->text = $stateMake->update->getMessage()->get('text');
        $stateMake->messageId = $stateMake->update->getMessage()->get('message_id');
        $stateMake->chatId = $stateMake->update->getChat()->get('id');
        $stateMake->parentId = $stateMake->state->parentId ?? 0;
        $stateMake->sortedId = 1;

        $changeNameFolder = new MakeChangeNameFolder($stateMake);
        $changeNameFolder->make();

        $this->assertDatabaseCount('folders', 1);
        $this->assertNotEquals('📚 ' . $this->update->getMessage()->get('text'), $oldNameFolder->name);
    }

    public function test_state_make_change_emoji()
    {
        $folderOldEmoji = Folder::first();

        $this->update = new Update([
            "update_id" => 644024188,
            'message' => new Message([
                'message_id' => 6424,
                "from" => new \Telegram\Bot\Objects\User([
                    "id" => $this->user->tg_id,
                    'is_bot' => false,
                    "first_name" => $this->user->first_name,
                    "last_name" => $this->user->last_name,
                    "username" => $this->user->username,
                    "language_code" => $this->user->language
                ]),
                'chat' => new Chat([
                    "id" => $this->user->tg_id,
                    "first_name" => $this->user->first_name,
                    "last_name" => $this->user->last_name,
                    "username" => $this->user->username,
                    "type" => "private",
                ]),
                "date" => 1692067987,
                "text" => "📣",
                "entities" => [
                    [
                        "offset" => 0,
                        "length" => 6,
                        "type" => "bot_command"
                    ]
                ]
            ])
        ]);

        StateCreate::createState($this->update, $this->user, $this->argumentsService,
            'ChangeEmoji' . $this->argumentsService->m);

        $this->user->refresh();

        $stateMake = new StateMake($this->update, $this->user,
            $this->argumentsService, $this->user->state);
        $stateMake->text = $stateMake->update->getMessage()->get('text');
        $stateMake->messageId = $stateMake->update->getMessage()->get('message_id');
        $stateMake->chatId = $stateMake->update->getChat()->get('id');
        $stateMake->parentId = $stateMake->state->parentId ?? 0;
        $stateMake->sortedId = 1;

        $changeNameFolder = new MakeChangeEmojiFolder($stateMake);
        $changeNameFolder->make();

        $folderNewEmoji = Folder::first();

        $this->assertDatabaseCount('folders', 1);
        $this->assertNotEquals($folderNewEmoji->name, $folderOldEmoji->name);
    }

    public function test_state_make_change_caption()
    {
        $folderOldEmoji = Folder::first();

        StateCreate::createState($this->update, $this->user, $this->argumentsService,
            'ChangeCaption' . $this->argumentsService->m);

        $this->user->refresh();

        $stateMake = new StateMake($this->update, $this->user,
            $this->argumentsService, $this->user->state);
        $stateMake->text = $stateMake->update->getMessage()->get('text');
        $stateMake->messageId = $stateMake->update->getMessage()->get('message_id');
        $stateMake->chatId = $stateMake->update->getChat()->get('id');
        $stateMake->parentId = $stateMake->state->parentId ?? 0;
        $stateMake->sortedId = 1;

        $changeNameFolder = new MakeChangeCaptionFolder($stateMake);
        $changeNameFolder->make();

        $folderNewEmoji = Folder::first();

        $this->assertDatabaseCount('folders', 1);
        $this->assertNotEquals($folderNewEmoji->caption, $folderOldEmoji->caption);
    }

    public function test_state_make_change_image()
    {
        $folderOldImage = Folder::first();

        StateCreate::createState($this->update, $this->user, $this->argumentsService,
            'ChangeImage' . $this->argumentsService->m);

        $this->user->refresh();

        $stateMake = new StateMake($this->update, $this->user,
            $this->argumentsService, $this->user->state);
        $stateMake->text = $stateMake->update->getMessage()->get('text');
        $stateMake->messageId = $stateMake->update->getMessage()->get('message_id');
        $stateMake->chatId = $stateMake->update->getChat()->get('id');
        $stateMake->parentId = $stateMake->state->parentId ?? 0;
        $stateMake->sortedId = 1;

        $changeNameFolder = new MakeChangeImageFolder($stateMake);
        $changeNameFolder->make();

        $folderNewImage = Folder::first();

        $this->assertDatabaseCount('folders', 1);
        $this->assertNotEquals($folderNewImage->media, $folderOldImage->media);
    }

    public function test_state_make_change_secrecy()
    {
        $folderOldSecrecy = Folder::first();

        StateCreate::createState($this->update, $this->user, $this->argumentsService,
            'ChangeSecrecy' . $this->argumentsService->m);

        $this->user->refresh();

        $stateMake = new StateMake($this->update, $this->user,
            $this->argumentsService, $this->user->state);
        $stateMake->text = $stateMake->update->getMessage()->get('text');
        $stateMake->messageId = $stateMake->update->getMessage()->get('message_id');
        $stateMake->chatId = $stateMake->update->getChat()->get('id');
        $stateMake->parentId = $stateMake->state->parentId ?? 0;
        $stateMake->sortedId = 1;

        $changeNameFolder = new MakeChangeSecrecyFolder($stateMake);
        $changeNameFolder->make();

        $folderNewSecrecy = Folder::first();

        $this->assertDatabaseCount('folders', 1);
        $this->assertNotEquals($folderNewSecrecy->display, $folderOldSecrecy->display);
    }

    public function test_state_make_change_visibility()
    {
        $this->argumentsService->v = 50;

        $folderOldVisibility = Folder::first();

        StateCreate::createState($this->update, $this->user, $this->argumentsService,
            'ChangeVisibility' . $this->argumentsService->m);

        $this->user->refresh();

        $stateMake = new StateMake($this->update, $this->user,
            $this->argumentsService, $this->user->state);
        $stateMake->text = $stateMake->update->getMessage()->get('text');
        $stateMake->messageId = $stateMake->update->getMessage()->get('message_id');
        $stateMake->chatId = $stateMake->update->getChat()->get('id');
        $stateMake->parentId = $stateMake->state->parentId ?? 0;
        $stateMake->sortedId = 1;

        $changeNameFolder = new MakeChangeVisibilityFolder($stateMake);
        $changeNameFolder->make();

        $folderNewVisibility = Folder::first();

        $this->assertDatabaseCount('folders', 1);
        $this->assertNotEquals($folderNewVisibility->visibility, $folderOldVisibility->visibility);
    }

    public function test_state_make_change_delete()
    {
        $this->argumentsService->v = 'del';

        StateCreate::createState($this->update, $this->user, $this->argumentsService,
            'Delete' . $this->argumentsService->m);

        $this->user->refresh();

        $stateMake = new StateMake($this->update, $this->user,
            $this->argumentsService, $this->user->state);
        $stateMake->text = $stateMake->update->getMessage()->get('text');
        $stateMake->messageId = $stateMake->update->getMessage()->get('message_id');
        $stateMake->chatId = $stateMake->update->getChat()->get('id');
        $stateMake->parentId = $stateMake->state->parentId ?? 0;
        $stateMake->sortedId = 1;

        $changeNameFolder = new MakeDeleteFolder($stateMake);
        $changeNameFolder->make();

        $this->assertDatabaseCount('folders', 0);
    }
}
