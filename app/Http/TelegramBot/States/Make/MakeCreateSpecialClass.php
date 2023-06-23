<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\States\StateMake;
use App\Models\Button;
use App\Models\Folder;
use App\Models\Product;
use App\Models\Tab;

class MakeCreateSpecialClass
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
//        /**
//         * @var $tab Tab|null
//         */
//        preg_match('/\d+/', $this->stateMake->state->action, $matches);
//        Tab::with(['folders' => function($query){
//            $query->where('parentId', $this->stateMake->parentId);
//        }])->where('name', class_basename($this->stateMake->state->TabClass))->first();
//        $parentFolder = Folder::with('buttons')->find($this->stateMake->parentId);
//
//        if ($parentFolder && count($parentFolder->buttons) > 0){
//            $this->stateMake->sortedId = $parentFolder->buttons->max('sorted_id') + 1;
//        }
//
//        Button::create([
//            'folder_id' => $this->stateMake->parentId,
//            'text' => $this->stateMake->text,
//            'callback' => 'SClass' . $matches[0],
//            'sorted_id' => $this->stateMake->sortedId,
//        ]);

        /**
         * @var $tab Tab|null
         */
        $tab = Tab::with(['folders' => function($query){
            $query->where('parentId', $this->stateMake->parentId);
        }])->where('name', class_basename($this->stateMake->state->TabClass))->first();


        if ($tab && $tab->folders){
            $this->stateMake->sortedId = $tab->folders->max('sorted_id') + 1;
        }

        $folder = Folder::create([
            'tab_id' => $tab->id,
            'parentId' => $this->stateMake->parentId,
            'name' => '💳 ' . $this->stateMake->text,
            'sorted_id' => $this->stateMake->sortedId,
            'action' => 'MenuM'
        ]);

        $button = Button::create([
            'folder_id' => $folder->id,
            'text' => '💎 Pay',
            'callback' => 'PayS',
            'sorted_id' => 1,
        ]);

        $product = Product::create([
            'folder_id' => $folder->id,
        ]);

        return null;
    }
}
