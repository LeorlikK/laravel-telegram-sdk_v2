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

        $tab = Tab::where('name', class_basename($this))->first(); // 4

        $caption = $this->recursionCaption($tab);
        $photo = $this->recursionPhoto($tab);

        return [$photo, $caption, $buttons];
    }

    private function next(string $parentId): Collection
    {
        $buttons = collect();

//        $query = Tab::query()->with(['folders' => function($query)use($parentId){ // 1
//            $query->where('parentId', $parentId)->orderBy('sorted_id');
//        }])->where('name', class_basename($this));
//        $tab = $query->first();
//
//        $tab = Tab::where('name', class_basename($this))->first();

        $this->argumentsService->p = $this->argumentsService->p ?? 1;
        $buttonPlus = ((int)$this->argumentsService->p) + 1;
        $buttonMinus = ((int)$this->argumentsService->p) - 1;
        $perPage = 5;
        $folders = Folder::with(['buttons', 'product'])
            ->where('parentId', $parentId)
            ->where('tab_id', Tab::where('name', class_basename($this))->first()->id)
            ->paginate($perPage, ['*'], null, $this->argumentsService->p);
        $totalFolder = $folders->total();

        $this->folderParent = Folder::with(['buttons', 'product'])->where('id', $parentId)->first(); // 2
        $buttons = $this->recursionFolders($buttons, $folders->items(), $parentId, $parentId);

        $this->argumentsService->ac = $parentId == 0 ? null : 'B';
        $this->argumentsService->fp = $parentId == 0 ? null : $parentId;

        $recursionButtons = new RecursionButtons();
        $buttons = $recursionButtons->getPaginate($buttons, $totalFolder, $perPage, $this->argumentsService, $buttonMinus, $buttonPlus);

        return $buttons;
    }

    private function back(string $parentId): Collection
    {
        $buttons = collect();

        $this->argumentsService->p = $this->argumentsService->p ?? 1;
        $buttonPlus = ((int)$this->argumentsService->p) + 1;
        $buttonMinus = ((int)$this->argumentsService->p) - 1;
        $perPage = 5;
        $this->folderParent = Folder::with(['buttons', 'product'])->where('id', $parentId)->first();
        $folders = Folder::where('parentId', $this->folderParent->parentId)
            ->where('tab_id', $this->folderParent->tab_id)
            ->orderBy('sorted_id')
            ->paginate($perPage, ['*'], null, $this->argumentsService->p);
        $totalFolder = $folders->total();

        $buttons = $this->recursionFolders($buttons, $folders, $parentId, $this->folderParent->parentId);

        $backId = $this->folderParent->parentId;
        dump('BACK: ' . $backId);
        $this->argumentsService->ac = $backId == 0 ? null : 'B';
        $this->argumentsService->fp = $backId == 0 ? null : $backId;

        $recursionButtons = new RecursionButtons();
        $buttons = $recursionButtons->getPaginate($buttons, $totalFolder, $perPage, $this->argumentsService, $buttonMinus, $buttonPlus);

        return $buttons;
    }

    private function recursionCaption(Tab $tab):string
    {
        if (isset($this->argumentsService->fp)) {
            $caption = $this->folderParent->caption .
                "\n\r" . "\n\r" .
                '◀️ ' . $this->folderParent->name;
            if ($this->administrator){
                $caption .= "\n\r" .
                    'Видимость: ' . $this->folderParent->visibility . ($this->folderParent->displayViewBool() ? "\n\r" .
                        'Скрыта до: ' . ($this->folderParent->displayViewString()) : ''). "\n\r" .
                        ($this->folderParent->action === 'MenuM' ?
                            "Price: " . $this->folderParent->product->price . ' ' . $this->folderParent->product->currency . "\n\r" .
                            "Purchase: " . ($this->folderParent->product->subscription ? $this->folderParent->product->subscription . ' h' : "♾") . "\n\r" .
                            "Products: " . ($this->folderParent->product->folders->count() > 0 ? $this->folderParent->product->folders->count() . '  products' : "❌") . "\n\r"
                            :
                            "");
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

    private function recursionFolders(Collection $buttons,  $folders, int $parentId): Collection
    {
//        $this->folderParent = Folder::with(['buttons', 'product'])->where('id', $lala)->first();

        $timeNow = now();
        if ($this->folderParent && $this->folderParent->parentId != 0){
            $parentsFolders = Folder::where('parentId', $this->folderParent->parentId)
                ->orderBy('sorted_id')
                ->paginate(5, ['*'], null, $this->argumentsService->p);
            $testCollect = collect();

            foreach ($parentsFolders->items() as $parentsFolder) {
                $timeResult = $parentsFolder->display < $timeNow;

                if ($timeResult || $this->administrator){
                    if (($timeResult && $this->user->role->visibility >= $parentsFolder->visibility) || $this->administrator || !$parentsFolder->blocked){

                        $blockedPayCallback = "";
                        if ($parentsFolder->blockedPay){
                            if ($this->administrator) $blockedPayCallback = false;
                            elseif($this->user->purchasedProducts->contains($parentsFolder->id)) $blockedPayCallback = false;
                            else $blockedPayCallback = true;
                        }

                        if ($blockedPayCallback) $callback = "cl:IA".'_'."er:9";
                        else{
                            if(!$this->administrator && $this->user->role->visibility <  $parentsFolder->visibility){
                                $callback = "cl:IA".'_'."er:1".'_'."ac:N".'_'."fp:$parentsFolder->id";
                            }else{
                                $callback = "cl:".class_basename($this).'_'."ac:N".'_'."fp:$parentsFolder->id";
//                                $testCollect->add([$parentsFolder->id => $callback]);
                                $testCollect->put($parentsFolder->id, $callback);
                            }
                        }
                    }
                }
            }

//            dump($this->folderParent->id);
//            dump($testCollect);
//            dump($testCollect->keys());
            $indexThisFolder = $testCollect->keys()->search($this->folderParent->id);
            $back = $indexThisFolder - 1;
            $next = $indexThisFolder + 1;
            $backIndex = $testCollect->keys()[$back] ?? null;
            $nextIndex = $testCollect->keys()[$next] ?? null;
//            dump($backIndex, $this->folderParent->id, $nextIndex);
            if ($backIndex && $nextIndex){
                $buttons->add([
                    ['text' => '⏮', 'callback_data' =>
                        "cl:".class_basename($this).'_'."fp:$backIndex".'_'."p:".($this->argumentsService->p)],
                    ['text' => '⏭', 'callback_data' =>
                        "cl:".class_basename($this).'_'."fp:$nextIndex".'_'."p:".($this->argumentsService->p)],
                ]);
            }elseif ($backIndex){
                $buttons->add([
                    ['text' => '⏮', 'callback_data' =>
                        "cl:".class_basename($this).'_'. "fp:$backIndex".'_'."p:".($this->argumentsService->p)],
                ]);
            }elseif ($nextIndex){
                $buttons->add([
                    ['text' => '⏭', 'callback_data' =>
                        "cl:".class_basename($this).'_'. "fp:$nextIndex".'_'."p:".($this->argumentsService->p)],
                ]);
            }
        }
        foreach ($folders as $folder){
            $timeResult = $folder->display < $timeNow;

            if ($timeResult || $this->administrator){
                if (($timeResult && $this->user->role->visibility >= $folder->visibility) || $this->administrator || !$folder->blocked){

                    $blockedPayText = '';
                    if ($folder->blockedPay){
                        if ($this->administrator && !$this->user->purchasedProducts->contains($folder->id)) $blockedPayText = "💳";
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
                         if(!$this->administrator && $this->user->role->visibility <  $folder->visibility){
                             $callback = "cl:IA".'_'."er:1".'_'."ac:N".'_'."fp:$folder->id";
                        }else{
                             $callback = "cl:".class_basename($this).'_'."ac:N".'_'."fp:$folder->id".'_'."p:".($this->argumentsService->p);
                        }
                    }

                    $buttons->add([
                        ['text' =>
                            $folder->name .
                            ($this->administrator ? "($folder->visibility)" : '') .
                            ($folder->displayViewBool() ? "⏳" : '') .
                            ($this->user->role->visibility <  $folder->visibility ? "🔒" : '').
                            ($this->administrator && $folder->blocked ? "👁‍🗨" : '').
                            $blockedPayText,
                            'callback_data' =>
                                $callback]
                    ]);
                }
            }
        }

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
