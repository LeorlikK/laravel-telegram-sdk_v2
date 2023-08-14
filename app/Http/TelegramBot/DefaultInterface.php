<?php

namespace App\Http\TelegramBot;

interface DefaultInterface
{
    public function main():array;
    public function sendCreate(): void;
    public function callbackUpdate():void;
    public function handleCallbackQuery():void;
}
