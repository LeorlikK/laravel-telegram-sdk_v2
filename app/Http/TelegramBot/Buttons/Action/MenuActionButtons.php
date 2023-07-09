<?php

namespace App\Http\TelegramBot\Buttons\Action;

use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\Folder;
use Illuminate\Support\Collection;

class MenuActionButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService):Collection
    {
        $folder = Folder::with('product')->find($argumentsService->fp);
        $buttons->add([
            ['text' => '📚 Create Folder', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:CreateF".'_'.
                "bk:$argumentsService->bk".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"]
        ]);
        if ($argumentsService->fp !== null){
            $buttons->add([
                ['text' => '✏️ Change Name Folder', 'callback_data' =>
                    "cl:$argumentsService->cl".'_'.
                    "sw:ChangeNameF".'_'.
                    "bk:$argumentsService->bk".'_'.
                    "ac:N".'_'.
                    "fp:$argumentsService->fp"],
                ['text' => '📊 Change Emoji Folder', 'callback_data' =>
                    "cl:$argumentsService->cl".'_'.
                    "sw:ChangeEmojiF".'_'.
                    "bk:$argumentsService->bk".'_'.
                    "ac:N".'_'.
                    "fp:$argumentsService->fp"]
            ]);
        }

        $buttons->add([
            ['text' => '📌 Change Caption Folder', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:ChangeCaptionF".'_'.
                "bk:$argumentsService->bk".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"],
            ['text' => '🖼 Change Image Folder', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:ChangeImageF".'_'.
                "bk:$argumentsService->bk".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"]
        ]);

        if ($argumentsService->fp !== null){
            $buttons->add([
                ['text' => '⏳ Change Secrecy Folder' . ($folder->displayViewBool() ? '( ' . $folder->display . ' )' : ""), 'callback_data' =>
                    "cl:$argumentsService->cl".'_'.
                    "sw:ChangeSecrecyF".'_'.
                    "bk:$argumentsService->bk".'_'.
                    "ac:N".'_'.
                    "fp:$argumentsService->fp"]
            ]);
        }
        if ($argumentsService->fp !== null){
            $buttons->add([
                ['text' => '🔒 Change Visibility Folder' . '( ' . ($folder->visibility) . ($folder->blocked ? "👁‍🗨" : "👁") . ' )', 'callback_data' =>
                    "cl:$argumentsService->cl".'_'.
                    "sw:ChangeVisibilityF".'_'.
                    "bk:$argumentsService->bk".'_'.
                    "ac:N".'_'.
                    "fp:$argumentsService->fp"]
            ]);
        }
        if ($argumentsService->fp !== null){
            $buttons->add([
                ['text' => '↕️ Change Sorted Folder', 'callback_data' =>
                    "cl:$argumentsService->cl".'_'.
                    "sw:ChangeSortedF".'_'.
                    "bk:$argumentsService->bk".'_'.
                    "ac:N".'_'.
                    "fp:$argumentsService->fp"]
            ]);
        }
        if ($argumentsService->fp !== null){
            $buttons->add([
                ['text' => '❌ Delete Folder', 'callback_data' =>
                    "cl:$argumentsService->cl".'_'.
                    "sw:DeleteF".'_'.
                    "bk:$argumentsService->bk".'_'.
                    "ac:N".'_'.
                    "fp:$argumentsService->fp"]
            ]);
        }
        if ($argumentsService->fp !== null){
            $buttons->add([
                ['text' => '💳 Paywall' . '( ' . ($folder->blockedPay ? "✅" : "❌") . ' )', 'callback_data' =>
                    "cl:$argumentsService->cl".'_'.
                    "sw:PaywallF".'_'.
                    "bk:$argumentsService->bk".'_'.
                    "ac:N".'_'.
                    "fp:$argumentsService->fp"]
            ]);
        }
        $buttons->add([
            ['text' => '🔹 Create Special Class', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:CreateC".'_'.
                "bk:$argumentsService->bk".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"]
        ]);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>  "cl:$argumentsService->bk".
                '_'."ac:N".'_'."fp:$argumentsService->fp"],
        ]);

        return $buttons;
    }
}
