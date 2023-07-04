<?php

namespace App\Http\TelegramBot\Components\DefaultClass\Admin;

use App\Http\TelegramBot\Buttons\DefaultClass\Admin\AdminRolesButtons;
use App\Http\TelegramBot\DefaultClass;
use App\Http\TelegramBot\States\StateCreate;
use App\Http\TelegramBot\States\StateMake;

class AdminRoles extends DefaultClass
{

    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw){
            case 'CreateRole':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , class_basename($this));
                $this->argumentsService->setArgument('m' , 'C');
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'CreateRole' . $this->argumentsService->m);
                $buttons = AdminRolesButtons::createRoleButtons($buttons, $this->argumentsService);
                $caption = 'Введите роль и её значение("role=50")';
                break;

            case 'СhoiceChangeRole':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , class_basename($this));
                $this->argumentsService->setArgument('m' , 'C');
                $buttons = AdminRolesButtons::choiceChangeRoleButtons($buttons, $this->argumentsService);
                $caption = 'Выберите роль, которую хотите изменить';
                break;
            case 'ChangeRole':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , class_basename($this));
                $this->argumentsService->setArgument('m' , 'C');
                $buttons = AdminRolesButtons::changeRoleButtons($buttons, $this->argumentsService);
                $caption = 'Выберите, что хотите изменить';
                break;
            case 'ChangeRoleName':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , class_basename($this));
                $this->argumentsService->setArgument('m' , 'C');
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'ChangeRoleName' . $this->argumentsService->m);
                $buttons = AdminRolesButtons::confirmChangeRoleNameButtons($buttons, $this->argumentsService);
                $caption = 'Укажите новое имя для роли';
                break;
            case 'ChangeRoleValue':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , class_basename($this));
                $this->argumentsService->setArgument('m' , 'C');
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'ChangeRoleValue' . $this->argumentsService->m);
                $buttons = AdminRolesButtons::confirmChangeRoleValueButtons($buttons, $this->argumentsService);
                $caption = 'Укажите новое значение для роли';
                break;
            case 'СhoiceDeleteRole':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , class_basename($this));
                $this->argumentsService->setArgument('m' , 'C');
                $buttons = AdminRolesButtons::choiceDeleteRoleButtons($buttons, $this->argumentsService);
                $caption = 'Выберите роль, которую хотите удалить';
                break;
            case 'ConfirmDeleteRole':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , class_basename($this));
                $this->argumentsService->setArgument('m' , 'C');
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'DeleteRoleValue' . $this->argumentsService->m);
                $buttons = AdminRolesButtons::confirmDeleteRoleButtons($buttons, $this->argumentsService);
                $caption = 'Вы уверены, что хотите удалить роль?';
                break;
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , 'AdminMenu');
                $this->argumentsService->setArgument('fp' , null);
                $buttons = AdminRolesButtons::defaultButtons($buttons, $this->argumentsService);
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
            case 'MakeDeleteRole':
                $this->argumentsService->setArgument('sw' , 'СhoiceDeleteRole');
                (new StateMake($this->update, $this->user, $this->argumentsService, $this->user->state))->make();
                break;
            default:
                $this->callbackUpdate();
                break;
        }
    }
}
