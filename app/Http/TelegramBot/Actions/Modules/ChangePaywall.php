<?php

namespace App\Http\TelegramBot\Actions\Modules;

use App\Http\TelegramBot\Buttons\Action\Modules\ChangePaywallButtons;
use App\Http\TelegramBot\DefaultClass;
use App\Http\TelegramBot\States\StateCreate;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;

class ChangePaywall extends DefaultClass
{
    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw){
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'ChangePaywall' . $this->argumentsService->m);
                $buttons = ChangePaywallButtons::defaultButtons($buttons, $this->argumentsService);
                $caption = $this->caption('Если включен, то папка будет доступна только после покупки пользователем');
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
            case 'Blocked':
                if ($this->argumentsService->m === 'F'){
                    $folder = Folder::find($this->argumentsService->fp);
                    $folder->blockedPay = !$folder->blockedPay;
                    $folder->save();
                }
                $this->callbackUpdate();
                break;
            default:
                $this->callbackUpdate();
                break;
        }
    }
}
