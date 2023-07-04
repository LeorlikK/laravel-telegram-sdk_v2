<?php

namespace App\Http\TelegramBot\Actions;

use App\Http\TelegramBot\Actions\Modules\ChangeBasketPay;
use App\Http\TelegramBot\Actions\Modules\ChangeCaption;
use App\Http\TelegramBot\Actions\Modules\ChangeEmoji;
use App\Http\TelegramBot\Actions\Modules\ChangeImage;
use App\Http\TelegramBot\Actions\Modules\ChangeName;
use App\Http\TelegramBot\Actions\Modules\ChangePaywall;
use App\Http\TelegramBot\Actions\Modules\ChangePeriodPay;
use App\Http\TelegramBot\Actions\Modules\ChangePricePay;
use App\Http\TelegramBot\Actions\Modules\ChangeSecrecy;
use App\Http\TelegramBot\Actions\Modules\ChangeSorted;
use App\Http\TelegramBot\Actions\Modules\ChangeVisibility;
use App\Http\TelegramBot\Actions\Modules\Create;
use App\Http\TelegramBot\Actions\Modules\CreateSpecialClass;
use App\Http\TelegramBot\Actions\Modules\Delete;
use App\Http\TelegramBot\Buttons\Action\MenuMonetizationButtons;
use App\Http\TelegramBot\DefaultClass;

class MenuM extends DefaultClass
{
    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw){
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , 'MenuR');
                $buttons = MenuMonetizationButtons::defaultButtons($buttons, $this->argumentsService);
                $caption = $this->caption('Выберите опцию');
                $photo = $this->photo(null);
                $reply_markup = $this->replyMarkup($buttons);
                break;
        }

        return [$photo, $caption, $reply_markup];
    }

    public function handleCallbackQuery(): void
    {
        switch ($this->argumentsService->sw){
            case 'CreateF':
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , 'MenuR');
                $this->argumentsService->setArgument('sw' , null);
                $this->argumentsService->setArgument('m' , 'F');
                (new Create($this->user, $this->update, $this->argumentsService))->handleCallbackQuery();
                break;
            case 'ChangeNameF':
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , 'MenuR');
                $this->argumentsService->setArgument('sw' , null);
                $this->argumentsService->setArgument('m' , 'F');
                (new ChangeName($this->user, $this->update, $this->argumentsService))->handleCallbackQuery();
                break;
            case 'ChangeEmojiF':
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , 'MenuR');
                $this->argumentsService->setArgument('sw' , null);
                $this->argumentsService->setArgument('m' , 'F');
                (new ChangeEmoji($this->user, $this->update, $this->argumentsService))->handleCallbackQuery();
                break;
            case 'ChangeCaptionF':
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , 'MenuR');
                $this->argumentsService->setArgument('sw' , null);
                $this->argumentsService->setArgument('m' , 'F');
                (new ChangeCaption($this->user, $this->update, $this->argumentsService))->handleCallbackQuery();
                break;
            case 'ChangeImageF':
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , 'MenuR');
                $this->argumentsService->setArgument('sw' , null);
                $this->argumentsService->setArgument('m' , 'F');
                (new ChangeImage($this->user, $this->update, $this->argumentsService))->handleCallbackQuery();
                break;
            case 'ChangeSecrecyF':
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , 'MenuR');
                $this->argumentsService->setArgument('sw' , null);
                $this->argumentsService->setArgument('m' , 'F');
                (new ChangeSecrecy($this->user, $this->update, $this->argumentsService))->handleCallbackQuery();
                break;
            case 'ChangeVisibilityF':
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , 'MenuR');
                $this->argumentsService->setArgument('sw' , null);
                $this->argumentsService->setArgument('m' , 'F');
                (new ChangeVisibility($this->user, $this->update, $this->argumentsService))->handleCallbackQuery();
                break;
            case 'ChangeSortedF':
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , 'MenuR');
                $this->argumentsService->setArgument('sw' , null);
                $this->argumentsService->setArgument('m' , 'F');
                (new ChangeSorted($this->user, $this->update, $this->argumentsService))->handleCallbackQuery();
                break;
            case 'DeleteM':
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , 'MenuR');
                $this->argumentsService->setArgument('sw' , 'M');
                $this->argumentsService->setArgument('m' , 'M');
                (new Delete($this->user, $this->update, $this->argumentsService))->handleCallbackQuery();
                break;
            case 'PaywallF':
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , 'MenuR');
                $this->argumentsService->setArgument('sw' , null);
                $this->argumentsService->setArgument('m' , 'F');
                (new ChangePaywall($this->user, $this->update, $this->argumentsService))->handleCallbackQuery();
                break;
            case 'CreateC':
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , 'MenuR');
                $this->argumentsService->setArgument('sw' , null);
                $this->argumentsService->setArgument('m' , 'C');
                (new CreateSpecialClass($this->user, $this->update, $this->argumentsService))->handleCallbackQuery();
                break;
            case 'PeriodF':
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , 'MenuR');
                $this->argumentsService->setArgument('sw' , null);
                $this->argumentsService->setArgument('m' , 'F');
                (new ChangePeriodPay($this->user, $this->update, $this->argumentsService))->handleCallbackQuery();
                break;
            case 'PriceF':
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , 'MenuR');
                $this->argumentsService->setArgument('sw' , null);
                $this->argumentsService->setArgument('m' , 'F');
                (new ChangePricePay($this->user, $this->update, $this->argumentsService))->handleCallbackQuery();
                break;
            case 'BasketF':
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , 'MenuR');
                $this->argumentsService->setArgument('sw' , null);
                $this->argumentsService->setArgument('m' , 'F');
                (new ChangeBasketPay($this->user, $this->update, $this->argumentsService))->handleCallbackQuery();
                break;
            default:
                $this->callbackUpdate();
                break;
        }
    }
}
