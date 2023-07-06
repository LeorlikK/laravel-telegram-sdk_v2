<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\Info\Exceptions\InputException;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\Pay;

class MakePayProduct
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        $folder = Folder::with('product')->find($this->stateMake->parentId);
        $product = $folder->product;
        Pay::create([
            'user_id' => $this->stateMake->user->id,
            'subscription' => $product->subscription ? now()->addHours($product->subscription) : null,
            'product_id' => $product->id,
            'price' => $product->price . " $product->currency"
        ]);
        $this->stateMake->user->state()->delete();
        $this->stateMake->user->unsetRelation('state');
        $this->stateMake->user->updatePurchasedProducts();
        $this->stateMake->user->updateCache($this->stateMake->user);

        $this->stateMake->argumentsService->er = '28';
        (new InputException($this->stateMake->user, $this->stateMake->update,
            $this->stateMake->argumentsService))->handleCallbackQuery();
        return null;
    }
}
