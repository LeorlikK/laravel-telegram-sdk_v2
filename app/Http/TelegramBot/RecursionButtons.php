<?php

namespace App\Http\TelegramBot;

use App\Http\TelegramBot\Services\ArgumentsService;
use Illuminate\Support\Collection;

class RecursionButtons extends Buttons
{
    public function getPaginate(
        Collection $buttons,
        $totalFolder,
        $perPage,
        ArgumentsService $argumentsService,
        $buttonMinus,
        $buttonPlus): Collection
    {
        return self::paginateNavigation($buttons, $totalFolder, $perPage, $argumentsService, $buttonMinus, $buttonPlus);
    }
}
