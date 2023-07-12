<?php

namespace App\Http\TelegramBot;

use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\Tab;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

abstract class DefaultClass implements DefaultInterface
{
    protected bool $administrator;
    public Update $update;
    public ArgumentsService $argumentsService;

    public function __construct(readonly User|null $user, Update $update, ArgumentsService $argumentsService)
    {
        $this->administrator = $this->user?->role_id === 1;
        $this->update = $update;
        $this->argumentsService = $argumentsService;
    }

    public function replyMarkup(Collection $buttons):array
    {
        $buttons = $buttons->values()->toArray();
        $buttons = Keyboard::inlineButton($buttons);
        return $reply_markup = [
            'inline_keyboard' => $buttons,
            'one_time_keyboard' => true
        ];
    }

    public function caption(string $caption):string
    {
        return $caption;
    }

    public function photo(?string $idOrUsl):InputFile
    {
        $photoPath = $idOrUsl ?? config('telegram.base_url_image');
        return InputFile::create($photoPath, 'defaultImage');
    }

    public function sendMessage(): void
    {
        /**
         * @var $photo InputFile
         */
        [$text] = $this->main();

        Telegram::sendMessage([
            'chat_id' => $this->update->getChat()->get('id'),
            'text' => $text,
        ]);
    }

    public function sendCreate(): void
    {
        /**
         * @var $photo InputFile
         */
        [$photo, $caption, $reply_markup] = $this->main();

        Telegram::sendPhoto([
            'chat_id' => $this->update->getChat()->get('id'),
            'photo' => str_starts_with($photo->getFile(), 'http') ? $photo : $photo->getFile(),
            'caption' => $caption,
            'reply_markup' => json_encode($reply_markup, JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function callbackUpdate(): void
    {
        /**
         * @var $photo InputFile
         */
        [$photo, $caption, $reply_markup] = $this->main();

        Telegram::editMessageMedia([
            'chat_id' => $this->update->getChat()->get('id'),
            'message_id' => $this->update->getMessage()->get('message_id'),
            'media' => json_encode(["type" => "photo", "media" => $photo->getFile(), "caption" => $caption, "parse_mode" => "Markdown"], JSON_UNESCAPED_UNICODE),
            'reply_markup' => json_encode($reply_markup, JSON_UNESCAPED_UNICODE)
        ]);
    }

    public function callbackAnswer(bool $show_alert): void
    {
        /**
         * @var $photo InputFile
         */
        [$text] = $this->main();

        Telegram::answerCallbackQuery([
            'callback_query_id' => $this->update->callbackQuery->get('id'),
            'text' => $text,
            'show_alert' => $show_alert,
            'cache_time' => 60
        ]);
    }

    public function sendInvoice(): void
    {
        /**
         * @var $photo InputFile
         */
        [$recipient, $amount, $currency, $description, $due_date] = $this->main();

        Telegram::sendInvoice([
            "recipient" => $recipient,
            "amount" => $amount,
            "currency" => $currency,
            "description" => $description,
            "due_date" => $due_date,
        ]);
    }
}
