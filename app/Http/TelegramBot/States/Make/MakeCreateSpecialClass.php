<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\Info\Alerts\InputAlert;
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

        Button::create([
            'folder_id' => $folder->id,
            'text' => '💎 Pay',
            'callback' => 'PayS',
            'sorted_id' => 1,
        ]);

        Product::create([
            'folder_id' => $folder->id,
        ]);

        $this->stateMake->argumentsService->er = '25';
        (new InputAlert($this->stateMake->user, $this->stateMake->update,
            $this->stateMake->argumentsService))->handleCallbackQuery();
        return null;
    }
}
