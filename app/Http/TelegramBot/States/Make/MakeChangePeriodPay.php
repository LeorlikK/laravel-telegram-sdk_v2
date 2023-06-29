<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\Product;

class MakeChangePeriodPay
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        $folder = Folder::find($this->stateMake->parentId);
        $product = $folder->product;

        if ($this->stateMake->argumentsService->v){
            if ($this->stateMake->argumentsService->v === 'null'){
                $product->subscription = null;
                $product->save();
            }elseif (str_starts_with($this->stateMake->argumentsService->v, 'h')){
                preg_match('/\d+/', $this->stateMake->argumentsService->v, $matches);
                $product->subscription = $matches[0];
                $product->save();
            }elseif (str_starts_with($this->stateMake->argumentsService->v, 'd')){
                preg_match('/\d+/', $this->stateMake->argumentsService->v, $matches);
                $product->subscription = $matches[0]*24;
                $product->save();
            }elseif (str_starts_with($this->stateMake->argumentsService->v, 'w')){
                preg_match('/\d+/', $this->stateMake->argumentsService->v, $matches);
                $product->subscription = $matches[0]*168;
                $product->save();
            }elseif (str_starts_with($this->stateMake->argumentsService->v, 'm')){
                preg_match('/\d+/', $this->stateMake->argumentsService->v, $matches);
                $product->subscription = $matches[0]*672;
                $product->save();
            }

            return null;

        }else{

            if (preg_match('/\dh$/', $this->stateMake->text)){
                preg_match('/\d+/', $this->stateMake->text, $matches);
                if ($matches[0] == 0) return '12';
                $product->subscription = $matches[0];
                $product->save();
                return null;
            }elseif (preg_match('/\dd$/', $this->stateMake->text)){
                preg_match('/\d+/', $this->stateMake->text, $matches);
                if ($matches[0] == 0) return '12';
                $product->subscription = $matches[0]*24;
                $product->save();
                return null;
            }elseif (preg_match('/\dw$/', $this->stateMake->text)){
                preg_match('/\d+/', $this->stateMake->text, $matches);
                if ($matches[0] == 0) return '12';
                $product->subscription = $matches[0]*168;
                $product->save();
                return null;
            }elseif (preg_match('/\dm$/', $this->stateMake->text)){
                preg_match('/\d+/', $this->stateMake->text, $matches);
                if ($matches[0] == 0) return '12';
                $product->subscription = $matches[0]*672;
                $product->save();
                return null;
            }else{
                return '13';
            }
        }
    }
}
