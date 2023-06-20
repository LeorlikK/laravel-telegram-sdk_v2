<?php

namespace App\Http\TelegramBot\Actions\Modules;

use App\Http\TelegramBot\ActionModuleClass;
use App\Http\TelegramBot\Buttons\Action\Modules\ChangeSecrecyButtons;
use App\Http\TelegramBot\Buttons\Action\Modules\ChangeVisibilityButtons;
use App\Http\TelegramBot\DefaultClass;
use App\Http\TelegramBot\States\StateCreate;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;

class ChangeVisibility extends DefaultClass
{
    public function main(): array
    {
        $buttons = collect();

        switch (true){
            case $this->argumentsService->sw === 'Scope':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'ChangeVisibility' . $this->argumentsService->m);
                $buttons = ChangeVisibilityButtons::scope($buttons, $this->argumentsService);
                $caption = $this->caption('Выберите уровень доступа к папке или введите число от 0 до 100');
                break;
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $buttons = ChangeVisibilityButtons::defaultButtons($buttons, $this->argumentsService);
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
                (new StateMake($this->update, $this->user, $this->argumentsService, $this->user->state))->make();
                break;
            case 'Blocked':
                if ($this->argumentsService->m === 'F'){
                    $folder = Folder::find($this->argumentsService->fp);
                    $folder->blocked = !$folder->blocked;
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
