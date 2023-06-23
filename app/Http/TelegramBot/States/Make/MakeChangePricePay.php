<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\Product;

class MakeChangePricePay
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        $currencyVal = preg_match('/\d+/', $this->stateMake->state->action, $matches);
        if ($currencyVal){
            $currency = $matches[0];
            if ($currency == 1){
                $currency = 'rub';
            }elseif($currency == 2){
                $currency = 'usd';
            }elseif ($currency == 3){
                $currency = 'eur';
            }else{
                $currency = null;
            }

            if ($currency && is_numeric($this->stateMake->text)){
                $folder = Folder::with('product')->find($this->stateMake->parentId);
                $product = $folder->product;
                $product->price = $this->stateMake->text;
                $product->currency = $currency;
                $product->save();

                return null;
            }

            return 'Указано некорректное значение';

        }else{
            return 'Неправильный ID валюты';
        }
    }
}
