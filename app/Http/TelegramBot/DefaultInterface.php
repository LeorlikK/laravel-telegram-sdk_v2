<?php

namespace App\Http\TelegramBot;

use App\Http\TelegramBot\Services\ArgumentsService;
use Telegram\Bot\Objects\Update;

interface DefaultInterface
{
    public function main():array;
    public function sendCreate(): void;
    public function callbackUpdate():void;
    public function handleCallbackQuery():void;
}
