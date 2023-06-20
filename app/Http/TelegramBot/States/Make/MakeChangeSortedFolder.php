<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\Tab;
use Carbon\Carbon;

class MakeChangeSortedFolder
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        $first = Folder::find($this->stateMake->state->parentId);
        $second = Folder::find($this->stateMake->argumentsService->v);

        $this->stateMake->parentId = Folder::find($this->stateMake->state->parentId)->parentId;

        $firstSorted = $first->sorted_id;
        $secondSorted = $second->sorted_id;
        $first->sorted_id = $secondSorted;
        $second->sorted_id = $firstSorted;
        $first->save();
        $second->save();

        return null;
    }
}
