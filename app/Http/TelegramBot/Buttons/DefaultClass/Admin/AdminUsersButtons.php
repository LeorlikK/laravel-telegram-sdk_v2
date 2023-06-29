<?php

namespace App\Http\TelegramBot\Buttons\DefaultClass\Admin;

use App\Http\TelegramBot\PaginateButtons;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\Folder;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection;

class AdminUsersButtons extends PaginateButtons
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
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:AdminMenu"
            ],
        ]);

        return $buttons;
    }

    public static function userButtons(Collection $buttons, ArgumentsService $argumentsService): array
    {
        $user = User::where('id', $argumentsService->fp)->first();
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
            ['text' => '♻️ Изменить роль', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:ChangeRole".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp"
            ]
        ]);
        if ($user->is_blocked){
            $buttons->add([
                ['text' => '✅ Разблокировать пользователя', 'callback_data' =>
                    "cl:$argumentsService->bk".'_'.
                    "sw:ConfirmUnlock".'_'.
                    "p:$argumentsService->p".'_'.
                    "fp:$argumentsService->fp"
                ]
            ]);
        }else{
            $buttons->add([
                ['text' => '❌ Заблокировать пользователя', 'callback_data' =>
                    "cl:$argumentsService->bk".'_'.
                    "sw:ConfirmBlock".'_'.
                    "p:$argumentsService->p".'_'.
                    "fp:$argumentsService->fp"
                ]
            ]);
        }

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "p:$argumentsService->p".'_'
            ],
        ]);

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
                "v:$argumentsService->v"
            ]
        ]);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:ChangeRole".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "v:$argumentsService->v"
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
                "fp:$argumentsService->fp"
            ]
        ]);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:User".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp"
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
                "fp:$argumentsService->fp"
            ]
        ]);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:User".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }

}
