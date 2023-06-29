<?php

namespace App\Http\TelegramBot\Buttons\Action;

use App\Http\TelegramBot\Services\ArgumentsService;
use Illuminate\Support\Collection;

class MenuActionButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService):Collection
    {
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
                    "fp:$argumentsService->fp"]
            ]);
        }
        if ($argumentsService->fp !== null){
            $buttons->add([
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
                "fp:$argumentsService->fp"]
        ]);
        $buttons->add([
            ['text' => '🖼 Change Image Folder', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:ChangeImageF".'_'.
                "bk:$argumentsService->bk".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"]
        ]);
        if ($argumentsService->fp !== null){
            $buttons->add([
                ['text' => '⏳ Change Secrecy Folder', 'callback_data' =>
                    "cl:$argumentsService->cl".'_'.
                    "sw:ChangeSecrecyF".'_'.
                    "bk:$argumentsService->bk".'_'.
                    "ac:N".'_'.
                    "fp:$argumentsService->fp"]
            ]);
        }
        if ($argumentsService->fp !== null){
            $buttons->add([
                ['text' => '🔒 Change Visibility Folder', 'callback_data' =>
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
                ['text' => '💳 Paywall', 'callback_data' =>
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
