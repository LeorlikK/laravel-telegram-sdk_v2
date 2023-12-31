<?php

namespace App\Http\TelegramBot;

use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
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

    public function sendMessage(): string
    {
        /**
         * @var $photo InputFile
         */
        [$text] = $this->main();

        return Telegram::sendMessage([
            'chat_id' => $this->update->getChat()->get('id'),
            'text' => $text,
        ]);
    }

    public function sendCreate(): string
    {
        /**
         * @var $photo InputFile
         */
        [$photo, $caption, $reply_markup] = $this->main();

        return Telegram::sendPhoto([
            'chat_id' => $this->update->getChat()->get('id'),
            'photo' => str_starts_with($photo->getFile(), 'http') ? $photo : $photo->getFile(),
            'caption' => $caption,
            'reply_markup' => json_encode($reply_markup, JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function callbackUpdate(): string
    {
        /**
         * @var $photo InputFile
         */
        [$photo, $caption, $reply_markup] = $this->main();

        return Telegram::editMessageMedia([
            'chat_id' => $this->argumentsService->chat ?? $this->update->getChat()->get('id'),
            'message_id' => $this->argumentsService->mi ?? $this->update->getMessage()->get('message_id'),
            'media' => json_encode(["type" => "photo", "media" => $photo->getFile(), "caption" => $caption, "parse_mode" => "Markdown"], JSON_UNESCAPED_UNICODE),
            'reply_markup' => json_encode($reply_markup, JSON_UNESCAPED_UNICODE)
        ]);
    }

    public function callbackAnswer(bool $show_alert): string
    {
        /**
         * @var $photo InputFile
         */
        [$text] = $this->main();

        return Telegram::answerCallbackQuery([
            'callback_query_id' => $this->update->callbackQuery->get('id'),
            'text' => $text,
            'show_alert' => $show_alert,
            'cache_time' => 60
        ]);
    }

    public function sendInvoice(): string
    {
        /**
         * @var $photo InputFile
         */
        [$chat_id, $title, $description, $start_parameter, $payload,
            $provider_token, $currency, $prices, $photo, $reply_markup] = $this->main();

        return Telegram::sendInvoice([
            "chat_id" => $chat_id,
            "title" => $title,
            "description" => $description,
            'start_parameter' => $start_parameter,
            "payload" => $payload,
            "provider_token" => $provider_token,
            "currency" => $currency,
            "prices" => $prices,
            'photo_url' => $photo->getFile(),
            'reply_markup' => json_encode($reply_markup, JSON_UNESCAPED_UNICODE)
        ]);
    }

    public function answerPreCheckoutQuery(): string
    {
        /**
         * @var $photo InputFile
         */
        [$pre_checkout_query_id, $ok, $error_message]= $this->main();

        return Telegram::answerPreCheckoutQuery([
            "pre_checkout_query_id" => $pre_checkout_query_id,
            "ok" => $ok,
            "error_message" => $error_message,
        ]);
    }
}
