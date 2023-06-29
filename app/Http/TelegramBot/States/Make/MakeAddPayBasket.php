<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\Product;
use App\Models\State;
use App\Models\Tab;
use App\Models\User;

class MakeAddPayBasket
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        if ($this->stateMake->argumentsService->v){
            $folder = Folder::with('product')->find($this->stateMake->parentId);
            $product = $folder->product;
            $linkFolder = Folder::find($this->stateMake->argumentsService->v);
            $product->folders()->attach($linkFolder->id);

            return null;
        }

        return '16';
    }
}
