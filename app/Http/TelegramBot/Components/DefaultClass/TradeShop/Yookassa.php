<?php

namespace App\Http\TelegramBot\Components\DefaultClass\TradeShop;

use App\Http\TelegramBot\Buttons\DefaultClass\TradeShop\YookassaButtons;
use App\Http\TelegramBot\Buttons\SpecialClass\PayProductSpecialButtons;
use App\Http\TelegramBot\DefaultClass;
use App\Models\Folder;
use Illuminate\Support\Str;
use Telegram\Bot\Objects\Payments\LabeledPrice;

class Yookassa extends DefaultClass
{

    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw) {
            case 'Pay':
                dump($this->update->callbackQuery);
                $this->argumentsService->setArgument('cl', class_basename($this));
                $this->argumentsService->setArgument('bk' , 'MenuR');
                $buttons = YookassaButtons::defaultButtons($buttons, $this->argumentsService, $this->user);
            default:
                $folder = Folder::with('product')->find($this->argumentsService->fp);
                $price = $folder->product->price;

                $chat_id = $this->user->tg_id;
                $title = Str::substr($folder->name, 0, 32);
                $description = Str::substr($folder->caption ?? 'Not description', 0, 255);
                $start_parameter = 'test-payment';
                $payload =
                    'cl:PayS'.'_'.
                    'sw:Confirm'.'_'.
                    'fp:'.($this->argumentsService->fp).'_'.
                    "mi:".($this->update->getMessage()->get('message_id')).'_'.
                    'chat:'.($this->update->getChat()->get('id'))
                ;
                $provider_token = config('telegram.pay.yookassa.provider_token');
                $currency = mb_strtoupper($folder->product->currency);
                $prices = LabeledPrice::make([
                    'label' => 'Label',
                    'amount' => $price * 100
                ]);
                $this->argumentsService->setArgument('cl', class_basename($this));

                break;
        }

        $caption = $caption ?? $this->caption('');
        $photo = $photo ?? $this->photo($folder->media);
        $reply_markup = $reply_markup ?? $this->replyMarkup($buttons);

        return [$chat_id, $title, $description, $start_parameter, $payload,
            $provider_token, $currency, $prices, $photo, $reply_markup];
    }

    public function handleCallbackQuery(): void
    {
        switch ($this->argumentsService->sw){
            case 'Confirm':
                $this->answerPreCheckoutQuery();
                break;
            default:
                $this->sendInvoice();
                break;
        }
    }
}
