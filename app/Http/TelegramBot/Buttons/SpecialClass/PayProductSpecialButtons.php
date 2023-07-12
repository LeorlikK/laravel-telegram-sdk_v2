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
                ['text' => 'You have already purchased this product ❗️', 'callback_data' =>
                    "cl:IA".'_'.
                    "sw:ConfirmPayC".'_'.
                    "bk:$argumentsService->bk".'_'.
                    "bkS:$argumentsService->bkS".'_'.
                    "fp:$argumentsService->fp".'_'.
                    "er:8"],
            ]);
        }else{
            $buttons->add([
                ['text' => '💰 Buy item', 'callback_data' =>
                    "cl:Yoo".'_'.
                    "bk:$argumentsService->bk".'_'.
                    "bkS:$argumentsService->bkS".'_'.
                    "fp:$argumentsService->fp"],
            ]);
        }

        $buttons->add([
            ['text' => '◀️ Cancel', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "fp:$argumentsService->fp".'_'.
                "s:1"
            ],
        ]);

        return $buttons;
    }
}
