<?php

namespace App\Http\TelegramBot;

use App\Http\TelegramBot\Services\ArgumentsService;

interface RecursionInterface
{
    function getRecursionFoldersAndButtons():array;
}
