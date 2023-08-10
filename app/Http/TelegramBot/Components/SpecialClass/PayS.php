<?php

namespace App\Http\TelegramBot\Components\SpecialClass;

use App\Http\TelegramBot\Buttons\SpecialClass\PayProductSpecialButtons;
use App\Http\TelegramBot\Components\RecursionClass\MenuR;
use App\Http\TelegramBot\DefaultClass;
use App\Models\Folder;
use App\Models\Pay;
use Exception;

class PayS extends DefaultClass
{

    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw) {
            case 'Confirm':
                try {
                    $folder = Folder::with('product')->find($this->argumentsService->fp);
                    $product = $folder->product;
                    Pay::create([
                        'user_id' => $this->user->id,
                        'subscription' => $product->subscription ? now()->addHours($product->subscription) : null,
                        'product_id' => $product->id,
                        'price' => $product->price . " $product->currency"
                    ]);
                    $this->user->state()->delete();
                    $this->user->unsetRelation('state');
                    $this->user->updatePurchasedProducts();
                    $this->user->updateCache($this->user);

                    $ok = true;
                    $error_message = null;
                }catch (Exception  $exception){
                    $ok = false;
                    $error_message = 'Something went wrong...';
                }

                $pre_checkout_query_id = $this->update->preCheckoutQuery->get('id');

                return [$pre_checkout_query_id, $ok, $error_message];
            default:
                $this->argumentsService->setArgument('cl', class_basename($this));
                $this->argumentsService->setArgument('bk' , 'MenuR');
                $this->argumentsService->setArgument('bkS' , 'MenuR');
                $this->argumentsService->setArgument('m' , 'C');
                $buttons = PayProductSpecialButtons::defaultButtons($buttons, $this->argumentsService, $this->user);
                $caption = $this->caption('Подтвердите покупку');
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
            case 'Confirm':
                $this->answerPreCheckoutQuery();
                $this->argumentsService->setArgument('bkS' , 'MenuR');
                $this->argumentsService->setArgument('sw' , null);
                (new MenuR($this->user, $this->update, $this->argumentsService))->handleCallbackQuery();
                break;
            default:
                $this->callbackUpdate();
                break;
        }
    }
}
