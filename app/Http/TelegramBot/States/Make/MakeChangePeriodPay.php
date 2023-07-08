<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\Info\Alerts\InputAlert;
use App\Http\TelegramBot\Info\Exceptions\InputException;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;

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

            $this->stateMake->argumentsService->er = '19';
            (new InputAlert($this->stateMake->user, $this->stateMake->update,
                $this->stateMake->argumentsService))->handleCallbackQuery();
            return null;

        }else{

            if (preg_match('/\dh$/', $this->stateMake->text)){
                preg_match('/\d+/', $this->stateMake->text, $matches);
                if ($matches[0] == 0) return '12';
                $product->subscription = $matches[0];
                $product->save();
                $this->stateMake->argumentsService->er = '19';
                (new InputAlert($this->stateMake->user, $this->stateMake->update,
                    $this->stateMake->argumentsService))->handleCallbackQuery();
                return null;
            }elseif (preg_match('/\dd$/', $this->stateMake->text)){
                preg_match('/\d+/', $this->stateMake->text, $matches);
                if ($matches[0] == 0) return '12';
                $product->subscription = $matches[0]*24;
                $product->save();
                $this->stateMake->argumentsService->er = '19';
                (new InputAlert($this->stateMake->user, $this->stateMake->update,
                    $this->stateMake->argumentsService))->handleCallbackQuery();
                return null;
            }elseif (preg_match('/\dw$/', $this->stateMake->text)){
                preg_match('/\d+/', $this->stateMake->text, $matches);
                if ($matches[0] == 0) return '12';
                $product->subscription = $matches[0]*168;
                $product->save();
                $this->stateMake->argumentsService->er = '19';
                (new InputAlert($this->stateMake->user, $this->stateMake->update,
                    $this->stateMake->argumentsService))->handleCallbackQuery();
                return null;
            }elseif (preg_match('/\dm$/', $this->stateMake->text)){
                preg_match('/\d+/', $this->stateMake->text, $matches);
                if ($matches[0] == 0) return '12';
                $product->subscription = $matches[0]*672;
                $product->save();
                $this->stateMake->argumentsService->er = '19';
                (new InputAlert($this->stateMake->user, $this->stateMake->update,
                    $this->stateMake->argumentsService))->handleCallbackQuery();
                return null;
            }else{
                return '13';
            }
        }
    }
}
