<?php

namespace App\Http\TelegramBot;

use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\Folder;
use App\Models\Tab;
use App\Models\User;
use Illuminate\Support\Collection;
use Telegram\Bot\FileUpload\InputFile;

abstract class RecursionClass extends DefaultClass implements RecursionInterface
{
    public Folder|null $folderParent;

    public function getRecursionFoldersAndButtons(): array
    {
        $action = $this->argumentsService->ac ?? null;
        $parentId = $this->argumentsService->fp ?? 0;

        if($action === 'B') $buttons = $this->back($parentId);
        else $buttons = $this->next($parentId);

        $tab = Tab::where('name', class_basename($this))->first();

        $caption = $this->recursionCaption($tab);
        $photo = $this->recursionPhoto($tab);

        return [$photo, $caption, $buttons];
    }

    private function next(string $parentId): Collection
    {
        $buttons = collect();

        $query = Tab::query()->with(['folders' => function($query)use($parentId){
            $query->where('parentId', $parentId)->orderBy('sorted_id');
        }])->where('name', class_basename($this));
        $tab = $query->first();

        $buttons = $this->recursionFolders($buttons, $tab->folders, $parentId, $parentId);

        $this->argumentsService->ac = $parentId == 0 ? null : 'B';
        $this->argumentsService->fp = $parentId == 0 ? null : $parentId;

        return $buttons;
    }

    private function back(string $parentId): Collection
    {
        $buttons = collect();

        $folderLala = Folder::where('id', $parentId)->first();
        $folders = Folder::where('parentId', $folderLala->parentId)->where('tab_id', $folderLala->tab_id)->orderBy('sorted_id')->get();

        $buttons = $this->recursionFolders($buttons, $folders, $parentId, $folderLala->parentId);

        $backId = $folderLala->parentId;
        $this->argumentsService->ac = $backId == 0 ? null : 'B';
        $this->argumentsService->fp = $backId == 0 ? null : $backId;

        return $buttons;
    }

    private function recursionCaption(Tab $tab):string
    {
        if (isset($this->folderParent)) {
            $caption = $this->folderParent->caption .
                "\n\r" . "\n\r" .
                '◀️ ' . $this->folderParent->name;
            if ($this->user->is_administrator){
                $caption .= "\n\r" .
                    'Видимость: ' . $this->folderParent->visibility . ($this->folderParent->displayViewBool() ? "\n\r" .
                        'Скрыта до: ' . ($this->folderParent->displayViewString()) : '');
            }
            return $caption;
        }else{
            return $tab->caption ?? '';
        }
    }

    private function recursionPhoto(Tab $tab):InputFile
    {
        if (isset($this->folderParent)) {
            $photoPath = $this->folderParent->media ?? config('telegram.base_url_image');
        }else{
            $photoPath = $tab->media ?? config('telegram.base_url_image');
        }

        return InputFile::create($photoPath, 'defaultImage');
    }

    private function recursionFolders(Collection $buttons, \Illuminate\Database\Eloquent\Collection $folders, int $parentId, $lala): Collection
    {
        $timeNow = now();
        foreach ($folders as $folder){
            $timeResult = $folder->display < $timeNow;

            if ($timeResult || $this->administrator){
                if (($timeResult && $this->user->role->visibility >= $folder->visibility) || $this->administrator || !$folder->blocked){

                    $blockedPayText = '';
                    if ($folder->blockedPay){
                        if ($this->administrator) $blockedPayText = "💳";
                        elseif($this->user->purchasedProducts->contains($folder->id)) $blockedPayText = "✅";
                        else $blockedPayText = "💳";
                    }

                    $blockedPayCallback = "";
                    if ($folder->blockedPay){
                        if ($this->administrator) $blockedPayCallback = false;
                        elseif($this->user->purchasedProducts->contains($folder->id)) $blockedPayCallback = false;
                        else $blockedPayCallback = true;
                    }

                    if ($blockedPayCallback) $callback = "cl:IA".'_'."er:9";
                    else{
                        $callback = !$this->administrator && $this->user->role->visibility <  $folder->visibility?
                            "cl:IA".'_'."er:1".'_'."ac:N".'_'."fp:$folder->id" :
                            "cl:".class_basename($this).'_'."ac:N".'_'."fp:$folder->id";
                    }

                    $buttons->add([
                        ['text' =>
                            $folder->name .
                            ($this->administrator ? "($folder->visibility)" : '') .
                            ($folder->displayViewBool() ? "⏳" : '') .
                            ($this->user->role->visibility <  $folder->visibility ? "🔒" : '').
                            ($this->administrator && $folder->blocked ? "👁" : '').
                            $blockedPayText,
                            'callback_data' =>
                                $callback]
                    ]);
                }
            }

        }

        $this->folderParent = Folder::with('buttons')->where('id', $lala)->first();
        if (isset($this->folderParent->buttons)){
            foreach ($this->folderParent->buttons as $button){
                $timeResult = $button->display < $timeNow;
                if (($timeResult && $this->user->role->visibility >= $button->visibility) || $this->administrator){
                    $buttons->add([
                        ['text' => $button->text  . ($button->displayViewBool() ? "⏳" : '') .
                        ($button->blocked && $this->user->role->visibility <  $button->visibility  ? "🔒" : ''),
                        'callback_data' =>
                            $button->blocked && $this->user->role->visibility <  $button->visibility ? "cl:IA".'_'."er:1".'_'."ac:N".'_'."fp:$button->id" :
                                "cl:$button->callback" . '_' . "ac:N" . '_' . "fp:$button->folder_id"]]);
                }
            }
        }

        return $buttons;
    }
}
