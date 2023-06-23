<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\State;
use App\Models\Tab;
use App\Models\User;

class MakeCreateFolder
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

        Folder::create([
            'tab_id' => $tab->id,
            'parentId' => $this->stateMake->parentId,
            'name' => '📚 ' . $this->stateMake->text,
            'sorted_id' => $this->stateMake->sortedId,
            'action' => class_basename($this->stateMake->state->TabClass)
        ]);

        return null;
    }
}
