<?php

namespace App\Http\TelegramBot\TradeShop;

use App\Http\TelegramBot\Buttons\SpecialClass\PayProductSpecialButtons;
use App\Http\TelegramBot\DefaultClass;
use App\Http\TelegramBot\States\StateCreate;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;

class Yookassa extends DefaultClass
{

    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw) {
            default:
                $folder = Folder::with('product')->find($this->argumentsService->fp);

                $recipient = $this->user->tg_id;
                $amount = $folder->product->price;
                $currency = strtoupper($folder->product->currency);
                $description = strtoupper($folder->product->currency);
                $due_date = now()->format('Y-m-d');
                $this->argumentsService->setArgument('cl', class_basename($this));
//                $this->argumentsService->setArgument('bk' , 'MenuR');
//                $this->argumentsService->setArgument('bkS' , 'MenuR');
//                $this->argumentsService->setArgument('m' , 'C');
//                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'PayProduct' . $this->argumentsService->m);
//                $buttons = PayProductSpecialButtons::defaultButtons($buttons, $this->argumentsService, $this->user);
//                $caption = $this->caption('Подтвердите покупку');
                break;
        }

//        $caption = $caption ?? $this->caption('');
//        $photo = $photo ?? $this->photo(null);
//        $reply_markup = $reply_markup ?? $this->replyMarkup($buttons);

        return [$recipient, $amount, $currency, $description, $due_date];
    }

    public function handleCallbackQuery(): void
    {
        switch ($this->argumentsService->sw){
            default:
                $this->sendInvoice();
                break;
        }
    }
}
