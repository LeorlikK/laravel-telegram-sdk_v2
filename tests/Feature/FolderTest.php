<?php

use App\Http\TelegramBot\Auth\Authentication;
use App\Http\TelegramBot\Buttons\RecursionClass\MenuRecursionButtons;
use App\Http\TelegramBot\Components\DefaultClass\TradeShop\Yookassa;
use App\Http\TelegramBot\Components\RecursionClass\MenuR;
use App\Http\TelegramBot\Info\Alerts\InputAlert;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Http\TelegramBot\States\CreateTelegramAction;
use App\Jobs\TelegramSendJob;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;
use Tests\TestCase;

class FolderTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);

        $this->botFirstname = 'lelik';
        $this->botUserName = 'Leorlik_bot';
        $this->user = User::first();

        $this->sendCreate = new Update([
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
                "text" => "/start",
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

    public function test_send_message()
    {
        $authentication = new Authentication();
        $user = $authentication->handle($this->sendCreate);
        $argumentsService = new ArgumentsService('er:1');
        $inputAlert = new InputAlert($user, $this->sendCreate, $argumentsService);
        $response = $inputAlert->sendMessage();

        $this->assertJson($response);

        $objectResponseSendMessage = json_decode($response);
        $this->assertObjectHasProperty('text', $objectResponseSendMessage);
        $this->assertEquals('Alert: У вас не хватает уровня доступа к этому товару', $objectResponseSendMessage->text);
    }

    public function test_send_create()
    {
        $authentication = new Authentication();
        $user = $authentication->handle($this->sendCreate);
        $argumentsService = new ArgumentsService('cl:MenuR');
        $menuR = new MenuR($user, $this->sendCreate, $argumentsService);
        $response = $menuR->sendCreate();

        $this->assertJson($response);

        $objectResponseSendCreate = json_decode($response);
        $this->assertObjectHasProperty('photo', $objectResponseSendCreate);
        $this->assertObjectHasProperty('reply_markup', $objectResponseSendCreate);
        $this->assertEquals($objectResponseSendCreate->chat->id, $this->user->tg_id);
    }

    public function test_callback_update()
    {
        $authentication = new Authentication();
        $user = $authentication->handle($this->sendCreate);
        $argumentsService = new ArgumentsService('cl:MenuR');
        $menuR = new MenuR($user, $this->sendCreate, $argumentsService);
        $response = $menuR->sendCreate();

        $objectResponseSendCreate = json_decode($response);
        $messageId = $objectResponseSendCreate->message_id;

        $callbackUpdate = new Update([
            "update_id" => 644024189,
            "callback_query" => [
                "id" => "4549266363297854501",
                "from" => [
                    "id" => $this->user->tg_id,
                    "is_bot" => false,
                    "first_name" => $this->user->first_name,
                    "last_name" => $this->user->last_name,
                    "username" => $this->user->username,
                    "language_code" => $this->user->language
                ],
                "message" => [
                    "message_id" => $messageId,
                    "chat" => [
                        "id" => $this->user->tg_id,
                        "first_name" => $this->user->first_name,
                        "last_name" => $this->user->last_name,
                        "username" => $this->user->username,
                        "type" => "private"
                    ],
                    "date" => 1692069904,
                ],
                "data" => "cl:MenuR_ac:N_fp:1"
            ]
        ]);

        $authentication = new Authentication();
        $user = $authentication->handle($callbackUpdate);
        $argumentsService = new ArgumentsService($callbackUpdate->callbackQuery->get('data'));
        $menuR = new MenuR($user, $callbackUpdate, $argumentsService);
        $response = $menuR->callbackUpdate();

        $this->assertJson($response);

        $objectResponseCallbackUpdate = json_decode($response);
        $this->assertObjectHasProperty('photo', $objectResponseCallbackUpdate);
        $this->assertObjectHasProperty('reply_markup', $objectResponseCallbackUpdate);
        $this->assertObjectHasProperty('caption', $objectResponseCallbackUpdate);
        $this->assertEquals($objectResponseCallbackUpdate->chat->id, $this->user->tg_id);
    }
}
