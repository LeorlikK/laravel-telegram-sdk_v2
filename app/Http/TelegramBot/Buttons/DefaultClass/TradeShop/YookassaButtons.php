<?php

namespace App\Http\TelegramBot\Buttons\DefaultClass\TradeShop;

use App\Http\TelegramBot\Services\ArgumentsService;
use Illuminate\Support\Collection;

class YookassaButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        return $buttons;
    }

}
