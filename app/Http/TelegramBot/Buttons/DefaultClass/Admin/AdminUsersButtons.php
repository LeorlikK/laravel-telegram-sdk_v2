<?php

namespace App\Http\TelegramBot\Buttons\DefaultClass\Admin;

use App\Http\TelegramBot\Buttons;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Http\TelegramBot\Services\RemainingTimeService;
use App\Models\Pay;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection;

class AdminUsersButtons extends Buttons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $argumentsService->p = $argumentsService->p ?? 1;
        $buttonPlus = ((int)$argumentsService->p) + 1;
        $buttonMinus = ((int)$argumentsService->p) - 1;
        $perPage = 10;
        $users = User::orderBy('created_at', 'DESC')->paginate($perPage, ['*'], null, $argumentsService->p);
        $totalFolder = $users->total();

        foreach ($users->items() as $user) {
            /**
             * @var $user User
             */
            $buttons->add([
                ['text' => $user->username . " ( " . ($user->tg_id) . " )",
                    'callback_data' =>
                        "cl:$argumentsService->cl".'_'.
                        "sw:User".'_'.
                        "p:$argumentsService->p".'_'.
                        "fp:$user->id".'_'
                ],
            ]);
        }

        $buttons = self::paginateNavigation($buttons, $totalFolder, $perPage, $argumentsService, $buttonMinus, $buttonPlus);

        $buttons->add([
            ['text' => '🔍 Find by tg-id', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:FindById".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp"
            ]
        ]);
        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:AdminMenu".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "s:1"
            ],
        ]);

        return $buttons;
    }

    public static function giveProductButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $argumentsService->p = $argumentsService->p ?? 1;
        $buttonPlus = ((int)$argumentsService->p) + 1;
        $buttonMinus = ((int)$argumentsService->p) - 1;
        $perPage = 10;
        $products = Product::with('folder')
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage, ['*'], null, $argumentsService->p);
        $totalFolder = $products->total();

        foreach ($products->items() as $product) {
            /**
             * @var $product Product
             */
            $buttons->add([
                ['text' => ($product->folder->name) . " ( " . ($product->tg_id) . " )",
                    'callback_data' =>
                        "cl:$argumentsService->cl".'_'.
                        "sw:User".'_'.
                        "p:$argumentsService->p".'_'.
                        "fp:$argumentsService->fp".'_'.
                        "v:$product->id".'_'.
                        "r:$argumentsService->r"
                ],
            ]);
        }

        $buttons = self::paginateNavigation($buttons, $totalFolder, $perPage, $argumentsService, $buttonMinus, $buttonPlus);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:AdminUsers".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "s:1"
            ],
        ]);
        return $buttons;
    }

    public static function findByIdButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => '◀️ Cancel', 'callback_data' =>
                "cl:AdminUsers".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "s:1"
            ],
        ]);
        return $buttons;
    }

    public static function userButtons(Collection $buttons, ArgumentsService $argumentsService): array
    {
        $user = User::with('pays')->where('id', $argumentsService->fp)->first();
        $mail = str_replace('_', '', $user->mail);

        $caption = 'tg id: ' . $user->tg_id . "\n\r".
            'username: ' . $user->username . "\n\r".
            'first name: ' . $user->first_name . "\n\r".
            'last name: ' . $user->last_name . "\n\r".
            'language: ' . $user->language . "\n\r".
            'role: ' . $user->role->name . "\n\r".
            'mail: ' . $mail . "\n\r".
            'number: ' . $user->number . "\n\r".
            'edit: ' . $user->edit . "\n\r".
            'is premium: ' . $user->is_premium . "\n\r".
            'is blocked: ' . $user->is_blocked;

        $buttons->add([
            ['text' => '♻️ Change Role', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:ChangeRole".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "r:$argumentsService->r"
            ]
        ]);
        $buttons->add([
            ['text' => '🛍 User purchases' . ($user->pays->count() > 0 ? '( ' . $user->pays->count() . ' pays' . ' )' : ""), 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:Purchase".'_'.
//                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "r:$argumentsService->r"
            ]
        ]);
        $buttons->add([
            ['text' => '💬 Write to the user', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:Write".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "r:$argumentsService->r"
            ]
        ]);
        if ($user->is_blocked){
            $buttons->add([
                ['text' => '✅ Unlock User', 'callback_data' =>
                    "cl:$argumentsService->bk".'_'.
                    "sw:ConfirmUnlock".'_'.
                    "p:$argumentsService->p".'_'.
                    "fp:$argumentsService->fp".'_'.
                    "r:$argumentsService->r"
                ]
            ]);
        }else{
            $buttons->add([
                ['text' => '❌ Blocked User', 'callback_data' =>
                    "cl:$argumentsService->bk".'_'.
                    "sw:ConfirmBlock".'_'.
                    "p:$argumentsService->p".'_'.
                    "fp:$argumentsService->fp".'_'.
                    "r:$argumentsService->r"
                ]
            ]);
        }

        if ($argumentsService->r){
            $buttons->add([
                ['text' => '↖️ Report', 'callback_data' =>
                    "cl:AdminReports".'_'.
                    "sw:Report".'_'.
                    "p:$argumentsService->p".'_'.
                    "fp:$argumentsService->r"
                ],
            ]);
        }else{
            $buttons->add([
                ['text' => '◀️ Back', 'callback_data' =>
                    "cl:$argumentsService->bk".'_'.
                    "p:$argumentsService->p".'_'
                ],
            ]);
        }

        return [$buttons, $caption];
    }

    public static function changeRoleButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $user = User::where('id', $argumentsService->fp)->first();
        $roles = Role::all();

        foreach ($roles as $role) {
            if ($user->role_id !== $role->id) {
                $buttons->add([
                    ['text' => $role->name . "($role->visibility)", 'callback_data' =>
                        "cl:$argumentsService->cl".'_'.
                        "sw:ConfirmChangeRole".'_'.
                        "p:$argumentsService->p".'_'.
                        "fp:$argumentsService->fp".'_'.
                        "fp:$argumentsService->fp".'_'.
                        "r:$argumentsService->r".'_'.
                        "v:$role->id"
                    ]
                ]);
            }
        }

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:User".'_'.
                "p:$argumentsService->p".'_'.
                "r:$argumentsService->r".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }

    public static function confirmChangeRoleButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => "✔️ Confirm", 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:MakeChangeRole".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "r:$argumentsService->r".'_'.
                "v:$argumentsService->v"
            ]
        ]);

        $buttons->add([
            ['text' => '◀️ Cancel', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:ChangeRole".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "r:$argumentsService->r".'_'.
                "v:$argumentsService->v".'_'.
                "s:1"
            ],
        ]);

        return $buttons;
    }

    public static function userConfirmUnlockButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => "✔️ Confirm", 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:MakeUserUnlock".'_'.
                "p:$argumentsService->p".'_'.
                "r:$argumentsService->r".'_'.
                "fp:$argumentsService->fp"
            ]
        ]);

        $buttons->add([
            ['text' => '◀️ Cancel', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:User".'_'.
                "p:$argumentsService->p".'_'.
                "r:$argumentsService->r".'_'.
                "fp:$argumentsService->fp".'_'.
                "s:1"
            ],
        ]);

        return $buttons;
    }

    public static function userConfirmBlockButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => "✔️ Confirm", 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:MakeUserBlock".'_'.
                "p:$argumentsService->p".'_'.
                "r:$argumentsService->r".'_'.
                "fp:$argumentsService->fp"
            ]
        ]);

        $buttons->add([
            ['text' => '◀️ Cancel', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:User".'_'.
                "p:$argumentsService->p".'_'.
                "r:$argumentsService->r".'_'.
                "fp:$argumentsService->fp".'_'.
                "s:1"
            ],
        ]);

        return $buttons;
    }

    public static function purchaseUserButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => "➕ Add a purchase to a user", 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Add".'_'.
//                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "r:$argumentsService->r"
            ],
        ]);
        $buttons->add([
            ['text' => "❌ Delete a purchase from a user", 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Del".'_'.
//                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "r:$argumentsService->r"
            ],
        ]);

        $argumentsService->p = $argumentsService->p ?? 1;
        $buttonPlus = ((int)$argumentsService->p) + 1;
        $buttonMinus = ((int)$argumentsService->p) - 1;
        $perPage = 10;
        $pays = Pay::with('product.folder')->where('user_id', $argumentsService->fp)
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
                        "cl:IA".'_'.
                        "er:7"
                ],
            ]);
        }

        $buttons = self::paginateNavigation($buttons, $totalFolder, $perPage, $argumentsService, $buttonMinus, $buttonPlus);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:User".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "r:$argumentsService->r"
            ],
        ]);

        return $buttons;
    }

    public static function addButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $pays = Pay::with('product')->where('user_id', $argumentsService->fp)->get();
        $productsId = collect();
        $pays->each(function ($item)use($productsId){
            $productsId->add($item->product->id);
        });
        $productsId = $productsId->toArray();

        $argumentsService->p = $argumentsService->p ?? 1;
        $buttonPlus = ((int)$argumentsService->p) + 1;
        $buttonMinus = ((int)$argumentsService->p) - 1;
        $perPage = 10;
        $products = Product::with('folder')
            ->whereNotIn('id', $productsId)
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage, ['*'], null, $argumentsService->p);
        $totalFolder = $products->total();

        foreach ($products->items() as $product) {
            /**
             * @var $product Product
             */
            $buttons->add([
                ['text' => $product->folder->name,
                    'callback_data' =>
                        "cl:$argumentsService->cl".'_'.
                        "sw:MakeAddPay".'_'.
                        "fp:$argumentsService->fp".'_'.
                        "v:$product->id".'_'.
                        "r:$argumentsService->r"
                ],
            ]);
        }

        $buttons = self::paginateNavigation($buttons, $totalFolder, $perPage, $argumentsService, $buttonMinus, $buttonPlus);

        $buttons->add([
            ['text' => '◀️ Cancel', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:Purchase".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "r:$argumentsService->r".'_'.
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
        $pays = Pay::with('product.folder')
            ->where('user_id', $argumentsService->fp)
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage, ['*'], null, $argumentsService->p);

        $totalFolder = $pays->total();

        foreach ($pays->items() as $pay) {
            /**
             * @var $pay Pay
             */
            $buttons->add([
                ['text' => $pay->product->folder->name,
                    'callback_data' =>
                        "cl:$argumentsService->cl".'_'.
                        "sw:MakeDelPay".'_'.
                        "fp:$argumentsService->fp".'_'.
                        "r:$argumentsService->r".'_'.
                        "v:$pay->id"
                ],
            ]);
        }

        $buttons = self::paginateNavigation($buttons, $totalFolder, $perPage, $argumentsService, $buttonMinus, $buttonPlus);

        $buttons->add([
            ['text' => '◀️ Cancel', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:Purchase".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "r:$argumentsService->r".
                "s:1"
            ],
        ]);

        return $buttons;
    }

    public static function choiceWriteButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => '✉️ Write to chat', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:WriteU".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "r:$argumentsService->r"
            ],
        ]);
//        $buttons->add([
//            ['text' => '📨 Write to personal area', 'callback_data' =>
//                "cl:$argumentsService->bk".'_'.
//                "sw:WriteLK".'_'.
//                "ac:N".'_'.
//                "p:$argumentsService->p".'_'.
//                "fp:$argumentsService->fp".'_'.
//                "r:$argumentsService->r"
//            ],
//        ]);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:User".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "r:$argumentsService->r"
            ],
        ]);

        return $buttons;
    }

    public static function cancelWriteButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => '◀️ Cancel', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:Write".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "s:1".'_'.
                "r:$argumentsService->r"
            ],
        ]);

        return $buttons;
    }
//
//    public static function cancelWriteLKButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
//    {
//        $buttons->add([
//            ['text' => '◀️ Cancel', 'callback_data' =>
//                "cl:$argumentsService->bk".'_'.
//                "sw:Write".'_'.
//                "p:$argumentsService->p".'_'.
//                "fp:$argumentsService->fp".'_'.
//                "s:1".'_'.
//                "r:$argumentsService->r"
//            ],
//        ]);
//        return $buttons;
//    }
}
