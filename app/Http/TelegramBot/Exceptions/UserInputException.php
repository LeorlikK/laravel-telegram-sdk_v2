<?php

namespace App\Http\TelegramBot\Exceptions;

use App\Http\TelegramBot\DefaultClass;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class UserInputException
{
    public static function sendError(Update $update, string $text): void
    {
        Telegram::sendMessage([
            'chat_id' => $update->getChat()->get('id'),
            'text' => $text
        ]);
    }
}
