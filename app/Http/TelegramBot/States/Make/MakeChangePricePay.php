<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\Info\Exceptions\InputException;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;

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

                $this->stateMake->argumentsService->er = '20';
                (new InputException($this->stateMake->user, $this->stateMake->update,
                    $this->stateMake->argumentsService))->handleCallbackQuery();
                return null;
            }

            return '14';

        }else{
            return '15';
        }
    }
}
