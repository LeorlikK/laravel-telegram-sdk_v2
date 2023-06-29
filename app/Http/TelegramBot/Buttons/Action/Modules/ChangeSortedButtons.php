<?php

namespace App\Http\TelegramBot\Buttons\Action\Modules;

use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\Folder;
use Illuminate\Support\Collection;

class ChangeSortedButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $folder = Folder::find($argumentsService->fp);
        $foldersParent = Folder::where('parentId', $folder->parentId)->where('tab_id', $folder->tab_id)->orderBy('sorted_id')->get();

        foreach ($foldersParent as $folderParent){
            $buttons->add([
                ['text' => $folderParent->id === $folder->id ? '🔃 ' . $folderParent->name : $folderParent->name, 'callback_data' =>
                    $folderParent->id === $folder->id ? 'cl:IA'.'_'.'er:5' :
                    "cl:$argumentsService->cl".'_'.
                    "sw:Confirm".'_'.
                    "bkS:$argumentsService->bkS".'_'.
                    "ac:N".'_'.
                    "fp:$argumentsService->fp".'_'.
                    "v:$folderParent->id"
                ],
            ]);
        }

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }
}
