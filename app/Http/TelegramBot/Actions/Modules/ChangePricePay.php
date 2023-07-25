<?php

namespace App\Http\TelegramBot\Actions\Modules;

use App\Http\TelegramBot\Buttons\Action\Modules\ChangePricePayButtons;
use App\Http\TelegramBot\DefaultClass;
use App\Http\TelegramBot\States\StateCreate;

class ChangePricePay extends DefaultClass
{
    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw){
            case 'Val':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'ChangePricePay' . $this->argumentsService->m);
                $buttons = ChangePricePayButtons::indicatePrice($buttons, $this->argumentsService);

                if ($this->argumentsService->m == 'F1'){
                    $priceMin = str_replace( '.', ',', config('telegram.pay.min_and_max_price.rub_min')) . ' R';
                    $priceMax = str_replace( '.', ',', config('telegram.pay.min_and_max_price.rub_max')) . ' R';
                    $currency = 'рублей';
                }elseif ($this->argumentsService->m == 'F2'){
                    $priceMin = str_replace( '.', ',', config('telegram.pay.min_and_max_price.usd_min')) . ' $';
                    $priceMax = str_replace( '.', ',', config('telegram.pay.min_and_max_price.usd_max')) . ' $';
                    $currency = 'долларов';
                }else{
                    $priceMin = str_replace( '.', ',', config('telegram.pay.min_and_max_price.eur_min')) . ' €';
                    $priceMax = str_replace( '.', ',', config('telegram.pay.min_and_max_price.eur_max')) . ' €';
                    $currency = 'евро';
                }
                $caption = $this->caption("Введите цену. Минимальная цена для $currency = $priceMin, максимальня = $priceMax");

                break;
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $buttons = ChangePricePayButtons::defaultButtons($buttons, $this->argumentsService);
                $caption = $this->caption("Выберите в какой валюте указать цену");
                break;
        }

        $caption = $caption ?? $this->caption('');
        $photo = $photo ?? $this->photo(null);
        $reply_markup = $reply_markup ?? $this->replyMarkup($buttons);

        return [$photo, $caption, $reply_markup];
    }

    public function handleCallbackQuery(): void
    {
        switch ($this->argumentsService->sw){
            default:
                $this->callbackUpdate();
                break;
        }
    }
}
