<?php

namespace App\Http\TelegramBot;

interface DefaultInterface
{
    public function main():array;
    public function sendMessage(): string;
    public function sendCreate(): string;
    public function callbackUpdate(): string;
    public function handleCallbackQuery():void;
}
