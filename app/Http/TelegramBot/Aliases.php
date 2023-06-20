<?php

namespace App\Http\TelegramBot;

use App\Http\TelegramBot\Actions\MenuA;
use App\Http\TelegramBot\Actions\Modules\ChangeSecrecy;
use App\Http\TelegramBot\Actions\Modules\ChangeSorted;
use App\Http\TelegramBot\Actions\Modules\ChangeVisibility;
use App\Http\TelegramBot\Actions\Modules\Create;
use App\Http\TelegramBot\Actions\Modules\CreateSpecialClass;
use App\Http\TelegramBot\Actions\Modules\Delete;
use App\Http\TelegramBot\Components\DefaultClass\TestD;
use App\Http\TelegramBot\Components\RecursionClass\MenuR;
use App\Http\TelegramBot\Components\SpecialClass\TestS;
use App\Http\TelegramBot\Exceptions\BlockedFolderException;


class Aliases
{
    public static function getFullNameSpace(string $class):string
    {
        $array = [
            'TestD' => TestD::class,
            'MenuR' => MenuR::class,
            'MenuA' => MenuA::class,
            'Create' => Create::class,
            'CreateSpecialClass' => CreateSpecialClass::class,
            'ChangeSecrecy' => ChangeSecrecy::class,
            'ChangeVisibility' => ChangeVisibility::class,
            'ChangeSorted' => ChangeSorted::class,
            'Delete' => Delete::class,

            // Exceptions
            'blockedF' => BlockedFolderException::class,

            // SpecialCLass
            'SClass1' => TestS::class,
            'SClass2' => TestS::class,
            'SClass3' => TestS::class,
            'SClass4' => TestS::class,
            'SClass5' => TestS::class,
        ];

        return $array[$class];
    }
}
