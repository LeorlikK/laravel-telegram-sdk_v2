<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\Pay;
use App\Models\Product;
use App\Models\State;
use App\Models\Tab;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class MakePayProductClass
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
            'product_id' => $product->id,
        ]);
        $this->stateMake->user->updatePurchasedProducts();
        $this->stateMake->user->updateCache($this->stateMake->user);

        return null;
    }
}
