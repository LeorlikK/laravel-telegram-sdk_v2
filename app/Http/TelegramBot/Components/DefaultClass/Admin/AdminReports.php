<?php

namespace App\Http\TelegramBot\Components\DefaultClass\Admin;

use App\Http\TelegramBot\Buttons\Action\Modules\ChangePeriodPayButtons;
use App\Http\TelegramBot\Buttons\DefaultClass\Admin\AdminMenuButtons;
use App\Http\TelegramBot\Buttons\DefaultClass\Admin\AdminReportsButtons;
use App\Http\TelegramBot\Buttons\DefaultClass\Admin\AdminUsersButtons;
use App\Http\TelegramBot\DefaultClass;
use App\Http\TelegramBot\States\StateCreate;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Report;

class AdminReports extends DefaultClass
{

    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw){
            case 'Answer':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , class_basename($this));
                $this->argumentsService->setArgument('m' , 'C');
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'AnswerReportUser' . $this->argumentsService->m);
                $buttons = AdminReportsButtons::answerReportButtons($buttons, $this->argumentsService);
                $caption = $this->caption("Напишите ответ пользователю");
                break;
            case 'Delete':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , class_basename($this));
                $this->argumentsService->setArgument('m' , 'C');
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'DeleteReportUser' . $this->argumentsService->m);
                $buttons = AdminReportsButtons::deleteReportButtons($buttons, $this->argumentsService);
                $caption = $this->caption("Подтвердите удаление обращения");
                break;
            case 'Report':
                Report::where('id', $this->argumentsService->fp)->where('state', false)->update(['state' => true]);
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                [$buttons, $caption] = AdminReportsButtons::reportButtons($buttons, $this->argumentsService);
                break;
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , 'AdminMenu');
                $this->argumentsService->setArgument('fp' , null);
                $buttons = AdminReportsButtons::defaultButtons($buttons, $this->argumentsService);
                $caption = $this->caption("Список обращений");
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
            case 'MakeDelete':
                (new StateMake($this->update, $this->user, $this->argumentsService, $this->user->state))->make();
                break;
            default:
                $this->callbackUpdate();
                break;
        }
    }
}
