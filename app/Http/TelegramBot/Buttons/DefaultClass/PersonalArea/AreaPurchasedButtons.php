<?php

namespace App\Http\TelegramBot\Buttons\DefaultClass\PersonalArea;

use App\Http\TelegramBot\Buttons;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Http\TelegramBot\Services\RemainingTimeService;
use App\Models\Pay;
use App\Models\User;
use Illuminate\Support\Collection;

class AreaPurchasedButtons extends Buttons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService, User $user): Collection
    {
        $argumentsService->p = $argumentsService->p ?? 1;
        $buttonPlus = ((int)$argumentsService->p) + 1;
        $buttonMinus = ((int)$argumentsService->p) - 1;
        $perPage = 10;
        $pays = Pay::with('product.folder')->where('user_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage, ['*'], null, $argumentsService->p);
        $totalFolder = $pays->total();

        $remainingTimeService = new RemainingTimeService();
        foreach ($pays->items() as $pay) {
            $diff = $pay->subscription ? $pay->subscription->diff(now()) : null;
            if ($diff){
                $remaining = $remainingTimeService->getRemainingTime($diff);
            }
            /**
             * @var $pay Pay
             */
            $buttons->add([
                ['text' => $pay->product->folder->name . (isset($remaining) ? "(осталось:   $remaining)": ''),
                    'callback_data' =>
                        "cl:$argumentsService->cl".'_'.
                        "sw:Purchase".'_'.
                        "p:$argumentsService->p".'_'.
                        "fp:$pay->id".'_'
                ],
            ]);
        }

        $buttons = self::paginateNavigation($buttons, $totalFolder, $perPage, $argumentsService, $buttonMinus, $buttonPlus);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:AreaMenu"
            ],
        ]);

        return $buttons;
    }

    public static function purchaseButtons(Collection $buttons, ArgumentsService $argumentsService): array
    {
        $pay = Pay::with('product.folder')->find($argumentsService->fp);
        $payProduct = $pay->product;
        $products = $payProduct->folders->pluck('name');
        $productList = "";
        foreach ($products as $product) {
            $productList .= "       --$product" . "\n\r";
        }
        $caption = 'Имя товара: ' . ($payProduct->folder->name) . "\n\r".
            'Цена покупки: ' . $pay->price . "\n\r".
            'Статус: ' . ($pay->subscription > now() || $pay->subscription == null ? "Активна" : "Неактивно") . "\n\r".
            'Подписка: ' . ($pay->subscription ? "✅" : "❌") . "\n\r".
            'Дата покупки: ' . ($pay->created_at->format('d-m-Y h:i:s')) . "\n\r".
            'Дата окончания: ' . ($pay->subscription ? $pay->subscription->format('d-m-Y h:i:s') : "❌") . "\n\r".
            'Список продуктов товара: ' . "\n\r" . (
                $productList
            ) . "\n\r"
        ;

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "p:$argumentsService->p"
            ],
        ]);
        return [$buttons, $caption];
    }
}
