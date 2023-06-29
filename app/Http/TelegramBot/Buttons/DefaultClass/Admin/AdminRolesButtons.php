<?php

namespace App\Http\TelegramBot\Buttons\DefaultClass\Admin;

use App\Http\TelegramBot\PaginateButtons;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\Folder;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection;

class AdminRolesButtons extends PaginateButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => '➕ Создать роль', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:CreateRole"
            ]
        ]);
        $buttons->add([
            ['text' => '♻️ Изменить роль', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:СhoiceChangeRole"
            ]
        ]);
        $buttons->add([
            ['text' => '❌ Удалить роль', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:СhoiceDeleteRole"
            ]
        ]);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:AdminMenu"
            ],
        ]);

        return $buttons;
    }

    public static function createRoleButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }

    public static function choiceChangeRoleButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $argumentsService->p = $argumentsService->p ?? 1;
        $buttonPlus = ((int)$argumentsService->p) + 1;
        $buttonMinus = ((int)$argumentsService->p) - 1;
        $perPage = 10;
        $roles = Role::orderBy('id', 'DESC')->paginate($perPage, ['*'], null, $argumentsService->p);
        $totalFolder = $roles->total();

        foreach ($roles->items() as $role) {
            /**
             * @var $role Role
             */
            $buttons->add([
                ['text' => $role->name . "($role->visibility)" . ($role->id == 1 ? "🔒" : ""),
                    'callback_data' => $role->id == 1 ? "cl:IA".'_'."er:11":
                        "cl:$argumentsService->cl".'_'.
                        "sw:ChangeRole".'_'.
                        "p:$argumentsService->p".'_'.
                        "fp:$role->id".'_'
                ],
            ]);
        }

        $buttons = self::paginateNavigation($buttons, $totalFolder, $perPage, $argumentsService, $buttonMinus, $buttonPlus);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "p:$argumentsService->p"
            ],
        ]);

        return $buttons;
    }

    public static function changeRoleButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => ($argumentsService->fp == 2 ? "🔒 " : "♻️ ") . 'Изменить имя', 'callback_data' =>
                $argumentsService->fp == 2 ? "cl:IA".'_'."er:12" :
                "cl:$argumentsService->cl".'_'.
                "sw:ChangeRoleName".'_'.
                "p:$argumentsService->p"."_".
                "fp:$argumentsService->fp"
            ]
        ]);
        $buttons->add([
            ['text' => '♻️ Изменить значение', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:ChangeRoleValue".'_'.
                "p:$argumentsService->p"."_".
                "fp:$argumentsService->fp"
            ]
        ]);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                'sw:СhoiceChangeRole'.'_'.
                "p:$argumentsService->p"."_".
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }

    public static function confirmChangeRoleNameButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:ChangeRole".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }

    public static function confirmChangeRoleValueButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:ChangeRole".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }

    public static function choiceDeleteRoleButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $argumentsService->p = $argumentsService->p ?? 1;
        $buttonPlus = ((int)$argumentsService->p) + 1;
        $buttonMinus = ((int)$argumentsService->p) - 1;
        $perPage = 10;
        $roles = Role::whereNotIn('id', [1,2])->orderBy('id', 'DESC')->paginate($perPage, ['*'], null, $argumentsService->p);
        $totalFolder = $roles->total();

        foreach ($roles->items() as $role) {
            /**
             * @var $role Role
             */
            if ($role->id != 1 && $role->id != 2){
                $buttons->add([
                    ['text' => $role->name . "($role->visibility)",
                        'callback_data' =>
                            "cl:$argumentsService->cl".'_'.
                            "sw:ConfirmDeleteRole".'_'.
                            "p:$argumentsService->p".'_'.
                            "fp:$role->id".'_'
                    ],
                ]);
            }
        }

        $buttons = self::paginateNavigation($buttons, $totalFolder, $perPage, $argumentsService, $buttonMinus, $buttonPlus);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:AdminRoles"
            ],
        ]);

        return $buttons;
    }

    public static function confirmDeleteRoleButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => '✔️ Confirm', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                'sw:MakeDeleteRole'.'_'.
                "p:$argumentsService->p".'_'.
                "v:del"
            ],
        ]);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                'sw:СhoiceDeleteRole'.'_'.
                "p:$argumentsService->p"
            ],
        ]);

        return $buttons;
    }
}
