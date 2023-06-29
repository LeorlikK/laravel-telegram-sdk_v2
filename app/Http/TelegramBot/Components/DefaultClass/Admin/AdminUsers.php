<?php

namespace App\Http\TelegramBot\Components\DefaultClass\Admin;

use App\Http\TelegramBot\Buttons\Action\Modules\ChangePeriodPayButtons;
use App\Http\TelegramBot\Buttons\DefaultClass\Admin\AdminMenuButtons;
use App\Http\TelegramBot\Buttons\DefaultClass\Admin\AdminUsersButtons;
use App\Http\TelegramBot\DefaultClass;
use App\Http\TelegramBot\States\StateCreate;
use App\Http\TelegramBot\States\StateMake;

class AdminUsers extends DefaultClass
{

    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw){
            case 'User':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                [$buttons, $caption] = AdminUsersButtons::userButtons($buttons, $this->argumentsService);
                break;

            case 'ChangeRole':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $buttons = AdminUsersButtons::changeRoleButtons($buttons, $this->argumentsService);
                $caption = 'Выберите новую роль';
                break;
            case 'ConfirmChangeRole':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , class_basename($this));
                $this->argumentsService->setArgument('m' , 'C');
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'ChangeRoleUser' . $this->argumentsService->m);
                $buttons = AdminUsersButtons::confirmChangeRoleButtons($buttons, $this->argumentsService);
                $caption = 'Подтвердите изменение роли';
                break;

            case 'ConfirmUnlock':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , class_basename($this));
                $this->argumentsService->setArgument('m' , 'C');
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'UserUnlock' . $this->argumentsService->m);
                $buttons = AdminUsersButtons::userConfirmUnlockButtons($buttons, $this->argumentsService);
                $caption = 'Вы уверены, что хотите разблокировать пользователя?';
                break;
            case 'ConfirmBlock':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , class_basename($this));
                $this->argumentsService->setArgument('m' , 'C');
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'UserBlock' . $this->argumentsService->m);
                $buttons = AdminUsersButtons::userConfirmBlockButtons($buttons, $this->argumentsService);
                $caption = 'Вы уверены, что хотите заблокировать пользователя?';
                break;

            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , 'AdminMenu');
                $this->argumentsService->setArgument('fp' , null);
                $buttons = AdminUsersButtons::defaultButtons($buttons, $this->argumentsService);
                $caption = $this->caption("Список пользователей");
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
            case 'MakeChangeRole':
                $this->argumentsService->setArgument('sw' , 'User');
                (new StateMake($this->update, $this->user, $this->argumentsService, $this->user->state))->make();
                break;
            case 'MakeUserUnlock':
                $this->argumentsService->setArgument('sw' , 'User');
                (new StateMake($this->update, $this->user, $this->argumentsService, $this->user->state))->make();
                break;
            case 'MakeUserBlock':
                $this->argumentsService->setArgument('sw' , 'User');
                (new StateMake($this->update, $this->user, $this->argumentsService, $this->user->state))->make();
                break;
            default:
                $this->callbackUpdate();
                break;
        }
    }
}
