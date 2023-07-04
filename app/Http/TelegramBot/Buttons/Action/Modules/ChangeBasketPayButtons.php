<?php

namespace App\Http\TelegramBot\Buttons\Action\Modules;

use App\Http\TelegramBot\Buttons;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\Folder;
use App\Models\Product;
use Illuminate\Support\Collection;

class ChangeBasketPayButtons extends Buttons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => "➕ Добавить в корзину", 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "bk:$argumentsService->bk".'_'.
                "sw:Add".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                "m:$argumentsService->m".'_'
            ],
        ]);
        $buttons->add([
            ['text' => "❌ Удалить из корзины", 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "bk:$argumentsService->bk".'_'.
                "sw:Del".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                "m:$argumentsService->m".'_'
            ],
        ]);

        $argumentsService->p = $argumentsService->p ?? 1;
        $buttonPlus = ((int)$argumentsService->p) + 1;
        $buttonMinus = ((int)$argumentsService->p) - 1;
        $perPage = 10;
        $product = Product::where('folder_id', $argumentsService->fp)->first();
        $folders = Folder::whereHas('products', function($item)use($product){
            $item->where('product_id', $product->id);
        })->orderBy('created_at', 'DESC')->paginate($perPage, ['*'], null, $argumentsService->p);
        $totalFolder = $folders->total();

        foreach ($folders->items() as $folder) {
            /**
             * @var $folder Folder
             */
            $buttons->add([
                ['text' => $folder->name,
                    'callback_data' =>
                        "cl:IA".'_'.
                        "er:7"
                ],
            ]);
        }

        $buttons = self::paginateNavigation($buttons, $totalFolder, $perPage, $argumentsService, $buttonMinus, $buttonPlus);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }

    public static function addButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $argumentsService->p = $argumentsService->p ?? 1;
        $buttonPlus = ((int)$argumentsService->p) + 1;
        $buttonMinus = ((int)$argumentsService->p) - 1;
        $perPage = 10;
        $folders = Folder::doesntHave('product')->orderBy('created_at', 'DESC')->paginate($perPage, ['*'], null, $argumentsService->p);
        $totalFolder = $folders->total();

        foreach ($folders->items() as $folder) {
            /**
             * @var $folder Folder
             */
            $buttons->add([
                ['text' => $folder->name,
                    'callback_data' =>
                        "cl:$argumentsService->cl".'_'.
                        "sw:ConfirmAdd".'_'.
                        "fp:$argumentsService->fp".'_'.
                        "v:$folder->id".'_'
                ],
            ]);
        }

        $buttons = self::paginateNavigation($buttons, $totalFolder, $perPage, $argumentsService, $buttonMinus, $buttonPlus);

        $buttons->add([
            ['text' => '◀️ Cancel', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "bk:$argumentsService->bk".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                "m:$argumentsService->m".'_'.
                "s:1"
            ],
        ]);

        return $buttons;
    }

    public static function deleteButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $argumentsService->p = $argumentsService->p ?? 1;
        $buttonPlus = ((int)$argumentsService->p) + 1;
        $buttonMinus = ((int)$argumentsService->p) - 1;
        $perPage = 10;
        $product = Product::where('folder_id', $argumentsService->fp)->first();
        $folders = Folder::whereHas('products', function($item)use($product){
            $item->where('product_id', $product->id);
        })->orderBy('created_at', 'DESC')->paginate($perPage, ['*'], null, $argumentsService->p);
        $totalFolder = $folders->total();

        foreach ($folders->items() as $folder) {
            /**
             * @var $folder Folder
             */
            $buttons->add([
                ['text' => $folder->name,
                    'callback_data' =>
                        "cl:$argumentsService->cl".'_'.
                        "sw:ConfirmDel".'_'.
                        "fp:$argumentsService->fp".'_'.
                        "v:$folder->id".'_'
                ],
            ]);
        }

        $buttons = self::paginateNavigation($buttons, $totalFolder, $perPage, $argumentsService, $buttonMinus, $buttonPlus);

        $buttons->add([
            ['text' => '◀️ Cancel', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "bk:$argumentsService->bk".'_'.
                "bkS:$argumentsService->bkS".'_'.
                "fp:$argumentsService->fp".'_'.
                "m:$argumentsService->m".'_'.
                "s:1"
            ],
        ]);

        return $buttons;
    }
}
