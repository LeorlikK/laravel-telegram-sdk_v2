<?php

namespace App\Http\TelegramBot\Buttons\SpecialClass;

use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;

class PayProductSpecialButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService, User $user):Collection
    {
        $product = Product::where('folder_id', $argumentsService->fp)->first();
        $haveProduct = $user->pays->contains('product_id', $product->id);

        if ($haveProduct){
            $buttons->add([
                ['text' => 'У вас уже куплен этот товар ❗️', 'callback_data' =>
                    "cl:IA".'_'.
                    "sw:ConfirmPayC".'_'.
                    "bk:$argumentsService->bk".'_'.
                    "bkS:$argumentsService->bkS".'_'.
                    "ac:N".'_'.
                    "fp:$argumentsService->fp".'_'.
                    "er:8"],
            ]);
        }else{
            $buttons->add([
                ['text' => '💰 Купить товар', 'callback_data' =>
                    "cl:$argumentsService->cl".'_'.
                    "sw:ConfirmPayC".'_'.
                    "bk:$argumentsService->bk".'_'.
                    "bkS:$argumentsService->bkS".'_'.
                    "ac:N".'_'.
                    "fp:$argumentsService->fp"],
            ]);
        }

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"],
        ]);

        return $buttons;
    }
}
