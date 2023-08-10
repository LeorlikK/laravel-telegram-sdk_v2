<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\Info\Alerts\InputAlert;
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
                $min = config('telegram.pay.min_and_max_price.rub_min');
                $max = config('telegram.pay.min_and_max_price.rub_max');
            }elseif($currency == 2){
                $currency = 'usd';
                $min = config('telegram.pay.min_and_max_price.usd_min');
                $max = config('telegram.pay.min_and_max_price.usd_max');
            }elseif ($currency == 3){
                $currency = 'eur';
                $min = config('telegram.pay.min_and_max_price.eur_min');
                $max = config('telegram.pay.min_and_max_price.eur_max');
            }else{
                $currency = null;
                $min = null;
                $max = null;
            }

            if ($currency && is_numeric($this->stateMake->text)){
                if ($this->stateMake->text > $max){
                    return '24';
                }elseif ($this->stateMake->text < $min){
                   return '23';
                }

                $folder = Folder::with('product')->find($this->stateMake->parentId);
                $product = $folder->product;
                $product->price = $this->stateMake->text;
                $product->currency = $currency;
                $product->save();

                $this->stateMake->argumentsService->er = '20';
                (new InputAlert($this->stateMake->user, $this->stateMake->update,
                    $this->stateMake->argumentsService))->handleCallbackQuery();
                return null;
            }

            return '14';

        }else{
            return '15';
        }
    }
}
