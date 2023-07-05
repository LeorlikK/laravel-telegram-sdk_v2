<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\Info\Alerts\InputAlert;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

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

            $users = User::whereHas('pays', function($pay)use($product){
                $pay->where('product_id', $product->id);
            })->pluck('tg_id');
            Cache::deleteMultiple($users);

            $this->stateMake->argumentsService->er = '13';
            (new InputAlert($this->stateMake->user, $this->stateMake->update,
                $this->stateMake->argumentsService))->handleCallbackQuery();
            return null;
        }

        return '16';
    }
}
