<?php

namespace App\Http\TelegramBot\States\Make\Admin;

use App\Http\TelegramBot\Info\Exceptions\InputException;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Pay;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class MakeAddPayForUser
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        if ($this->stateMake->argumentsService->v){
            $user = User::find($this->stateMake->parentId)->first();
            $product = Product::find($this->stateMake->argumentsService->v)->first();

            Pay::create([
                'user_id' => $this->stateMake->parentId,
                'subscription' => $product->subscription ? now()->addHours($product->subscription) : null,
                'product_id' => $this->stateMake->argumentsService->v,
                'price' => $product->price . " $product->currency"
            ]);
            Cache::forget($user->tg_id);

            $this->stateMake->argumentsService->er = '29';
            (new InputException($this->stateMake->user, $this->stateMake->update,
                $this->stateMake->argumentsService))->handleCallbackQuery();
            return null;
        }

        return '7';
    }
}
