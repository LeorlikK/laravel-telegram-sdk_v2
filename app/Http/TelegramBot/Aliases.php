<?php

namespace App\Http\TelegramBot;

use App\Http\TelegramBot\Actions\MenuA;
use App\Http\TelegramBot\Actions\MenuM;
use App\Http\TelegramBot\Actions\Modules\ChangeBasketPay;
use App\Http\TelegramBot\Actions\Modules\ChangePaywall;
use App\Http\TelegramBot\Actions\Modules\ChangePeriodPay;
use App\Http\TelegramBot\Actions\Modules\ChangePricePay;
use App\Http\TelegramBot\Actions\Modules\ChangeSecrecy;
use App\Http\TelegramBot\Actions\Modules\ChangeSorted;
use App\Http\TelegramBot\Actions\Modules\ChangeVisibility;
use App\Http\TelegramBot\Actions\Modules\Create;
use App\Http\TelegramBot\Actions\Modules\CreateSpecialClass;
use App\Http\TelegramBot\Actions\Modules\Delete;
use App\Http\TelegramBot\Components\DefaultClass\Admin\AdminMenu;
use App\Http\TelegramBot\Components\DefaultClass\Admin\AdminReports;
use App\Http\TelegramBot\Components\DefaultClass\Admin\AdminRoles;
use App\Http\TelegramBot\Components\DefaultClass\Admin\AdminUsers;
use App\Http\TelegramBot\Components\DefaultClass\PersonalArea\AreaFeedback;
use App\Http\TelegramBot\Components\DefaultClass\PersonalArea\AreaMenu;
use App\Http\TelegramBot\Components\DefaultClass\PersonalArea\AreaPurchased;
use App\Http\TelegramBot\Components\RecursionClass\MenuR;
use App\Http\TelegramBot\Components\SpecialClass\PayS;
use App\Http\TelegramBot\Info\Alerts\InputAlert;
use App\Http\TelegramBot\Info\Exceptions\InputException;
use App\Http\TelegramBot\TradeShop\Yookassa;


class Aliases
{
    public static function getFullNameSpace(string $class):string
    {
        $array = [
            'MenuR' => MenuR::class,

            'Create' => Create::class,
            'CreateSpecialClass' => CreateSpecialClass::class,
            'ChangeSecrecy' => ChangeSecrecy::class,
            'ChangeVisibility' => ChangeVisibility::class,
            'ChangeSorted' => ChangeSorted::class,
            'Delete' => Delete::class,
            'ChangePaywall' => ChangePaywall::class,
            'ChangePricePay' => ChangePricePay::class,
            'ChangePeriodPay' => ChangePeriodPay::class,
            'ChangeBasketPay' => ChangeBasketPay::class,

            // Special Class
            'PayS' => PayS::class,

            // Trade Shop
            'Yoo' => Yookassa::class,

            // Action
            'MenuA' => MenuA::class,
            'MenuM' => MenuM::class,

            // Exceptions and Alert
            'IA' => InputAlert::class,
            'IE' => InputException::class,

            // Admin
            'AdminMenu' => AdminMenu::class,
            'AdminUsers' => AdminUsers::class,
            'AdminRoles' => AdminRoles::class,
            'AdminReports' => AdminReports::class,

            // Personal Area
            'AreaMenu' => AreaMenu::class,
            'AreaPurchased' => AreaPurchased::class,

            // Reports
            'AreaFeedback' => AreaFeedback::class
        ];

        return $array[$class];
    }
}
