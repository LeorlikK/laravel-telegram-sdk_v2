<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\States\StateMake;
use App\Models\Button;
use App\Models\Folder;
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
        preg_match('/\d+/', $this->stateMake->state->action, $matches);
        Tab::with(['folders' => function($query){
            $query->where('parentId', $this->stateMake->parentId);
        }])->where('name', class_basename($this->stateMake->state->TabClass))->first();
        $parentFolder = Folder::with('buttons')->find($this->stateMake->parentId);

        if ($parentFolder && count($parentFolder->buttons) > 0){
            $this->stateMake->sortedId = $parentFolder->buttons->max('sorted_id') + 1;
        }

        Button::create([
            'folder_id' => $this->stateMake->parentId,
            'text' => $this->stateMake->text,
            'callback' => 'SClass' . $matches[0],
            'sorted_id' => $this->stateMake->sortedId,
        ]);

        return null;
    }
}
